<?php
// This file is part of Moodle - http://moodle.org/
/**
 * Maintain separate course tile images/texts (by category).
 *
 * @package    local_zsk_local_tiles
 * @copyright  2026 Silvio Kuhn
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/lib.php');

use local_zsk_local_tiles\local\content_store;

require_login();
local_zsk_local_tiles_require_manage_content();

$categoryid = optional_param('categoryid', 0, PARAM_INT);
$page = optional_param('page', 0, PARAM_INT);
$perpage = 20;

$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/zsk_local_tiles/manage_courses.php', [
    'categoryid' => $categoryid,
    'page' => $page,
]));
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('manage_courses', 'local_zsk_local_tiles'));
$PAGE->set_heading(get_string('manage_courses', 'local_zsk_local_tiles'));

$categories = core_course_category::make_categories_list();

echo $OUTPUT->header();
echo html_writer::div(
    html_writer::link(
        new moodle_url('/local/zsk_local_tiles/manage_content.php'),
        get_string('backtohub', 'local_zsk_local_tiles'),
        ['class' => 'btn btn-secondary btn-sm mb-3']
    )
);

if (!content_store::uses_custom_content()) {
    echo $OUTPUT->notification(get_string('manage_content_source_course_notice', 'local_zsk_local_tiles'), 'warning');
}

echo html_writer::tag('p', get_string('manage_courses_intro', 'local_zsk_local_tiles'));

// Category picker.
echo html_writer::start_tag('form', [
    'method' => 'get',
    'action' => (new moodle_url('/local/zsk_local_tiles/manage_courses.php'))->out(false),
    'class' => 'mb-3',
]);
echo html_writer::label(get_string('category'), 'categoryid');
echo ' ';
echo html_writer::select($categories, 'categoryid', $categoryid, [0 => get_string('choose')], ['id' => 'categoryid']);
echo ' ';
echo html_writer::empty_tag('input', ['type' => 'submit', 'value' => get_string('show'), 'class' => 'btn btn-secondary btn-sm']);
echo html_writer::end_tag('form');

if ($categoryid <= 0) {
    echo $OUTPUT->notification(get_string('manage_courses_choose_category', 'local_zsk_local_tiles'), 'info');
    echo $OUTPUT->footer();
    exit;
}

global $DB;
$total = $DB->count_records_select('course', 'category = :cat AND id > 1', ['cat' => $categoryid]);
$courses = $DB->get_records_select(
    'course',
    'category = :cat AND id > 1',
    ['cat' => $categoryid],
    'sortorder ASC, fullname ASC',
    'id, fullname, shortname, category',
    $page * $perpage,
    $perpage
);

if (empty($courses)) {
    echo $OUTPUT->notification(get_string('manage_courses_empty', 'local_zsk_local_tiles'), 'info');
    echo $OUTPUT->footer();
    exit;
}

$defaults = [];
foreach ($courses as $course) {
    $cid = (int) $course->id;
    $rec = content_store::get_course_record($cid);
    $defaults['summary_' . $cid] = $rec ? (string) $rec->summarytext : '';
    $defaults['image_' . $cid] = content_store::prepare_course_draft($cid);
}

$form = new \local_zsk_local_tiles\form\manage_courses_form(null, [
    'courses' => $courses,
    'categoryid' => $categoryid,
    'page' => $page,
]);
$form->set_data($defaults);

if ($form->is_cancelled()) {
    redirect(new moodle_url('/local/zsk_local_tiles/manage_content.php'));
}

if ($data = $form->get_data()) {
    foreach ($courses as $course) {
        $cid = (int) $course->id;
        $summarykey = 'summary_' . $cid;
        $imagekey = 'image_' . $cid;
        $summary = isset($data->$summarykey) ? (string) $data->$summarykey : '';
        $draft = isset($data->$imagekey) ? (int) $data->$imagekey : 0;
        content_store::save_course($cid, $summary, $draft);
    }
    redirect(
        new moodle_url('/local/zsk_local_tiles/manage_courses.php', [
            'categoryid' => $categoryid,
            'page' => $page,
        ]),
        get_string('manage_content_saved', 'local_zsk_local_tiles'),
        null,
        \core\output\notification::NOTIFY_SUCCESS
    );
}

echo $OUTPUT->paging_bar($total, $page, $perpage, $PAGE->url);
$form->display();
echo $OUTPUT->paging_bar($total, $page, $perpage, $PAGE->url);
echo $OUTPUT->footer();
