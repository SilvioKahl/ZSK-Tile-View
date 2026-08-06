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
 * Install hook for block_coursetiles.
 *
 * @package    block_coursetiles
 * @copyright  2025 Silvio Kuhn
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

/**
 * Add course tiles block to the dashboard centre column (content region) when enabled.
 */
function xmldb_block_coursetiles_install() {
    global $CFG;

    require_once($CFG->dirroot . '/local/zsk_local_tiles/classes/dashboard_block.php');
    \local_zsk_local_tiles\dashboard_block::ensure_default_instance();

    return true;
}
