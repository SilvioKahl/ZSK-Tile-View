<?php
// This file is part of Moodle - http://moodle.org/
/**
 * Privacy provider for local_zsk_local_tiles.
 *
 * @package    local_zsk_local_tiles
 * @copyright  2026 Silvio Kuhn
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_zsk_local_tiles\privacy;

defined('MOODLE_INTERNAL') || die();

use core_privacy\local\metadata\collection;
use core_privacy\local\request\approved_contextlist;
use core_privacy\local\request\approved_userlist;
use core_privacy\local\request\contextlist;
use core_privacy\local\request\userlist;
use core_privacy\local\request\writer;

/**
 * Privacy provider for tile content allowlist and editors.
 */
class provider implements
    \core_privacy\local\metadata\provider,
    \core_privacy\local\request\plugin\provider,
    \core_privacy\local\request\core_userlist_provider {

    /**
     * @param collection $collection
     * @return collection
     */
    public static function get_metadata(collection $collection): collection {
        $collection->add_database_table('local_zsk_local_tiles_allow', [
            'userid' => 'privacy:metadata:allow:userid',
            'timecreated' => 'privacy:metadata:allow:timecreated',
            'usermodified' => 'privacy:metadata:allow:usermodified',
        ], 'privacy:metadata:allow');

        $collection->add_database_table('local_zsk_local_tiles_course', [
            'usermodified' => 'privacy:metadata:content:usermodified',
            'timemodified' => 'privacy:metadata:content:timemodified',
        ], 'privacy:metadata:coursecontent');

        $collection->add_database_table('local_zsk_local_tiles_category', [
            'usermodified' => 'privacy:metadata:content:usermodified',
            'timemodified' => 'privacy:metadata:content:timemodified',
        ], 'privacy:metadata:categorycontent');

        return $collection;
    }

    /**
     * @param int $userid
     * @return contextlist
     */
    public static function get_contexts_for_userid(int $userid): contextlist {
        $contextlist = new contextlist();
        global $DB;
        if ($DB->record_exists('local_zsk_local_tiles_allow', ['userid' => $userid])
                || $DB->record_exists('local_zsk_local_tiles_course', ['usermodified' => $userid])
                || $DB->record_exists('local_zsk_local_tiles_category', ['usermodified' => $userid])) {
            $contextlist->add_system_context();
        }
        return $contextlist;
    }

    /**
     * @param userlist $userlist
     */
    public static function get_users_in_context(userlist $userlist): void {
        $context = $userlist->get_context();
        if (!$context instanceof \context_system) {
            return;
        }
        $userlist->add_from_sql('userid', 'SELECT userid FROM {local_zsk_local_tiles_allow}', []);
        $userlist->add_from_sql('usermodified', 'SELECT usermodified FROM {local_zsk_local_tiles_course}', []);
        $userlist->add_from_sql('usermodified', 'SELECT usermodified FROM {local_zsk_local_tiles_category}', []);
    }

    /**
     * @param approved_contextlist $contextlist
     */
    public static function export_user_data(approved_contextlist $contextlist): void {
        global $DB;
        if ($contextlist->count() === 0) {
            return;
        }
        $userid = $contextlist->get_user()->id;
        foreach ($contextlist->get_contexts() as $context) {
            if ($context->contextlevel !== CONTEXT_SYSTEM) {
                continue;
            }
            if ($DB->record_exists('local_zsk_local_tiles_allow', ['userid' => $userid])) {
                writer::with_context($context)->export_data(
                    [get_string('privacy:path:allow', 'local_zsk_local_tiles')],
                    (object) ['userid' => $userid]
                );
            }
        }
    }

    /**
     * @param approved_contextlist $contextlist
     */
    public static function delete_data_for_all_users_in_context(\context $context): void {
        global $DB;
        if ($context->contextlevel !== CONTEXT_SYSTEM) {
            return;
        }
        $DB->delete_records('local_zsk_local_tiles_allow');
    }

    /**
     * @param approved_contextlist $contextlist
     */
    public static function delete_data_for_user(approved_contextlist $contextlist): void {
        global $DB;
        $userid = $contextlist->get_user()->id;
        foreach ($contextlist->get_contexts() as $context) {
            if ($context->contextlevel === CONTEXT_SYSTEM) {
                $DB->delete_records('local_zsk_local_tiles_allow', ['userid' => $userid]);
            }
        }
    }

    /**
     * @param approved_userlist $userlist
     */
    public static function delete_data_for_users(approved_userlist $userlist): void {
        global $DB;
        $context = $userlist->get_context();
        if ($context->contextlevel !== CONTEXT_SYSTEM) {
            return;
        }
        $userids = $userlist->get_userids();
        if (empty($userids)) {
            return;
        }
        list($insql, $params) = $DB->get_in_or_equal($userids, SQL_PARAMS_NAMED);
        $DB->delete_records_select('local_zsk_local_tiles_allow', "userid $insql", $params);
    }
}
