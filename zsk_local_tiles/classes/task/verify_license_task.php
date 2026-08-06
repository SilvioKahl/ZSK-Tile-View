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

namespace local_zsk_local_tiles\task;

use local_zsk_local_tiles\util\license;

defined('MOODLE_INTERNAL') || die();

/**
 * Re-validates the premium license against the external API.
 */
class verify_license_task extends \core\task\scheduled_task {

    /**
     * @return string
     */
    public function get_name(): string {
        return get_string('task_verify_license', 'local_zsk_local_tiles');
    }

    /**
     * @return void
     */
    public function execute(): void {
        if (empty(get_config('local_zsk_local_tiles', 'license_key'))) {
            return;
        }
        license::verify();
    }
}
