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

$string['pluginname'] = 'ZSK Course tiles';

$string['tile_coursecount'] = '{$a} course|{$a} courses';
$string['tile_completion_percent'] = 'Course {$a}% complete.';
$string['tile_completion_disabled'] = 'No completion tracking for this course';
$string['tile_not_enrolled'] = 'not enrolled yet';
$string['tilesettings_heading'] = 'Tile view settings';
$string['tilesettings_intro'] = 'Category pages and My courses: automatic tiles. Site home: item in front page settings. Dashboard: block in the centre (content region).';
$string['tilesettings_block_heading'] = 'Site home & Dashboard';
$string['tilesettings_block_desc'] = 'Site home: choose “Course tiles” under Site administration → Front page → “Front page items when logged in”. Dashboard: “Course tiles” block in the centre (created automatically when enabled, if missing).';
$string['frontpagecoursetiles'] = 'Course tiles';
$string['frontpagecoursetiles_heading'] = 'Course list';
$string['tilesettings_injection_heading'] = 'Categories & My courses (automatic)';
$string['tilesettings_injection_desc'] = 'Replaces the default list on category pages and optionally on My courses – not on site home/Dashboard.';
$string['tiles_showunenrolled'] = 'Show courses without enrolment';
$string['tiles_showunenrolled_desc'] = 'Applies to category pages, My courses, and the block on site home/Dashboard. Tile footer shows “not enrolled yet”. Never on My courses without enrolment.';
$string['tiles_category'] = 'Show tiles on category pages';
$string['tiles_category_desc'] = 'Replaces the default list on category pages and course search results (/course/search.php) with the tile view. Depth on category pages can be limited separately.';
$string['tiles_category_maxdepth'] = 'Tile view up to level';
$string['tiles_category_maxdepth_desc'] = 'Applies only to category pages (/course/index.php?categoryid=…), not site home, Dashboard or My courses. The top-level category counts as level 1. With “Level 3”, levels 1–3 (top area plus two sub-levels) use tiles; deeper levels show the default Moodle view. “All levels” keeps tiles on every category level.';
$string['tiles_category_maxdepth_unlimited'] = 'All levels (unlimited)';
$string['tiles_category_maxdepth_level'] = 'Level {$a}';
$string['tiles_dashboard'] = 'Allow course tiles on Dashboard (centre)';
$string['tiles_dashboard_desc'] = 'Shows course tiles in the “Course tiles” block in the centre of /my/ (content region). The block is added automatically on first enable.';
$string['tiles_frontpage'] = 'Allow course tiles on site home (centre)';
$string['tiles_frontpage_desc'] = 'When “Course tiles” is selected in front page settings, courses are shown as tiles in the centre column (not the sidebar).';
$string['tiles_mycourses'] = 'Show tiles on My courses';
$string['tiles_mycourses_desc'] = 'Shows the tile view on /my/courses.php. Only enrolled courses are shown.';
$string['tiles_placeholderimage'] = 'Placeholder image for tiles without their own image';
$string['tiles_placeholderimage_desc'] = 'Shown in the tile view when a course or category has no tile image. Recommended: landscape, at least 400×200 px.';

