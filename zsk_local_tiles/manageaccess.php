<?php
// This file is part of Moodle - http://moodle.org/
/**
 * Allowlist: who may manage separate tile content.
 *
 * @package    local_zsk_local_tiles
 * @copyright  2026 Silvio Kuhn
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/lib.php');

require_login();
require_capability('moodle/site:config', context_system::instance());

$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/zsk_local_tiles/manageaccess.php'));
$PAGE->set_pagelayout('admin');
$PAGE->set_title(get_string('manageaccess', 'local_zsk_local_tiles'));
$PAGE->set_heading(get_string('manageaccess', 'local_zsk_local_tiles'));

$useroptions = local_zsk_local_tiles_get_allowed_user_options();
$form = new \local_zsk_local_tiles\form\manage_access_form(null, ['useroptions' => $useroptions]);
$form->set_data((object) ['allowedusers' => array_keys($useroptions)]);

if ($form->is_cancelled()) {
    redirect(new moodle_url('/admin/settings.php', ['section' => 'local_zsk_local_tiles_config']));
}

if ($data = $form->get_data()) {
    $userids = $data->allowedusers ?? [];
    if (!is_array($userids)) {
        $userids = [$userids];
    }
    local_zsk_local_tiles_set_allowed_userids($userids);
    redirect(
        new moodle_url('/local/zsk_local_tiles/manageaccess.php'),
        get_string('manageaccess_saved', 'local_zsk_local_tiles'),
        null,
        \core\output\notification::NOTIFY_SUCCESS
    );
}

echo $OUTPUT->header();
echo html_writer::tag('p', get_string('manageaccess_desc', 'local_zsk_local_tiles'), ['class' => 'lead']);
$form->display();
echo $OUTPUT->footer();
