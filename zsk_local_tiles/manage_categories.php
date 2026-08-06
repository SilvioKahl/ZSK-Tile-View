<?php
// This file is part of Moodle - http://moodle.org/
/**
 * Maintain separate category tile images/texts.
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

$parent = optional_param('parent', 0, PARAM_INT);
$page = optional_param('page', 0, PARAM_INT);
$perpage = 20;

$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/zsk_local_tiles/manage_categories.php', [
    'parent' => $parent,
    'page' => $page,
]));
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('manage_categories', 'local_zsk_local_tiles'));
$PAGE->set_heading(get_string('manage_categories', 'local_zsk_local_tiles'));

$parents = core_course_category::make_categories_list();

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

echo html_writer::tag('p', get_string('manage_categories_intro', 'local_zsk_local_tiles'));

echo html_writer::start_tag('form', [
    'method' => 'get',
    'action' => (new moodle_url('/local/zsk_local_tiles/manage_categories.php'))->out(false),
    'class' => 'mb-3',
]);
echo html_writer::label(get_string('manage_categories_parent', 'local_zsk_local_tiles'), 'parent');
echo ' ';
echo html_writer::select(
    $parents,
    'parent',
    $parent,
    [0 => get_string('manage_categories_top', 'local_zsk_local_tiles')],
    ['id' => 'parent']
);
echo ' ';
echo html_writer::empty_tag('input', ['type' => 'submit', 'value' => get_string('show'), 'class' => 'btn btn-secondary btn-sm']);
echo html_writer::end_tag('form');

global $DB;
$total = $DB->count_records('course_categories', ['parent' => $parent]);
$categories = $DB->get_records(
    'course_categories',
    ['parent' => $parent],
    'sortorder ASC, name ASC',
    '*',
    $page * $perpage,
    $perpage
);

if (empty($categories)) {
    echo $OUTPUT->notification(get_string('manage_categories_empty', 'local_zsk_local_tiles'), 'info');
    echo $OUTPUT->footer();
    exit;
}

$defaults = [];
foreach ($categories as $category) {
    $cid = (int) $category->id;
    $rec = content_store::get_category_record($cid);
    $defaults['summary_' . $cid] = $rec ? (string) $rec->summarytext : '';
    $defaults['image_' . $cid] = content_store::prepare_category_draft($cid);
}

$form = new \local_zsk_local_tiles\form\manage_categories_form(null, [
    'categories' => $categories,
    'parent' => $parent,
    'page' => $page,
]);
$form->set_data($defaults);

if ($form->is_cancelled()) {
    redirect(new moodle_url('/local/zsk_local_tiles/manage_content.php'));
}

if ($data = $form->get_data()) {
    foreach ($categories as $category) {
        $cid = (int) $category->id;
        $summarykey = 'summary_' . $cid;
        $imagekey = 'image_' . $cid;
        $summary = isset($data->$summarykey) ? (string) $data->$summarykey : '';
        $draft = isset($data->$imagekey) ? (int) $data->$imagekey : 0;
        content_store::save_category($cid, $summary, $draft);
    }
    redirect(
        new moodle_url('/local/zsk_local_tiles/manage_categories.php', [
            'parent' => $parent,
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