$string['tilesettings_free_heading'] = 'Free tier (after trial)';
$string['tilesettings_free_desc'] = 'After the 100-day trial without a license: Dashboard and My courses with standard layout (2 columns, default colours, no footer info).';
$string['tilesettings_premium_heading'] = 'Premium features';
$string['tilesettings_premium_desc'] = 'Fully unlocked for 100 days. Afterwards with a license: front page, category pages, course search, placeholder image, tile info (progress, enrolment, course count), layout and colours.';
$string['tilesettings_layout_heading'] = 'Tile layout (Premium)';
$string['tilesettings_layout_desc'] = 'Image height, column count and description lines. The free tier uses default values.';
$string['tilesettings_colors_heading'] = 'Footer colours (Premium)';
$string['tilesettings_colors_desc'] = 'Background and text colour of the info bar at the bottom of each tile. Leave empty for defaults.';
$string['tiles_grid_columns'] = 'Columns per row';
$string['tiles_grid_columns_desc'] = 'Number of equal-width tiles side by side (from 520 px width).';
$string['tiles_image_height'] = 'Image height (pixels)';
$string['tiles_image_height_desc'] = 'Height of the course image in the tile.';
$string['tiles_desc_lines'] = 'Description lines';
$string['tiles_desc_lines_desc'] = 'Maximum lines of course description in the tile.';
$string['footer_color_bg'] = 'Background colour';
$string['footer_color_bg_desc'] = 'CSS colour value, e.g. #dff5e3';
$string['footer_color_fg'] = 'Text colour';
$string['footer_color_fg_desc'] = 'CSS colour value, e.g. #1f5c2e';
$string['footer_color_complete'] = 'Course complete';
$string['footer_color_progress'] = 'Course in progress';
$string['footer_color_notstarted'] = 'Course not started';
$string['footer_color_disabled'] = 'No completion tracking';
$string['footer_color_notenrolled'] = 'Not enrolled';
$string['footer_color_categorycount'] = 'Course count (category)';
$string['branding_footer'] = 'ZSK Course tiles – free version';
$string['license_premium_only_hint'] = '(Premium)';
$string['license_error_premium_required'] = 'This setting requires a valid Premium license or an active trial.';
$string['admin_category'] = 'ZSK course tiles display';
$string['license_settings_title'] = 'ZSK course tiles – license';
$string['settings_moved_title'] = 'Settings moved';
$string['settings_moved_tiles'] = 'Settings are now split across: {$a->license} · {$a->design}';
$string['license_heading'] = 'Premium license';
$string['license_heading_desc'] = 'All features are free for 100 days. Afterwards a valid license key unlocks Premium; without a key, Dashboard and My courses remain.';
$string['license_key'] = 'Premium license key';
$string['license_key_desc'] = 'ZSK Course tiles key only (prefix ZSK-KA-). Create on the license server with: php cli/create_license.php --plugin=local_zsk_local_tiles. To remove or replace: choose “Unmask”, edit the field and save.';
$string['license_status'] = 'License status';
$string['license_status_trial'] = 'Trial active – {$a} days of full access remaining';
$string['license_status_free'] = 'Free tier (Dashboard + My courses, standard layout)';
$string['license_status_premium'] = 'Premium (all features active)';
$string['license_status_premium_slots'] = 'Premium ({$a->used}/{$a->max} sites bound)';
$string['license_status_grace'] = 'Premium (offline grace: {$a} days)';
$string['license_status_key_unverified'] = 'License key saved, verification pending.';
$string['license_status_key_no_server'] = 'License key saved but no license server URL configured.';
$string['license_server_url'] = 'License server URL';
$string['license_server_url_desc'] = 'Full URL to the verify endpoint (e.g. http://204.168.247.140/zsk-license/api/v1/verify.php). Save this before entering the license key.';
$string['license_grace_days'] = 'Offline grace period (days)';
$string['license_grace_days_desc'] = 'If the license server is unreachable, Premium stays active for this many days.';
$string['license_error_no_server'] = 'No license server URL configured.';
$string['license_error_network'] = 'License server unreachable. Settings were saved; verification will be retried later (daily cron). Check the URL, firewall, and that Moodle can reach the server.';
$string['license_error_bad_response'] = 'Unexpected response from the license server (HTTP {$a->httpcode}). Check the URL – it must point to …/api/v1/verify.php. Current: {$a->url}';
$string['license_error_bad_response_short'] = 'Unexpected response from the license server – check URL (…/api/v1/verify.php).';
$string['license_warning_network_deferred'] = 'License server currently unreachable. Key and URL were saved; verification will run automatically once the server is reachable.';
$string['license_diag_heading'] = 'Diagnostics (verify)';
$string['license_diag_wwwroot'] = 'site_url for verify: {$a}';
$string['license_diag_server_url'] = 'License server URL: {$a}';
$string['license_diag_key_prefix'] = 'Key prefix: {$a}';
$string['license_diag_http_code'] = 'Last HTTP status: {$a}';
$string['license_diag_curl_error'] = 'cURL error: {$a}';
$string['license_diag_response'] = 'Server response (excerpt): {$a}';
$string['license_diag_cli_hint'] = 'CLI test on the Moodle server: php local/zsk_local_tiles/cli/test_license.php';
$string['license_error_expired'] = 'The license has expired.';
$string['license_error_invalid'] = 'The license key is invalid.';
$string['license_error_site_mismatch'] = 'This license key is bound to another Moodle instance.';
$string['license_error_site_limit'] = 'All {$a} site slots are already in use.';
$string['license_error_inactive'] = 'This license key has been deactivated.';
$string['license_error_plugin_mismatch'] = 'This license key is not valid for this plugin.';
$string['task_verify_license'] = 'ZSK Course tiles: verify license';

