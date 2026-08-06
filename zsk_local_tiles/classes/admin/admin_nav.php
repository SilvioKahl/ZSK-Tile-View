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
 * Standalone admin navigation for ZSK Kacheldarstellung.
 */
class admin_nav {

    public const CATEGORY = 'zsklocaltiles';

    /**
     * @param \admin_root $admin
     * @return void
     */
    public static function ensure_category(\admin_root $admin): void {
        static $done = false;
        if ($done || $admin->locate(self::CATEGORY, false)) {
            $done = true;
            return;
        }

        $insertbefore = null;
        foreach (['aisettings', 'ai', 'analytics'] as $candidate) {
            if ($admin->locate($candidate, false)) {
                $insertbefore = $candidate;
                break;
            }
        }

        $category = new \admin_category(self::CATEGORY, get_string('admin_category', 'local_zsk_local_tiles'));
        if ($insertbefore !== null) {
            $admin->add('root', $category, $insertbefore);
        } else {
            $admin->add('root', $category);
        }
        $done = true;
    }

    /**
     * @param \admin_root $admin
     * @param \admin_externalpage|\admin_settingpage $page
     * @return void
     */
    public static function add_page(\admin_root $admin, $page): void {
        self::ensure_category($admin);
        $admin->add(self::CATEGORY, $page);
    }
}
