<?php
// This file is part of Moodle - http://moodle.org/
/**
 * Checkbox available only with Premium license.
 *
 * Enabling (non-empty) without Premium is rejected. Writing the off/default
 * state is always allowed so upgradesettings can complete.
 *
 * @package    local_zsk_local_tiles
 */

namespace local_zsk_local_tiles\admin;

use local_zsk_local_tiles\util\license;

defined('MOODLE_INTERNAL') || die();

/**
 * Checkbox available only with Premium license.
 */
class setting_premium_checkbox extends \admin_setting_configcheckbox {

    /**
     * @param string $name
     * @param string $visiblename
     * @param string $description
     * @param int $default
     */
    public function __construct(string $name, string $visiblename, string $description, int $default = 0) {
        parent::__construct('local_zsk_local_tiles/' . $name, $visiblename, $description, $default);
    }

    /**
     * @param mixed $data
     * @return string
     */
    public function write_setting($data) {
        // Allow storing "off"/empty without Premium (defaults / upgradesettings).
        if (!empty($data) && !license::is_premium()) {
            // Persist off instead of failing the whole upgradesettings save.
            return parent::write_setting(0);
        }
        return parent::write_setting($data);
    }
}