$string['cli_license_help'] = 'Test license verification from this Moodle server. Usage: php local/zsk_local_tiles/cli/test_license.php [--url=... --key=...]. Uses plugin config by default.';
$string['cli_license_heading'] = 'ZSK Course tiles – license test';
$string['cli_license_wwwroot'] = 'Moodle wwwroot:  {$a}';
$string['cli_license_verifyurl'] = 'Verify URL:     {$a}';
$string['cli_license_plugin'] = 'Plugin:         local_zsk_local_tiles';
$string['cli_license_keyprefix'] = 'Key prefix:     {$a}';
$string['cli_license_empty'] = '(empty)';
$string['cli_license_missing_config'] = 'URL and key required (in plugin settings or via --url / --key).';
$string['cli_license_httpstatus'] = 'HTTP status:    {$a}';
$string['cli_license_curlerror'] = 'cURL error:     [{$a->errno}] {$a->error}';
$string['cli_license_response'] = 'Response:';
$string['cli_license_no_json'] = 'No JSON response – check URL, Apache alias and permissions.';
$string['cli_license_ok'] = 'Result: OK – premium valid until {$a}';
$string['cli_license_failed'] = 'Verify failed: {$a}';
$string['tilesettings_content_heading'] = 'Tile content';
$string['tilesettings_content_desc'] = 'Controls where tile images and preview texts come from. Preview text is truncated to a maximum of 300 characters.';
$string['tiles_content_source'] = 'Take tile images and texts from';
$string['tiles_content_source_desc'] = 'Course settings: course image and summary / category description. Separate upload: dedicated maintenance pages for authorised users.';
$string['tiles_content_source_course'] = 'Course settings';
$string['tiles_content_source_custom'] = 'Separate upload';
$string['nav_manage_tiles'] = 'Maintain tile content';
$string['manageaccess'] = 'Users allowed to maintain tile content';
$string['manageaccess_desc'] = 'Only users listed here see the navigation item “Maintain tile content” and may maintain separate images/texts. Site administrators do not get this navigation item automatically.';
$string['manageaccess_saved'] = 'Allowed users have been saved.';
$string['allowedusers'] = 'Allowed users';
$string['allowedusers_help'] = 'Select the users who may maintain tile content independently of course settings.';
$string['manage_content_intro'] = 'Maintain images and preview texts for course and category tiles. Multilingual texts can use the Moodle multi-language filter.';
$string['manage_content_source_course_notice'] = 'Note: Tile settings currently use “Course settings”. Separately uploaded content is only used when the switch is set to “Separate upload”.';
$string['manage_content_saved'] = 'Tile content has been saved.';
$string['manage_courses'] = 'Maintain course tiles';
$string['manage_courses_intro'] = 'Choose a category and maintain image and preview text for multiple courses.';
$string['manage_courses_choose_category'] = 'Please choose a category first.';
$string['manage_courses_empty'] = 'There are no courses in this category.';
$string['manage_categories'] = 'Maintain category tiles';
$string['manage_categories_intro'] = 'Maintain image and preview text for categories. Optionally choose a parent to edit only its child categories.';
$string['manage_categories_parent'] = 'Parent category';
$string['manage_categories_top'] = 'Top level';
$string['manage_categories_empty'] = 'No categories on this level.';
$string['content_summary'] = 'Preview text (max. 300 characters on the tile)';
$string['content_image'] = 'Tile image';
$string['content_multilang_hint'] = 'Multilingual: texts may use the Moodle multi-language filter (e.g. {mlang de}…{mlang}{mlang en}…{mlang}). The tile shows the current UI language; display max. 300 characters.';
$string['backtohub'] = 'Back to overview';
$string['privacy:metadata'] = 'The plugin stores an allowlist for tile content maintenance plus optional tile texts and the editor user.';
$string['privacy:metadata:allow'] = 'Users allowed to maintain tile content.';
$string['privacy:metadata:allow:userid'] = 'User ID';
$string['privacy:metadata:allow:timecreated'] = 'When access was granted';
$string['privacy:metadata:allow:usermodified'] = 'Who granted access';
$string['privacy:metadata:coursecontent'] = 'Separately maintained course tile texts';
$string['privacy:metadata:categorycontent'] = 'Separately maintained category tile texts';
$string['privacy:metadata:content:usermodified'] = 'Last modified by';
$string['privacy:metadata:content:timemodified'] = 'Time of last modification';
$string['privacy:path:allow'] = 'Tile content access';
