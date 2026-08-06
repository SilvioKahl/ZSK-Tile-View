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
 * Course tiles dashboard block class.
 *
 * @package    block_coursetiles
 * @copyright  2025 Silvio Kuhn
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

/**
 * Course/category tiles as a standard Moodle block (dashboard).
 *
 * Requires local_zsk_local_tiles for data and styling.
 */
class block_coursetiles extends block_base {

    public function init(): void {
        $this->title = get_string('pluginname', 'block_coursetiles');
    }

    public function applicable_formats(): array {
        return [
            'all' => false,
            'my-index' => true,
        ];
    }

    public function instance_allow_multiple(): bool {
        return false;
    }

    public function has_config(): bool {
        return false;
    }

    public function get_content() {
        global $CFG;

        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass();
        $this->content->text = '';
        $this->content->footer = '';

        if (!isloggedin() || isguestuser()) {
            return $this->content;
        }

        require_once($CFG->dirroot . '/local/zsk_local_tiles/lib.php');
        require_once($CFG->dirroot . '/local/zsk_local_tiles/classes/output/tile_grid.php');

        if (!local_zsk_local_tiles_enabled_for('dashboard')) {
            return $this->content;
        }

        $includeunenrolled = local_zsk_local_tiles_include_unenrolled('dashboard');
        $payload = \local_zsk_local_tiles\category_tiles::build_explorable_courses_payload($includeunenrolled);

        $html = \local_zsk_local_tiles\output\tile_grid::render_items($payload['items'] ?? []);
        if ($html === '') {
            return $this->content;
        }

        $this->content->text = $html;

        global $PAGE;
        $PAGE->requires->js_call_amd('block_coursetiles/rendered', 'init');

        return $this->content;
    }
}
