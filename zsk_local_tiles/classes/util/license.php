<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

namespace local_zsk_local_tiles\util;

defined('MOODLE_INTERNAL') || die();

/**
 * License verification and freemium feature gates for ZSK Kachelansicht.
 */
class license {

    public const TIER_PREMIUM = 'premium';

    /** @var string[] Free tier: dashboard + my courses only. */
    public const FREE_CONTEXTS = ['dashboard', 'mycourses'];

    private const CONFIG_PREFIX = 'local_zsk_local_tiles';
    private const DEFAULT_GRACE_DAYS = 7;

    /**
     * @return string
     */
    private static function get_effective_license_key(): string {
        return trim((string) get_config(self::CONFIG_PREFIX, 'license_key'));
    }

    /**
     * @return bool
     */
    public static function is_premium(): bool {
        return self::has_active_license_tier([self::TIER_PREMIUM, 'enterprise']);
    }

    /**
     * @param string[] $allowedtiers
     * @return bool
     */
    private static function has_active_license_tier(array $allowedtiers): bool {
        if (self::get_effective_license_key() === '') {
            return false;
        }

        $payload = self::decode_token(get_config(self::CONFIG_PREFIX, 'license_token'));
        if ($payload === null || empty($payload['valid'])) {
            return false;
        }

        $tier = (string) ($payload['tier'] ?? self::TIER_PREMIUM);
        if (!in_array($tier, $allowedtiers, true)) {
            return false;
        }

        $now = time();
        if (!empty($payload['expires']) && (int) $payload['expires'] > $now) {
            return true;
        }

        return (int) get_config(self::CONFIG_PREFIX, 'license_grace_until') > $now;
    }

    /**
     * Whether tiles may render on this page context.
     *
     * @param string $context category|dashboard|frontpage|mycourses|search
     * @return bool
     */
    public static function is_context_allowed(string $context): bool {
        if (self::is_premium()) {
            return true;
        }
        return in_array($context, self::FREE_CONTEXTS, true);
    }

    /**
     * @return bool
     */
    public static function can_use_frontpage(): bool {
        return self::is_premium();
    }

    /**
     * @return bool
     */
    public static function can_use_category_pages(): bool {
        return self::is_premium();
    }

    /**
     * @return bool
     */
    public static function can_use_unenrolled_courses(): bool {
        return self::is_premium();
    }

    /**
     * @return bool
     */
    public static function can_show_tile_footer_info(): bool {
        return self::is_premium();
    }

    /**
     * @return bool
     */
    public static function can_use_custom_placeholder(): bool {
        return self::is_premium();
    }

    /**
     * @return bool
     */
    public static function can_use_custom_layout(): bool {
        return self::is_premium();
    }

    /**
     * @return bool
     */
    public static function can_use_custom_colors(): bool {
        return self::is_premium();
    }

    /**
     * @return bool
     */
    public static function show_branding(): bool {
        return !self::is_premium();
    }

    /**
     * @return \stdClass
     */
    public static function verify(): \stdClass {
        $result = (object) [
            'success' => false,
            'error_code' => '',
            'message' => '',
            'network_error' => false,
        ];

        $licensekey = self::get_effective_license_key();
        if ($licensekey === '') {
            self::clear_license();
            $result->message = self::get_status_string();
            return $result;
        }

        $serverurl = self::normalize_server_url(self::get_server_url());
        if ($serverurl === '') {
            $result->error_code = 'no_server';
            $result->message = get_string('license_error_no_server', 'local_zsk_local_tiles');
            set_config('license_last_error', 'no_server', self::CONFIG_PREFIX);
            return $result;
        }

        $siteurl = rtrim(strtolower($GLOBALS['CFG']->wwwroot), '/');
        $body = json_encode([
            'license_key' => $licensekey,
            'site_url' => $siteurl,
            'plugin' => 'local_zsk_local_tiles',
        ]);

        $curl = new \curl();
        $response = $curl->post($serverurl, $body, [
            'CURLOPT_HTTPHEADER' => [
                'Content-Type: application/json',
                'Accept: application/json',
            ],
            'CURLOPT_TIMEOUT' => 20,
            'CURLOPT_CONNECTTIMEOUT' => 10,
        ]);

        $curlerrno = $curl->get_errno();
        $curlerror = $curlerrno ? (string) ($curl->error ?? '') : '';
        if ($curlerror === '' && $curlerrno && function_exists('curl_strerror')) {
            $curlerror = curl_strerror($curlerrno);
        }
        $info = $curl->get_info();
        $httpcode = (int) ($info['http_code'] ?? 0);

        self::store_verify_debug($httpcode, $curlerror, (string) $response, $siteurl, $serverurl);

        $data = json_decode((string) $response, true);
        if (is_array($data)) {
            if (empty($data['valid'])) {
                self::clear_license(false);
                $result->error_code = $data['error_code'] ?? 'invalid';
                $result->message = self::map_error_message($result->error_code, $data['message'] ?? '');
                return $result;
            }

            $sitesused = (int) ($data['sites_used'] ?? 0);
            $sitesmax = (int) ($data['sites_max'] ?? 0);

            $token = self::encode_token([
                'valid' => true,
                'expires' => (int) ($data['expires'] ?? (time() + DAYSECS)),
                'tier' => $data['tier'] ?? self::TIER_PREMIUM,
                'sites_used' => $sitesused,
                'sites_max' => $sitesmax,
            ]);
            set_config('license_token', $token, self::CONFIG_PREFIX);
            set_config('license_last_success', time(), self::CONFIG_PREFIX);
            set_config('license_grace_until', 0, self::CONFIG_PREFIX);
            set_config('license_last_error', '', self::CONFIG_PREFIX);
            set_config('license_debug_http_code', 0, self::CONFIG_PREFIX);
            set_config('license_debug_curl_error', '', self::CONFIG_PREFIX);
            set_config('license_debug_response', '', self::CONFIG_PREFIX);

            $result->success = true;
            $result->message = self::format_premium_status($sitesused, $sitesmax);
            return $result;
        }

        if ($curlerrno) {
            $result->curl_error = $curlerror;
            return self::handle_network_failure($result);
        }

        $result->error_code = 'bad_response';
        $result->message = get_string('license_error_bad_response', 'local_zsk_local_tiles', (object) [
            'httpcode' => $httpcode,
            'url' => $serverurl,
        ]);
        set_config('license_last_error', 'bad_response', self::CONFIG_PREFIX);
        return $result;
    }

