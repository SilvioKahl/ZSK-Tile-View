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

require_once(__DIR__ . '/../lib.php');

/**
 * Hook callbacks for tile injection.
 */
class hook_callbacks {

    /**
     * Register tile CSS in <head> before it is printed (site home / dashboard).
     *
     * @param \core\hook\output\before_standard_head_html_generation $hook
     */
    public static function register_page_styles(
        \core\hook\output\before_standard_head_html_generation $hook
    ): void {
        if (!local_zsk_local_tiles_page_needs_tile_styles()) {
            return;
        }

        $late = local_zsk_local_tiles_require_styles();
        if ($late !== '') {
            $hook->add_html($late);
        }
    }

    /**
     * @param \core\hook\output\before_standard_top_of_body_html_generation $hook
     */
    public static function inject_category_tiles(
        \core\hook\output\before_standard_top_of_body_html_generation $hook
    ): void {
        $hook->add_html(\local_zsk_local_tiles_category_tiles_bootstrap_html());
    }

    /**
     * @param \core\hook\output\before_footer_html_generation $hook
     */
    public static function inject_category_tiles_footer(
        \core\hook\output\before_footer_html_generation $hook
    ): void {
        self::inject_frontpage_coursetiles($hook);
        \local_zsk_local_tiles_inject_tiles_when_ready();
    }

    /**
     * Render course tiles in the front page centre column (Startseite nach Anmeldung).
     *
     * @param \core\hook\output\before_footer_html_generation $hook
     */
    public static function inject_frontpage_coursetiles(
        \core\hook\output\before_footer_html_generation $hook
    ): void {
        global $PAGE;

        if ($PAGE->pagetype !== 'site-index' || !\local_zsk_local_tiles\frontpage::layout_includes_coursetiles(true)) {
            return;
        }

        if (!local_zsk_local_tiles_enabled_for('frontpage')) {
            return;
        }

        $slotindex = \local_zsk_local_tiles\frontpage::get_coursetiles_slot_index();
        if ($slotindex === false) {
            return;
        }

        $hook->add_html(\local_zsk_local_tiles\frontpage::get_frontpage_layout_hide_markup());
        $PAGE->requires->js_call_amd('local_zsk_local_tiles/frontpage_mode', 'init');

        $html = \local_zsk_local_tiles\frontpage::render_coursetiles_section();
        if ($html !== '') {
            $html = \html_writer::div(
                $html,
                'local-zsk-tiles-frontpage-pending',
                [
                    'id' => 'local-zsk-tiles-frontpage-pending',
                    'data-layout-slot' => \local_zsk_local_tiles\frontpage::FRONTPAGECOURSETILES,
                    'data-zsk-fp-placement-pending' => '1',
                ]
            );
            $hook->add_html($html);
            if (!function_exists('local_zsk_frontpage_elements_is_enabled') || !local_zsk_frontpage_elements_is_enabled()) {
                $PAGE->requires->js_call_amd('local_zsk_local_tiles/frontpage_layout', 'init', [$slotindex]);
            }
        }
    }

    /**
     * Add "Kachelinhalte pflegen" for allowlisted users only (not auto for admins).
     *
     * @param \core\hook\navigation\primary_extend $hook
     */
    public static function extend_primary_navigation(\core\hook\navigation\primary_extend $hook): void {
        if (!local_zsk_local_tiles_user_can_manage_content()) {
            return;
        }

        $primaryview = $hook->get_primaryview();
        if ($primaryview->find('local_zsk_local_tiles_manage', \navigation_node::TYPE_CUSTOM)) {
            return;
        }

        $primaryview->add(
            get_string('nav_manage_tiles', 'local_zsk_local_tiles'),
            new \moodle_url('/local/zsk_local_tiles/manage_content.php'),
            \navigation_node::TYPE_CUSTOM,
            null,
            'local_zsk_local_tiles_manage',
            new \pix_icon('i/edit', '')
        );
    }
}
