// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.

/**
 * Place course tiles in the front page centre column.
 *
 * @module     local_zsk_local_tiles/frontpage_layout
 * @copyright  2025 Silvio Kuhn
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
define([], function() {

    /**
     * @param {number} slotindex
     */
    const placeFrontpageTiles = (slotindex) => {
        const pending = document.getElementById('local-zsk-tiles-frontpage-pending');
        if (!pending) {
            return;
        }
        let section = document.getElementById('frontpage-course-tiles');
        if (!section || section.closest('#local-zsk-tiles-frontpage-pending')) {
            section = pending.querySelector('#frontpage-course-tiles');
        }
        if (!section) {
            return;
        }
        const main = document.querySelector('#region-main');
        if (!main) {
            return;
        }
        document.body.classList.add('local-zsk-tiles-frontpage-mode');
        const slot = parseInt(String(slotindex), 10) || 0;
        const selector = '#site-news-forum, #frontpage-course-list, #frontpage-available-course-list, ' +
            '#frontpage-category-names, #frontpage-category-combo, #frontpage-upcoming-events, ' +
            '#frontpage-course-tiles';
        const existing = [];
        main.querySelectorAll(selector).forEach((el) => {
            if (!el.closest('#local-zsk-tiles-frontpage-pending')) {
                existing.push(el);
            }
        });
        pending.removeAttribute('id');
        pending.classList.remove('local-zsk-tiles-frontpage-pending');
        const ref = existing[slot] || null;
        if (ref) {
            main.insertBefore(pending, ref);
        } else if (slot === 0) {
            main.insertBefore(pending, main.firstChild);
        } else {
            main.appendChild(pending);
        }
        document.dispatchEvent(new CustomEvent('local-zsk-tiles-tiles-rendered'));
    };

    /**
     * @param {number} slotindex
     */
    const init = (slotindex) => {
        const run = () => placeFrontpageTiles(slotindex);
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', run);
        } else {
            run();
        }
    };

    return {
        init: init,
    };
});