    /**
     * @param \stdClass $result
     * @return \stdClass
     */
    private static function handle_network_failure(\stdClass $result): \stdClass {
        $result->network_error = true;
        $result->error_code = 'network';

        if (self::activate_grace_period()) {
            $result->success = true;
            $result->message = get_string('license_status_grace', 'local_zsk_local_tiles', self::get_grace_days());
            set_config('license_last_error', 'network', self::CONFIG_PREFIX);
            return $result;
        }

        $result->message = get_string('license_error_network', 'local_zsk_local_tiles');
        if (!empty($result->curl_error)) {
            $result->message .= ' (' . $result->curl_error . ')';
        }
        set_config('license_last_error', 'network', self::CONFIG_PREFIX);
        return $result;
    }

    /**
     * @return bool
     */
    private static function activate_grace_period(): bool {
        $payload = self::decode_token(get_config(self::CONFIG_PREFIX, 'license_token'));
        $lastsuccess = (int) get_config(self::CONFIG_PREFIX, 'license_last_success');

        if ($payload === null || empty($payload['valid']) || $lastsuccess === 0) {
            return false;
        }

        set_config('license_grace_until', time() + (self::get_grace_days() * DAYSECS), self::CONFIG_PREFIX);
        return true;
    }

    /**
     * @return int
     */
    public static function get_grace_days(): int {
        $days = (int) get_config(self::CONFIG_PREFIX, 'license_grace_days');
        return max(1, $days ?: self::DEFAULT_GRACE_DAYS);
    }

    /**
     * @return string
     */
    public static function get_server_url(): string {
        return self::normalize_server_url((string) get_config(self::CONFIG_PREFIX, 'license_server_url'));
    }

    /**
     * @param string $url
     * @return string
     */
    public static function normalize_server_url(string $url): string {
        $url = trim($url);
        if ($url === '') {
            return '';
        }
        if (!preg_match('#^https?://#i', $url)) {
            $url = 'http://' . $url;
        }
        return rtrim($url, '/');
    }

    /**
     * Technical details for admins when verification fails.
     *
     * @return string
     */
    public static function get_diagnostic_summary(): string {
        global $CFG;

        $lines = [
            get_string('license_diag_wwwroot', 'local_zsk_local_tiles', rtrim(strtolower($CFG->wwwroot), '/')),
            get_string('license_diag_server_url', 'local_zsk_local_tiles', self::get_server_url() ?: '—'),
        ];

        $key = self::get_effective_license_key();
        if ($key !== '') {
            $prefix = substr($key, 0, min(8, strlen($key)));
            $lines[] = get_string('license_diag_key_prefix', 'local_zsk_local_tiles', $prefix . '…');
        }

        $httpcode = (int) get_config(self::CONFIG_PREFIX, 'license_debug_http_code');
        $curlerror = (string) get_config(self::CONFIG_PREFIX, 'license_debug_curl_error');
        $snippet = (string) get_config(self::CONFIG_PREFIX, 'license_debug_response');

        if ($httpcode > 0) {
            $lines[] = get_string('license_diag_http_code', 'local_zsk_local_tiles', $httpcode);
        }
        if ($curlerror !== '') {
            $lines[] = get_string('license_diag_curl_error', 'local_zsk_local_tiles', s($curlerror));
        }
        if ($snippet !== '') {
            $lines[] = get_string('license_diag_response', 'local_zsk_local_tiles', s($snippet));
        }

        $lines[] = get_string('license_diag_cli_hint', 'local_zsk_local_tiles');

        return \html_writer::alist($lines);
    }

