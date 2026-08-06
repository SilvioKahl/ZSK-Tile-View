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

/**
 * CLI license verification test for ZSK Course tiles.
 *
 * @package    local_zsk_local_tiles
 * @copyright  2025 Silvio Kuhn
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define('CLI_SCRIPT', true);

require(__DIR__ . '/../../../config.php');
require_once($CFG->libdir . '/clilib.php');

use local_zsk_local_tiles\util\license;

list($options, $unrecognized) = cli_get_params([
    'help' => false,
    'url' => '',
    'key' => '',
], ['h' => 'help']);

if ($unrecognized) {
    $unrecognized = implode(PHP_EOL . '  ', $unrecognized);
    cli_error(get_string('cliunknowoption', 'admin', $unrecognized));
}

if ($options['help']) {
    echo get_string('cli_license_help', 'local_zsk_local_tiles');
    exit(0);
}

$serverurl = $options['url'] !== ''
    ? license::normalize_server_url($options['url'])
    : license::get_server_url();
$key = $options['key'] !== ''
    ? trim($options['key'])
    : trim((string) get_config('local_zsk_local_tiles', 'license_key'));
$siteurl = rtrim(strtolower($CFG->wwwroot), '/');

cli_heading(get_string('cli_license_heading', 'local_zsk_local_tiles'));

echo get_string('cli_license_wwwroot', 'local_zsk_local_tiles', $siteurl) . "\n";
echo get_string('cli_license_verifyurl', 'local_zsk_local_tiles', $serverurl ?: get_string('cli_license_empty', 'local_zsk_local_tiles')) . "\n";
echo get_string('cli_license_plugin', 'local_zsk_local_tiles') . "\n";
$keyprefix = $key !== '' ? substr($key, 0, 8) . '…' : get_string('cli_license_empty', 'local_zsk_local_tiles');
echo get_string('cli_license_keyprefix', 'local_zsk_local_tiles', $keyprefix) . "\n\n";

if ($serverurl === '' || $key === '') {
    cli_error(get_string('cli_license_missing_config', 'local_zsk_local_tiles'));
}

$body = json_encode([
    'license_key' => $key,
    'site_url' => $siteurl,
    'plugin' => 'local_zsk_local_tiles',
]);

$curl = new curl();
$response = $curl->post($serverurl, $body, [
    'CURLOPT_HTTPHEADER' => [
        'Content-Type: application/json',
        'Accept: application/json',
    ],
    'CURLOPT_TIMEOUT' => 20,
    'CURLOPT_CONNECTTIMEOUT' => 10,
]);

$errno = $curl->get_errno();
$error = $errno ? (string) ($curl->error ?? '') : '';
if ($error === '' && $errno && function_exists('curl_strerror')) {
    $error = curl_strerror($errno);
}
$info = $curl->get_info();
$httpcode = (int) ($info['http_code'] ?? 0);

echo get_string('cli_license_httpstatus', 'local_zsk_local_tiles', $httpcode) . "\n";
if ($errno) {
    echo get_string('cli_license_curlerror', 'local_zsk_local_tiles', (object) [
        'errno' => $errno,
        'error' => $error,
    ]) . "\n";
}
echo get_string('cli_license_response', 'local_zsk_local_tiles') . "\n{$response}\n\n";

$data = json_decode((string) $response, true);
if (!is_array($data)) {
    cli_error(get_string('cli_license_no_json', 'local_zsk_local_tiles'));
}

if (!empty($data['valid'])) {
    echo get_string('cli_license_ok', 'local_zsk_local_tiles', userdate((int) ($data['expires'] ?? 0))) . "\n";
    exit(0);
}

$code = $data['error_code'] ?? 'unknown';
$message = $data['message'] ?? '';
$errortext = get_string('cli_license_failed', 'local_zsk_local_tiles', $code);
if ($message !== '') {
    $errortext .= ' – ' . $message;
}
cli_error($errortext);
exit(1);
