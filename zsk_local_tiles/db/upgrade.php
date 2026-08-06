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

defined('MOODLE_INTERNAL') || die();

/**
 * Upgrade steps for local_zsk_local_tiles.
 *
 * @package    local_zsk_local_tiles
 * @copyright  2025 Silvio Kuhn
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Copy config and placeholder files from legacy local_tiles2 if present.
 *
 * @return void
 */
function local_zsk_local_tiles_migrate_legacy_plugin_data(): void {
    global $CFG;

    $configkeys = [
        'tiles_showunenrolled',
        'tiles_category',
        'tiles_category_maxdepth',
        'tiles_dashboard',
        'tiles_frontpage',
        'tiles_mycourses',
    ];

    foreach ($configkeys as $key) {
        if (get_config('local_zsk_local_tiles', $key) !== false) {
            continue;
        }
        foreach (['local_tiles2', 'local_tiles', 'local_statistics'] as $legacyplugin) {
            $value = get_config($legacyplugin, $key);
            if ($value !== false && $value !== null) {
                set_config($key, $value, 'local_zsk_local_tiles');
                break;
            }
        }
    }

    $context = context_system::instance();
    $fs = get_file_storage();
    foreach (['local_tiles2', 'local_tiles', 'local_statistics'] as $component) {
        $files = $fs->get_area_files(
            $context->id,
            $component,
            'tileplaceholder',
            0,
            'itemid, filepath, filename',
            false
        );
        foreach ($files as $file) {
            if ($file->is_directory()) {
                continue;
            }
            if ($fs->file_exists(
                $context->id,
                'local_zsk_local_tiles',
                'tileplaceholder',
                0,
                $file->get_filepath(),
                $file->get_filename()
            )) {
                continue;
            }
            $filerecord = [
                'contextid' => $context->id,
                'component' => 'local_zsk_local_tiles',
                'filearea' => 'tileplaceholder',
                'itemid' => 0,
                'filepath' => $file->get_filepath(),
                'filename' => $file->get_filename(),
            ];
            $fs->create_file_from_storedfile($filerecord, $file);
        }
    }

    require_once($CFG->dirroot . '/local/zsk_local_tiles/classes/admin/frontpage_settings.php');
    \local_zsk_local_tiles\admin\frontpage_settings::patch_admin_tree();
    require_once($CFG->dirroot . '/local/zsk_local_tiles/classes/dashboard_block.php');
    \local_zsk_local_tiles\dashboard_block::ensure_default_instance();
}

/**
 * @param int $oldversion
 * @return bool
 */
