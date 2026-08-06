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

$callbacks = [
    [
        'hook' => \core\hook\output\before_standard_top_of_body_html_generation::class,
        'callback' => [\local_zsk_local_tiles\hook_callbacks::class, 'inject_category_tiles'],
    ],
    [
        'hook' => \core\hook\output\before_footer_html_generation::class,
        'callback' => [\local_zsk_local_tiles\hook_callbacks::class, 'inject_category_tiles_footer'],
    ],
];

if (class_exists(\core\hook\output\before_standard_head_html_generation::class)) {
    $callbacks[] = [
        'hook' => \core\hook\output\before_standard_head_html_generation::class,
        'callback' => [\local_zsk_local_tiles\hook_callbacks::class, 'register_page_styles'],
    ];
}

if (class_exists(\core\hook\navigation\primary_extend::class)) {
    $callbacks[] = [
        'hook' => \core\hook\navigation\primary_extend::class,
        'callback' => [\local_zsk_local_tiles\hook_callbacks::class, 'extend_primary_navigation'],
    ];
}