    /**
     * @param int $httpcode
     * @param string $curlerror
     * @param string $response
     * @param string $siteurl
     * @param string $serverurl
     * @return void
     */
    private static function store_verify_debug(
        int $httpcode,
        string $curlerror,
        string $response,
        string $siteurl,
        string $serverurl
    ): void {
        set_config('license_debug_http_code', $httpcode, self::CONFIG_PREFIX);
        set_config('license_debug_curl_error', $curlerror, self::CONFIG_PREFIX);
        set_config('license_debug_site_url', $siteurl, self::CONFIG_PREFIX);
        set_config('license_debug_server_url', $serverurl, self::CONFIG_PREFIX);

        $snippet = preg_replace('/\s+/', ' ', trim($response));
        if (strlen($snippet) > 240) {
            $snippet = substr($snippet, 0, 240) . '…';
        }
        set_config('license_debug_response', $snippet, self::CONFIG_PREFIX);
    }

    /**
     * @return void
     */
    public static function refresh_status_if_key_present(): void {
        if (self::get_effective_license_key() !== '' && self::get_server_url() !== '') {
            self::verify();
        }
    }

    /**
     * @param bool $removekey
     * @return void
     */
    public static function clear_license(bool $removekey = true): void {
        unset_config('license_token', self::CONFIG_PREFIX);
        unset_config('license_grace_until', self::CONFIG_PREFIX);
        unset_config('license_last_success', self::CONFIG_PREFIX);
        unset_config('license_last_error', self::CONFIG_PREFIX);
        unset_config('license_debug_http_code', self::CONFIG_PREFIX);
        unset_config('license_debug_curl_error', self::CONFIG_PREFIX);
        unset_config('license_debug_response', self::CONFIG_PREFIX);
        if ($removekey) {
            unset_config('license_key', self::CONFIG_PREFIX);
        }
    }

    /**
     * @return string
     */
    public static function get_status_string(): string {
        if (self::get_effective_license_key() === '') {
            return get_string('license_status_free', 'local_zsk_local_tiles');
        }

        $payload = self::decode_token(get_config(self::CONFIG_PREFIX, 'license_token'));
        $now = time();

        if ($payload && !empty($payload['valid']) && !empty($payload['expires']) && (int) $payload['expires'] > $now) {
            return self::format_premium_status(
                (int) ($payload['sites_used'] ?? 0),
                (int) ($payload['sites_max'] ?? 0)
            );
        }

        $graceuntil = (int) get_config(self::CONFIG_PREFIX, 'license_grace_until');
        if ($graceuntil > $now) {
            return get_string('license_status_grace', 'local_zsk_local_tiles', self::get_grace_days());
        }

        $lasterror = (string) get_config(self::CONFIG_PREFIX, 'license_last_error');
        if ($lasterror !== '') {
            return self::map_error_message($lasterror, '');
        }

        if (self::get_server_url() === '') {
            return get_string('license_status_key_no_server', 'local_zsk_local_tiles');
        }

        return get_string('license_status_key_unverified', 'local_zsk_local_tiles');
    }

    /**
     * @param string $code
     * @param string $fallback
     * @return string
     */
    private static function map_error_message(string $code, string $fallback): string {
        set_config('license_last_error', $code, self::CONFIG_PREFIX);

        $map = [
            'expired' => 'license_error_expired',
            'invalid_key' => 'license_error_invalid',
            'no_server' => 'license_error_no_server',
            'site_mismatch' => 'license_error_site_mismatch',
            'site_limit_reached' => 'license_error_site_limit',
            'inactive' => 'license_error_inactive',
            'plugin_mismatch' => 'license_error_plugin_mismatch',
        ];

        if ($code === 'site_limit_reached') {
            $payload = self::decode_token(get_config(self::CONFIG_PREFIX, 'license_token'));
            $max = (int) ($payload['sites_max'] ?? 3);
            return get_string('license_error_site_limit', 'local_zsk_local_tiles', $max);
        }

        if ($code === 'bad_response') {
            return get_string('license_error_bad_response_short', 'local_zsk_local_tiles');
        }

        if ($code === 'network') {
            return get_string('license_error_network', 'local_zsk_local_tiles');
        }

        if (!empty($map[$code])) {
            return get_string($map[$code], 'local_zsk_local_tiles');
        }

        return $fallback !== '' ? $fallback : get_string('license_error_invalid', 'local_zsk_local_tiles');
    }

    /**
     * @param int $sitesused
     * @param int $sitesmax
     * @return string
     */
    private static function format_premium_status(int $sitesused, int $sitesmax): string {
        if ($sitesmax > 0) {
            return get_string('license_status_premium_slots', 'local_zsk_local_tiles', (object) [
                'used' => $sitesused,
                'max' => $sitesmax,
            ]);
        }
        return get_string('license_status_premium', 'local_zsk_local_tiles');
    }

    /**
     * @param array $payload
     * @return string
     */
    private static function encode_token(array $payload): string {
        return base64_encode(json_encode($payload));
    }

    /**
     * @param string|null $token
     * @return array|null
     */
    private static function decode_token(?string $token): ?array {
        if (empty($token)) {
            return null;
        }
        $decoded = json_decode(base64_decode($token), true);
        return is_array($decoded) ? $decoded : null;
    }
}
