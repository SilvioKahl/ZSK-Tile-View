<?php
// This file is part of Moodle - http://moodle.org/
/**
 * File setting that never blocks admin/upgradesettings.php.
 *
 * admin_setting_configstoredfile often leaves get_setting() empty when no file
 * was uploaded, which causes Moodle to redirect-loop on upgradesettings.
 *
 * @package    local_zsk_local_tiles
 * @copyright  2026 Silvio Kuhn
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_zsk_local_tiles\admin;

defined('MOODLE_INTERNAL') || die();

/**
 * Stored-file setting with a durable empty default for upgradesettings.
 */
class setting_placeholder_file extends \admin_setting_configstoredfile {

    /**
     * @return mixed
     */
    public function get_setting() {
        $value = parent::get_setting();
        if ($value === false || $value === null || $value === '') {
            return '0';
        }
        return $value;
    }

    /**
     * @param mixed $data
     * @return string
     */
    public function write_setting($data) {
        $result = parent::write_setting($data);
        if ($result !== '') {
            // Still ensure a config key exists so upgradesettings can finish.
            if ($this->config_read($this->name) === false) {
                $this->config_write($this->name, '0');
            }
            // Do not fail the whole upgradesettings form for an optional image.
            return '';
        }
        if ($this->config_read($this->name) === false) {
            $this->config_write($this->name, '0');
        }
        return '';
    }
}
