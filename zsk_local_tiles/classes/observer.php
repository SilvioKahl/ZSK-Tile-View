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

namespace local_zsk_local_tiles;

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/../lib.php');

/**
 * Event observers for category tile rendering.
 */
class observer {

    /**
     * @param \core\event\course_category_viewed $event
     */
    public static function course_category_viewed(\core\event\course_category_viewed $event): void {
        if (!\local_zsk_local_tiles_enabled_for('category')) {
            return;
        }
        \local_zsk_local_tiles_inject_category_tiles_for_category((int) $event->objectid);
    }
}
