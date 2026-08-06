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

namespace local_zsk_local_tiles\admin;

use local_zsk_local_tiles\util\license;

defined('MOODLE_INTERNAL') || die();

/**
 * License key setting that verifies against the external API on save.
 */
class setting_license_key extends \admin_setting_configpasswordunmask {

    public function __construct() {
        parent::__construct(
            'local_zsk_local_tiles/license_key',
            get_string('license_key', 'local_zsk_local_tiles'),
            get_string('license_key_desc', 'local_zsk_local_tiles'),
            ''
        );
    }

    /**
     * @param mixed $data
     * @return string
     */
    public function write_setting($data) {
        if (empty($data)) {
            set_config('license_key', '', 'local_zsk_local_tiles');
            license::clear_license(false);
            return '';
        }

        $result = parent::write_setting($data);
        if ($result !== '') {
            return $result;
        }

        if (license::get_server_url() === '') {
            // Keep key saved; do not block admin form completion.
            return '';
        }

        // Never return an error after the key was stored – that breaks upgradesettings.
        self::verify_or_error();
        return '';
    }

    /**
     * Re-verify after other license settings changed; never block the save.
     *
     * @return void
     */
    public static function notify_verify_result(): void {
        $verify = license::verify();
        if ($verify->success) {
            return;
        }

        if (!empty($verify->network_error) || ($verify->error_code ?? '') === 'bad_response') {
            \core\notification::warning(
                $verify->message !== ''
                    ? $verify->message
                    : get_string('license_warning_network_deferred', 'local_zsk_local_tiles')
            );
            return;
        }

        \core\notification::warning($verify->message);
    }

    /**
     * @return string Empty string on success or deferred verify.
     */
    public static function verify_or_error(): string {
        $verify = license::verify();
        if ($verify->success) {
            \core\notification::success($verify->message);
            return '';
        }

        if (!empty($verify->network_error) || ($verify->error_code ?? '') === 'bad_response') {
            \core\notification::warning(
                $verify->message !== ''
                    ? $verify->message
                    : get_string('license_warning_network_deferred', 'local_zsk_local_tiles')
            );
            return '';
        }

        return $verify->message !== ''
            ? $verify->message
            : get_string('license_error_network', 'local_zsk_local_tiles');
    }
}
