<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

namespace local_zsk_local_tiles\admin;



use local_zsk_local_tiles\util\license;



defined('MOODLE_INTERNAL') || die();



/**

 * Config text stored under local_zsk_local_tiles (per-plugin license settings).

 */

class setting_plugin_configtext extends \admin_setting_configtext {



    /** @var bool */

    private $reverifyonchange;



    /**

     * @param string $name

     * @param string $visiblename

     * @param string $description

     * @param string $default

     * @param mixed $paramtype

     * @param bool $reverifyonchange

     */

    public function __construct(

        $name,

        $visiblename,

        $description,

        $default,

        $paramtype = PARAM_TEXT,

        $reverifyonchange = false

    ) {

        $this->reverifyonchange = (bool) $reverifyonchange;

        parent::__construct('local_zsk_local_tiles/' . $name, $visiblename, $description, $default, $paramtype);

    }



    /**

     * @param mixed $data

     * @return string

     */

    public function write_setting($data) {

        $result = parent::write_setting($data);

        if ($result !== '' || !$this->reverifyonchange) {

            return $result;

        }



        if (!empty(get_config('local_zsk_local_tiles', 'license_key'))) {

            setting_license_key::notify_verify_result();

        }



        return '';

    }

}

