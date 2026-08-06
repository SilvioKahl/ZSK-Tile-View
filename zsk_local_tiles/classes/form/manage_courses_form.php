<?php
// This file is part of Moodle - http://moodle.org/
/**
 * Bulk form for course tile overrides in one category.
 *
 * @package    local_zsk_local_tiles
 * @copyright  2026 Silvio Kuhn
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_zsk_local_tiles\form;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');
require_once($CFG->libdir . '/filelib.php');

use local_zsk_local_tiles\local\content_store;

/**
 * Table-like course tile content editor.
 */
class manage_courses_form extends \moodleform {

    /**
     * @return void
     */
    protected function definition() {
        $mform = $this->_form;
        $courses = $this->_customdata['courses'] ?? [];
        $options = content_store::file_options();

        $mform->addElement('hidden', 'categoryid', (int) ($this->_customdata['categoryid'] ?? 0));
        $mform->setType('categoryid', PARAM_INT);
        $mform->addElement('hidden', 'page', (int) ($this->_customdata['page'] ?? 0));
        $mform->setType('page', PARAM_INT);

        $mform->addElement('static', 'mlanghint', '', get_string('content_multilang_hint', 'local_zsk_local_tiles'));

        foreach ($courses as $course) {
            $cid = (int) $course->id;
            $mform->addElement('header', 'coursehdr' . $cid, format_string($course->fullname));

            $mform->addElement(
                'textarea',
                'summary_' . $cid,
                get_string('content_summary', 'local_zsk_local_tiles'),
                ['rows' => 4, 'cols' => 60, 'maxlength' => 2000]
            );
            $mform->setType('summary_' . $cid, PARAM_RAW);

            $mform->addElement(
                'filemanager',
                'image_' . $cid,
                get_string('content_image', 'local_zsk_local_tiles'),
                null,
                $options
            );
        }

        $this->add_action_buttons(true, get_string('savechanges'));
    }
}
