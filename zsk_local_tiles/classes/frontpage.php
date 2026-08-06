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

namespace local_zsk_local_tiles;

defined('MOODLE_INTERNAL') || die();

/**
 * Front page centre-column integration (Startseite nach Anmeldung).
 */
class frontpage {

    /** @var string Custom frontpage slot value (not used by Moodle core). */
    public const FRONTPAGECOURSETILES = '8';

    /**
     * @param bool $loggedin
     * @return string[]
     */
    public static function get_layout_slots(bool $loggedin = true): array {
        global $CFG;

        $key = $loggedin ? 'frontpageloggedin' : 'frontpage';
        $layout = isset($CFG->$key) ? (string) $CFG->$key : '';
        if ($layout === '') {
            return [];
        }

        $slots = [];
        foreach (explode(',', $layout) as $slot) {
            $slot = trim($slot);
            if ($slot !== '' && $slot !== 'none') {
                $slots[] = $slot;
            }
        }

        return $slots;
    }

    /**
     * @param bool $loggedin
     * @return bool
     */
    public static function layout_includes_coursetiles(bool $loggedin = true): bool {
        return in_array(self::FRONTPAGECOURSETILES, self::get_layout_slots($loggedin), true);
    }

    /**
     * @return int|false Zero-based position among centre-column frontpage elements.
     */
    public static function get_coursetiles_slot_index(): int|false {
        $slots = self::get_layout_slots(true);
        $index = array_search(self::FRONTPAGECOURSETILES, $slots, true);
        return $index === false ? false : (int) $index;
    }

    /**
     * @return string
     */
    public static function render_coursetiles_section(): string {
        global $OUTPUT;

        if (!local_zsk_local_tiles_enabled_for('frontpage')) {
            return '';
        }

        require_once(__DIR__ . '/output/tile_grid.php');

        $includeunenrolled = local_zsk_local_tiles_include_unenrolled('frontpage');
        $payload = category_tiles::build_frontpage_courses_payload($includeunenrolled);
        $contents = \local_zsk_local_tiles\output\tile_grid::render_items(
            $payload['items'] ?? [],
            'frontpage-course-tiles'
        );

        if ($contents === '') {
            return '';
        }

        $header = get_string('frontpagecoursetiles_heading', 'local_zsk_local_tiles');
        $html = \html_writer::link(
            '#skipcoursetiles',
            get_string('skipa', 'access', \core_text::strtolower(strip_tags($header))),
            ['class' => 'skip-block skip aabtn']
        );
        $html .= \html_writer::start_tag('div', ['id' => 'frontpage-course-tiles', 'class' => 'local-zsk-tiles-frontpage-section']);
        $html .= $OUTPUT->heading($header, 2);
        $html .= $contents;
        $html .= \html_writer::end_tag('div');
        $html .= \html_writer::tag('span', '', ['class' => 'skip-block-to', 'id' => 'skipcoursetiles']);

        return $html;
    }

    /**
     * Inline CSS to hide the site-home course section UI when centre tiles are active.
     *
     * @return string
     */
    public static function get_frontpage_layout_hide_markup(): string {
        return '<style id="local-zsk-tiles-frontpage-hide">'
            . 'body.local-zsk-tiles-frontpage-mode.path-site.pagelayout-frontpage #region-main .course-content,'
            . 'body.local-zsk-tiles-frontpage-mode.path-site.pagelayout-frontpage #region-main [data-region="section-list"],'
            . 'body.local-zsk-tiles-frontpage-mode.path-site.pagelayout-frontpage #region-main [data-region="section-topics"],'
            . 'body.local-zsk-tiles-frontpage-mode.path-site.pagelayout-frontpage #region-main .course-section,'
            . 'body.local-zsk-tiles-frontpage-mode.path-site.pagelayout-frontpage #region-main .activity-add,'
            . 'body.local-zsk-tiles-frontpage-mode.path-site.pagelayout-frontpage #region-main .section-modchooser,'
            . 'body.local-zsk-tiles-frontpage-mode.path-site.pagelayout-frontpage #region-main .section-modchooser-link,'
            . 'body.local-zsk-tiles-frontpage-mode.path-site.pagelayout-frontpage #region-main a[data-action="addactivity"],'
            . 'body.local-zsk-tiles-frontpage-mode.path-site.pagelayout-frontpage #region-main .btn.add-content,'
            . 'body.local-zsk-tiles-frontpage-mode.path-site.pagelayout-frontpage #region-main > .buttons,'
            . 'body.local-zsk-tiles-frontpage-mode.path-site.pagelayout-frontpage #region-main > .singlebutton,'
            . 'body.local-zsk-tiles-frontpage-mode.path-site.pagelayout-frontpage #region-main .buttons .singlebutton'
            . '{display:none!important;}'
            . '</style>';
    }
}
