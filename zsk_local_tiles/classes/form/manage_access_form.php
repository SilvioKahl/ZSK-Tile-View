<?php
// This file is part of Moodle - http://moodle.org/
/**
 * Form: allowlist users who may manage tile content.
 *
 * @package    local_zsk_local_tiles
 * @copyright  2026 Silvio Kuhn
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_zsk_local_tiles\form;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

/**
 * Manage tile-content allowlist.
 */
class manage_access_form extends \moodleform {

    /**
     * @return void
     */
    protected function definition() {
        $mform = $this->_form;
        $useroptions = $this->_customdata['useroptions'] ?? [];

        $mform->addElement('header', 'accessheading', get_string('manageaccess', 'local_zsk_local_tiles'));
        $mform->addElement('static', 'desc', '', get_string('manageaccess_desc', 'local_zsk_local_tiles'));

        $mform->addElement(
            'autocomplete',
            'allowedusers',
            get_string('allowedusers', 'local_zsk_local_tiles'),
            $useroptions,
            [
                'multiple' => true,
                'ajax' => 'core_user/form_user_selector',
            ]
        );
        $mform->addHelpButton('allowedusers', 'allowedusers', 'local_zsk_local_tiles');

        $this->add_action_buttons(true, get_string('savechanges'));
    }
}
