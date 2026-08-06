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



defined('MOODLE_INTERNAL') || die();



/**

 * Replace core frontpage list settings with versions that include course tiles.

 */

class frontpage_settings {



    /**

     * @return void

     */

    public static function patch_admin_tree(): void {

        global $ADMIN;



        if (during_initial_install() || !isset($ADMIN)) {

            return;

        }

        if (isset($_SERVER['SCRIPT_NAME']) && str_ends_with($_SERVER['SCRIPT_NAME'], 'upgradesettings.php')) {

            return;

        }



        self::replace_setting_on_page($ADMIN, 'frontpagesettings', 'frontpageloggedin', true);

        self::replace_setting_on_page($ADMIN, 'frontpagesettings', 'frontpage', false);

    }



    /**

     * @param \admin_root $admin

     * @param string $pageid

     * @param string $settingname

     * @param bool $loggedin

     */

    protected static function replace_setting_on_page(

        \admin_root $admin,

        string $pageid,

        string $settingname,

        bool $loggedin

    ): void {

        $page = $admin->locate($pageid, false);

        if (!$page || !($page instanceof \admin_settingpage)) {

            return;

        }



        if (empty($page->settings)) {

            return;

        }



        foreach ($page->settings as $key => $setting) {

            if (!($setting instanceof \admin_setting_courselist_frontpage)) {

                continue;

            }

            if ($setting instanceof frontpage_courselist) {

                return;

            }

            if ($setting instanceof \local_zsk_frontpage_elements\admin\frontpage_courselist) {

                return;

            }

            if ($setting->name !== $settingname) {

                continue;

            }



            // Moodle 4.x stores settings as stdClass properties, not array keys.

            if (is_object($page->settings)) {

                $page->settings->{$key} = new frontpage_courselist($loggedin);

            } else {

                $page->settings[$key] = new frontpage_courselist($loggedin);

            }

            return;

        }

    }

}

