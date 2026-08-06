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

namespace local_zsk_local_tiles\output;

defined('MOODLE_INTERNAL') || die();

/**
 * Server-side HTML renderer for course/category tile grids (blocks, etc.).
 */
class tile_grid {

    /**
     * @param array $items Tile payloads from category_tiles.
     * @param string $wrapperid Optional DOM id on the wrapper (for JS integrations).
     * @return string
     */
    public static function render_items(array $items, string $wrapperid = 'local-zsk-tiles-category-tiles'): string {
        if (empty($items)) {
            return '';
        }

        require_once(__DIR__ . '/../../lib.php');
        $html = local_zsk_local_tiles_require_styles();

        $html .= \html_writer::start_div('local-zsk-tiles-category-tiles', ['id' => $wrapperid]);
        $html .= \html_writer::start_div('local-zsk-tiles-category-grid');

        foreach ($items as $item) {
            $html .= self::render_card($item);
        }

        $html .= \html_writer::end_div();
        $html .= \html_writer::end_div();

        if (\local_zsk_local_tiles\util\license::show_branding()) {
            $html .= \html_writer::div(
                get_string('branding_footer', 'local_zsk_local_tiles'),
                'local-zsk-tiles-branding'
            );
        }

        return $html;
    }

    /**
     * @param array $item
     * @return string
     */
    protected static function render_card(array $item): string {
        $classes = 'local-zsk-tiles-category-card';
        if (empty($item['image'])) {
            $classes .= ' local-zsk-tiles-no-image';
        }

        $linkattrs = ['class' => $classes];
        $url = $item['url'] ?? '#';
        $inner = '';

        if (!empty($item['image'])) {
            $inner .= \html_writer::empty_tag('img', [
                'class' => 'local-zsk-tiles-category-image',
                'src' => $item['image'],
                'alt' => $item['title'] ?? '',
                'loading' => 'lazy',
            ]);
        }

        $body = \html_writer::start_div('local-zsk-tiles-category-card-body');
        $body .= \html_writer::tag('h4', $item['title'] ?? '', ['class' => 'local-zsk-tiles-category-card-title']);

        if (!empty($item['text'])) {
            $body .= \html_writer::tag('p', $item['text'], ['class' => 'local-zsk-tiles-category-card-text']);
        }

        if (!empty($item['completiontext'])) {
            $state = $item['completionstate'] ?? 'disabled';
            $body .= \html_writer::tag('p', $item['completiontext'], [
                'class' => 'local-zsk-tiles-category-card-footer local-zsk-tiles-completion-' . $state,
            ]);
        } else if (!empty($item['categorycounttext'])) {
            $body .= \html_writer::tag('p', $item['categorycounttext'], [
                'class' => 'local-zsk-tiles-category-card-footer local-zsk-tiles-category-count',
            ]);
        }

        $body .= \html_writer::end_div();
        $inner .= $body;

        return \html_writer::link($url, $inner, $linkattrs);
    }
}