function xmldb_local_zsk_local_tiles_upgrade($oldversion) {
    global $DB;
    if ($oldversion < 2025061300) {
        local_zsk_local_tiles_migrate_legacy_plugin_data();
        if (get_config('local_zsk_local_tiles', 'tiles_dashboard') === false) {
            set_config('tiles_dashboard', 1, 'local_zsk_local_tiles');
        }
        if (get_config('local_zsk_local_tiles', 'tiles_mycourses') === false) {
            set_config('tiles_mycourses', 1, 'local_zsk_local_tiles');
        }
        upgrade_plugin_savepoint(true, 2025061300, 'local', 'zsk_local_tiles');
    }

    if ($oldversion < 2025061301) {
        $pluginurl = get_config('local_zsk_local_tiles', 'license_server_url');
        if ($pluginurl === false || $pluginurl === '') {
            $sharedurl = get_config('local_zsk_plugins', 'license_server_url');
            if (!empty($sharedurl)) {
                set_config('license_server_url', $sharedurl, 'local_zsk_local_tiles');
            }
        }
        upgrade_plugin_savepoint(true, 2025061301, 'local', 'zsk_local_tiles');
    }

    if ($oldversion < 2025061306) {
        upgrade_plugin_savepoint(true, 2025061306, 'local', 'zsk_local_tiles');
    }

    if ($oldversion < 2025061308) {
        foreach (['license_server_url', 'license_grace_days'] as $configkey) {
            $own = get_config('local_zsk_local_tiles', $configkey);
            if ($own === false || $own === '') {
                $shared = get_config('local_zsk_plugins', $configkey);
                if ($shared !== false && $shared !== '') {
                    set_config($configkey, $shared, 'local_zsk_local_tiles');
                }
            }
        }
        upgrade_plugin_savepoint(true, 2025061308, 'local', 'zsk_local_tiles');
    }

    if ($oldversion < 2025061309) {
        if (get_config('local_zsk_local_tiles', 'license_key') === false) {
            set_config('license_key', '', 'local_zsk_local_tiles');
        }
        upgrade_plugin_savepoint(true, 2025061309, 'local', 'zsk_local_tiles');
    }

    if ($oldversion < 2025061310) {
        upgrade_plugin_savepoint(true, 2025061310, 'local', 'zsk_local_tiles');
    }

    if ($oldversion < 2025061311) {
        upgrade_plugin_savepoint(true, 2025061311, 'local', 'zsk_local_tiles');
    }

    if ($oldversion < 2025070904) {
        upgrade_plugin_savepoint(true, 2025070904, 'local', 'zsk_local_tiles');
    }

    if ($oldversion < 2025080400) {
        $dbman = $DB->get_manager();

        $table = new xmldb_table('local_zsk_tiles_allow');
        if (!$dbman->table_exists($table)) {
            $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
            $table->add_field('userid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
            $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
            $table->add_field('usermodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
            $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
            $table->add_key('userid', XMLDB_KEY_UNIQUE, ['userid']);
            $table->add_key('userid_fk', XMLDB_KEY_FOREIGN, ['userid'], 'user', ['id']);
            $dbman->create_table($table);
        }

        $table = new xmldb_table('local_zsk_tiles_course');
        if (!$dbman->table_exists($table)) {
            $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
            $table->add_field('courseid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
            $table->add_field('summarytext', XMLDB_TYPE_TEXT, null, null, null, null, null);
            $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
            $table->add_field('usermodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
            $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
            $table->add_key('courseid', XMLDB_KEY_UNIQUE, ['courseid']);
            $table->add_key('courseid_fk', XMLDB_KEY_FOREIGN, ['courseid'], 'course', ['id']);
            $dbman->create_table($table);
        }

        $table = new xmldb_table('local_zsk_tiles_category');
        if (!$dbman->table_exists($table)) {
            $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
            $table->add_field('categoryid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
            $table->add_field('summarytext', XMLDB_TYPE_TEXT, null, null, null, null, null);
            $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
            $table->add_field('usermodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
            $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
            $table->add_key('categoryid', XMLDB_KEY_UNIQUE, ['categoryid']);
            $table->add_key('categoryid_fk', XMLDB_KEY_FOREIGN, ['categoryid'], 'course_categories', ['id']);
            $dbman->create_table($table);
        }

        if (get_config('local_zsk_local_tiles', 'tiles_content_source') === false) {
            set_config('tiles_content_source', 'course', 'local_zsk_local_tiles');
        }

        require_once(__DIR__ . '/../lib.php');
        local_zsk_local_tiles_seed_config_defaults();

        upgrade_plugin_savepoint(true, 2025080400, 'local', 'zsk_local_tiles');
    }

    if ($oldversion < 2025080401) {
        // Re-seed all defaults to break admin/upgradesettings redirect loops after new settings.
        require_once(__DIR__ . '/../lib.php');
        local_zsk_local_tiles_seed_config_defaults();
        upgrade_plugin_savepoint(true, 2025080401, 'local', 'zsk_local_tiles');
    }

    if ($oldversion < 2025080402) {
        // Fix: premium text settings must allow writes without license (upgradesettings loop).
        require_once(__DIR__ . '/../lib.php');
        local_zsk_local_tiles_seed_config_defaults();
        upgrade_plugin_savepoint(true, 2025080402, 'local', 'zsk_local_tiles');
    }

    if ($oldversion < 2025080403) {
        require_once(__DIR__ . '/../lib.php');
        local_zsk_local_tiles_seed_config_defaults();
        upgrade_plugin_savepoint(true, 2025080403, 'local', 'zsk_local_tiles');
    }

    if ($oldversion < 2025080404) {
        upgrade_plugin_savepoint(true, 2025080404, 'local', 'zsk_local_tiles');
    }

    if ($oldversion < 2025080405) {
        // Documentation update: two tile content sources (course vs separate upload).
        upgrade_plugin_savepoint(true, 2025080405, 'local', 'zsk_local_tiles');
    }

    if ($oldversion < 2025080406) {
        // Fix: lang cli_license_help must be a single PHP statement (phplint).
        upgrade_plugin_savepoint(true, 2025080406, 'local', 'zsk_local_tiles');
    }

    if ($oldversion < 2025080407) {
        // Frankenstyle: rename tables to local_zsk_local_tiles_* prefix (Moodle.org validate).
        $dbman = $DB->get_manager();
        $renames = [
            'local_zsk_tiles_allow' => 'local_zsk_local_tiles_allow',
            'local_zsk_tiles_course' => 'local_zsk_local_tiles_course',
            'local_zsk_tiles_category' => 'local_zsk_local_tiles_category',
        ];
        foreach ($renames as $oldname => $newname) {
            $oldtable = new xmldb_table($oldname);
            $newtable = new xmldb_table($newname);
            if ($dbman->table_exists($oldtable) && !$dbman->table_exists($newtable)) {
                $dbman->rename_table($oldtable, $newname);
            }
        }
        upgrade_plugin_savepoint(true, 2025080407, 'local', 'zsk_local_tiles');
    }

    return true;
}
