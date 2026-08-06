<?php
// This file is part of Moodle - http://moodle.org/
/**
 * Text setting available only with Premium license.
 *
 * Important: writes must still succeed without Premium so admin/upgradesettings.php
 * can persist defaults. Otherwise Moodle enters ERR_TOO_MANY_REDIRECTS.
 * Premium enforcement happens at runtime via license::is_premium() / can_*().
 *
 * @package    local_zsk_local_tiles
 */

namespace local_zsk_local_tiles\admin;

defined('MOODLE_INTERNAL') || die();

/**
 * Text setting available only with Premium license.
 */
class setting_premium_configtext extends \admin_setting_configtext {

    /**
     * @param string $name
     * @param string $visiblename
     * @param string $description
     * @param string $default
     * @param mixed $paramtype
     */
    public function __construct(
        string $name,
        string $visiblename,
        string $description,
        string $default,
        $paramtype = PARAM_TEXT
    ) {
        parent::__construct('local_zsk_local_tiles/' . $name, $visiblename, $description, $default, $paramtype);
    }

    /**
     * Always persist the value (needed for upgradesettings).
     *
     * @param mixed $data
     * @return string
     */
    public function write_setting($data) {
        return parent::write_setting($data);
    }
}
