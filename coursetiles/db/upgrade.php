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
 * Upgrade steps for block_coursetiles.
 *
 * @package    block_coursetiles
 * @copyright  2025 Silvio Kuhn
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

/**
 * @param int $oldversion
 * @return bool
 */
function xmldb_block_coursetiles_upgrade($oldversion) {
    global $CFG;

    if ($oldversion < 2025060302) {
        if (file_exists($CFG->dirroot . '/local/tiles2/classes/dashboard_block.php')) {
            require_once($CFG->dirroot . '/local/tiles2/classes/dashboard_block.php');
            \local_tiles2\dashboard_block::ensure_default_instance();
        } else {
            require_once($CFG->dirroot . '/local/zsk_local_tiles/classes/dashboard_block.php');
            \local_zsk_local_tiles\dashboard_block::ensure_default_instance();
        }
        upgrade_plugin_savepoint(true, 2025060302, 'block', 'coursetiles');
    }

    if ($oldversion < 2025061307) {
        require_once($CFG->dirroot . '/local/zsk_local_tiles/classes/dashboard_block.php');
        \local_zsk_local_tiles\dashboard_block::ensure_default_instance();
        upgrade_plugin_savepoint(true, 2025061307, 'block', 'coursetiles');
    }

    if ($oldversion < 2025061308) {
        upgrade_plugin_savepoint(true, 2025061308, 'block', 'coursetiles');
    }

    if ($oldversion < 2025070900) {
        upgrade_plugin_savepoint(true, 2025070900, 'block', 'coursetiles');
    }

    if ($oldversion < 2025073100) {
        upgrade_plugin_savepoint(true, 2025073100, 'block', 'coursetiles');
    }

    return true;
}
