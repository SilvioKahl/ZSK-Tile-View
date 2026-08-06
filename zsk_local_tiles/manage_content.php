<?php
// This file is part of Moodle - http://moodle.org/
/**
 * Hub for tile content maintenance (allowlist users only).
 *
 * @package    local_zsk_local_tiles
 * @copyright  2026 Silvio Kuhn
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/lib.php');

require_login();
local_zsk_local_tiles_require_manage_content();

$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/zsk_local_tiles/manage_content.php'));
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('nav_manage_tiles', 'local_zsk_local_tiles'));
$PAGE->set_heading(get_string('nav_manage_tiles', 'local_zsk_local_tiles'));

echo $OUTPUT->header();
echo html_writer::tag('p', get_string('manage_content_intro', 'local_zsk_local_tiles'), ['class' => 'lead']);

if (!\local_zsk_local_tiles\local\content_store::uses_custom_content()) {
    echo $OUTPUT->notification(get_string('manage_content_source_course_notice', 'local_zsk_local_tiles'), 'warning');
}

echo html_writer::start_div('local-zsk-tiles-manage-hub');
echo html_writer::link(
    new moodle_url('/local/zsk_local_tiles/manage_courses.php'),
    get_string('manage_courses', 'local_zsk_local_tiles'),
    ['class' => 'btn btn-primary mr-2 mb-2']
);
echo html_writer::link(
    new moodle_url('/local/zsk_local_tiles/manage_categories.php'),
    get_string('manage_categories', 'local_zsk_local_tiles'),
    ['class' => 'btn btn-primary mb-2']
);
echo html_writer::end_div();

echo $OUTPUT->footer();
