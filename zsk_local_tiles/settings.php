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

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/lib.php');

use local_zsk_local_tiles\admin\admin_nav;
use local_zsk_local_tiles\admin\setting_license_key;
use local_zsk_local_tiles\util\license;

if ($hassiteconfig) {
    admin_nav::ensure_category($ADMIN);

    license::refresh_status_if_key_present();

    $licensepage = new admin_settingpage(
        'local_zsk_local_tiles_license',
        get_string('license_settings_title', 'local_zsk_local_tiles')
    );

    $licensepage->add(new admin_setting_heading(
        'local_zsk_local_tiles/license_intro',
        get_string('license_settings_title', 'local_zsk_local_tiles'),
        get_string('license_heading_desc', 'local_zsk_local_tiles')
    ));

    $licensepage->add(new admin_setting_configtext(
        'local_zsk_local_tiles/license_server_url',
        get_string('license_server_url', 'local_zsk_local_tiles'),
        get_string('license_server_url_desc', 'local_zsk_local_tiles'),
        '',
        PARAM_RAW
    ));

    $licensepage->add(new admin_setting_configtext(
        'local_zsk_local_tiles/license_grace_days',
        get_string('license_grace_days', 'local_zsk_local_tiles'),
        get_string('license_grace_days_desc', 'local_zsk_local_tiles'),
        '7',
        PARAM_INT
    ));

    $licensepage->add(new setting_license_key());

    $licensepage->add(new admin_setting_description(
        'local_zsk_local_tiles/license_status',
        get_string('license_status', 'local_zsk_local_tiles'),
        license::get_status_string()
    ));

    admin_nav::add_page($ADMIN, $licensepage);

    $tilesettings = new admin_settingpage(
        'local_zsk_local_tiles_config',
        get_string('tilesettings_heading', 'local_zsk_local_tiles')
    );

    $tilesettings->add(new admin_setting_heading(
        'local_zsk_local_tiles/tilesettings_intro',
        get_string('tilesettings_heading', 'local_zsk_local_tiles'),
        get_string('tilesettings_intro', 'local_zsk_local_tiles')
    ));

    $tilesettings->add(new admin_setting_heading(
        'local_zsk_local_tiles/tilesettings_content',
        get_string('tilesettings_content_heading', 'local_zsk_local_tiles'),
        get_string('tilesettings_content_desc', 'local_zsk_local_tiles')
    ));

    $tilesettings->add(new admin_setting_configselect(
        'local_zsk_local_tiles/tiles_content_source',
        get_string('tiles_content_source', 'local_zsk_local_tiles'),
        get_string('tiles_content_source_desc', 'local_zsk_local_tiles'),
        'course',
        [
            'course' => get_string('tiles_content_source_course', 'local_zsk_local_tiles'),
            'custom' => get_string('tiles_content_source_custom', 'local_zsk_local_tiles'),
        ]
    ));

    $tilesettings->add(new admin_setting_heading(
        'local_zsk_local_tiles/tilesettings_free',
        get_string('tilesettings_free_heading', 'local_zsk_local_tiles'),
        get_string('tilesettings_free_desc', 'local_zsk_local_tiles')
    ));

    $tilesettings->add(new admin_setting_configcheckbox(
        'local_zsk_local_tiles/tiles_dashboard',
        get_string('tiles_dashboard', 'local_zsk_local_tiles'),
        get_string('tiles_dashboard_desc', 'local_zsk_local_tiles'),
        1
    ));

    $tilesettings->add(new admin_setting_configcheckbox(
        'local_zsk_local_tiles/tiles_mycourses',
        get_string('tiles_mycourses', 'local_zsk_local_tiles'),
        get_string('tiles_mycourses_desc', 'local_zsk_local_tiles'),
        1
    ));

    $tilesettings->add(new admin_setting_heading(
        'local_zsk_local_tiles/tilesettings_premium',
        get_string('tilesettings_premium_heading', 'local_zsk_local_tiles'),
        get_string('tilesettings_premium_desc', 'local_zsk_local_tiles')
    ));

    $tilesettings->add(new \local_zsk_local_tiles\admin\setting_premium_checkbox(
        'tiles_frontpage',
        get_string('tiles_frontpage', 'local_zsk_local_tiles'),
        get_string('tiles_frontpage_desc', 'local_zsk_local_tiles'),
        0
    ));

    $tilesettings->add(new \local_zsk_local_tiles\admin\setting_premium_checkbox(
        'tiles_category',
        get_string('tiles_category', 'local_zsk_local_tiles'),
        get_string('tiles_category_desc', 'local_zsk_local_tiles'),
        0
    ));

    $categorydepthoptions = [
        '0' => get_string('tiles_category_maxdepth_unlimited', 'local_zsk_local_tiles'),
    ];
    for ($level = 1; $level <= 10; $level++) {
        $categorydepthoptions[(string) $level] = get_string('tiles_category_maxdepth_level', 'local_zsk_local_tiles', $level);
    }

    $tilesettings->add(new admin_setting_configselect(
        'local_zsk_local_tiles/tiles_category_maxdepth',
        get_string('tiles_category_maxdepth', 'local_zsk_local_tiles'),
        get_string('tiles_category_maxdepth_desc', 'local_zsk_local_tiles') . ' ' .
            get_string('license_premium_only_hint', 'local_zsk_local_tiles'),
        '0',
        $categorydepthoptions
    ));

    $tilesettings->add(new \local_zsk_local_tiles\admin\setting_premium_checkbox(
        'tiles_showunenrolled',
        get_string('tiles_showunenrolled', 'local_zsk_local_tiles'),
        get_string('tiles_showunenrolled_desc', 'local_zsk_local_tiles'),
        0
    ));

    $tilesettings->add(new admin_setting_configstoredfile(
        'local_zsk_local_tiles/tiles_placeholderimage',
        get_string('tiles_placeholderimage', 'local_zsk_local_tiles'),
        get_string('tiles_placeholderimage_desc', 'local_zsk_local_tiles') . ' ' .
            get_string('license_premium_only_hint', 'local_zsk_local_tiles'),
        'tileplaceholder',
        0,
        ['maxfiles' => 1, 'accepted_types' => ['image']]
    ));

    $tilesettings->add(new admin_setting_heading(
        'local_zsk_local_tiles/tilesettings_layout',
        get_string('tilesettings_layout_heading', 'local_zsk_local_tiles'),
        get_string('tilesettings_layout_desc', 'local_zsk_local_tiles')
    ));

    $columnoptions = ['1' => '1', '2' => '2', '3' => '3'];
    $tilesettings->add(new admin_setting_configselect(
        'local_zsk_local_tiles/tiles_grid_columns',
        get_string('tiles_grid_columns', 'local_zsk_local_tiles'),
        get_string('tiles_grid_columns_desc', 'local_zsk_local_tiles') . ' ' .
            get_string('license_premium_only_hint', 'local_zsk_local_tiles'),
        '2',
        $columnoptions
    ));

    $tilesettings->add(new \local_zsk_local_tiles\admin\setting_premium_configtext(
        'tiles_image_height',
        get_string('tiles_image_height', 'local_zsk_local_tiles'),
        get_string('tiles_image_height_desc', 'local_zsk_local_tiles'),
        '175',
        PARAM_INT
    ));

    $tilesettings->add(new \local_zsk_local_tiles\admin\setting_premium_configtext(
        'tiles_desc_lines',
        get_string('tiles_desc_lines', 'local_zsk_local_tiles'),
        get_string('tiles_desc_lines_desc', 'local_zsk_local_tiles'),
        '7',
        PARAM_INT
    ));

    $colorstates = [
        'complete' => get_string('footer_color_complete', 'local_zsk_local_tiles'),
        'progress' => get_string('footer_color_progress', 'local_zsk_local_tiles'),
        'notstarted' => get_string('footer_color_notstarted', 'local_zsk_local_tiles'),
        'disabled' => get_string('footer_color_disabled', 'local_zsk_local_tiles'),
        'notenrolled' => get_string('footer_color_notenrolled', 'local_zsk_local_tiles'),
        'categorycount' => get_string('footer_color_categorycount', 'local_zsk_local_tiles'),
    ];

    $tilesettings->add(new admin_setting_heading(
        'local_zsk_local_tiles/tilesettings_colors',
        get_string('tilesettings_colors_heading', 'local_zsk_local_tiles'),
        get_string('tilesettings_colors_desc', 'local_zsk_local_tiles')
    ));

    foreach ($colorstates as $key => $label) {
        $tilesettings->add(new \local_zsk_local_tiles\admin\setting_premium_configtext(
            'footer_color_' . $key . '_bg',
            $label . ' – ' . get_string('footer_color_bg', 'local_zsk_local_tiles'),
            get_string('footer_color_bg_desc', 'local_zsk_local_tiles'),
            '',
            PARAM_TEXT
        ));
        $tilesettings->add(new \local_zsk_local_tiles\admin\setting_premium_configtext(
            'footer_color_' . $key . '_fg',
            $label . ' – ' . get_string('footer_color_fg', 'local_zsk_local_tiles'),
            get_string('footer_color_fg_desc', 'local_zsk_local_tiles'),
            '',
            PARAM_TEXT
        ));
    }

    admin_nav::add_page($ADMIN, $tilesettings);

    admin_nav::add_page($ADMIN, new admin_externalpage(
        'local_zsk_local_tiles_manageaccess',
        get_string('manageaccess', 'local_zsk_local_tiles'),
        new moodle_url('/local/zsk_local_tiles/manageaccess.php'),
        'moodle/site:config'
    ));

    if (!$ADMIN->locate('local_zsk_local_tiles', false)) {
        $legacy = new admin_settingpage(
            'local_zsk_local_tiles',
            get_string('pluginname', 'local_zsk_local_tiles')
        );
        $legacy->add(new admin_setting_description(
            'local_zsk_local_tiles/legacy_redirect',
            get_string('settings_moved_title', 'local_zsk_local_tiles'),
            get_string('settings_moved_tiles', 'local_zsk_local_tiles', (object) [
                'license' => html_writer::link(
                    new moodle_url('/admin/settings.php', ['section' => 'local_zsk_local_tiles_license']),
                    get_string('license_settings_title', 'local_zsk_local_tiles')
                ),
                'design' => html_writer::link(
                    new moodle_url('/admin/settings.php', ['section' => 'local_zsk_local_tiles_config']),
                    get_string('tilesettings_heading', 'local_zsk_local_tiles')
                ),
            ])
        ));
        admin_nav::add_page($ADMIN, $legacy);
    }

    require_once(__DIR__ . '/classes/admin/frontpage_settings.php');
    \local_zsk_local_tiles\admin\frontpage_settings::patch_admin_tree();
}
