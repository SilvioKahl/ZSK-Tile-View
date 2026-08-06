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

/**
 * Default dashboard block placement (centre / content region).
 */
class dashboard_block {

    /**
     * Create a block instance on the default dashboard (/my/) in the content region if missing.
     *
     * @return void
     */
    public static function ensure_default_instance(): void {
        global $CFG, $DB;

        if (!get_config('local_zsk_local_tiles', 'tiles_dashboard')) {
            return;
        }

        $systemcontext = \context_system::instance();

        if ($DB->record_exists('block_instances', [
            'blockname' => 'coursetiles',
            'parentcontextid' => $systemcontext->id,
            'pagetypepattern' => 'my-index',
        ])) {
            return;
        }

        require_once($CFG->libdir . '/blocklib.php');

        $subpagepattern = null;
        $defaultmypage = $DB->get_record('my_pages', [
            'userid' => null,
            'name' => '__default',
            'private' => 1,
        ], 'id', IGNORE_MISSING);
        if ($defaultmypage) {
            $subpagepattern = (int) $defaultmypage->id;
        }

        $page = new \moodle_page();
        $page->set_context($systemcontext);
        $page->blocks->add_blocks(
            ['content' => ['coursetiles']],
            'my-index',
            $subpagepattern
        );
    }
}
