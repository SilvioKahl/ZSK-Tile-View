// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.

/**
 * Enable front page tile layout mode on the body element.
 *
 * @module     local_zsk_local_tiles/frontpage_mode
 * @copyright  2025 Silvio Kuhn
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
define([], function() {

    const init = () => {
        document.body.classList.add('local-zsk-tiles-frontpage-mode');
    };

    return {
        init: init,
    };
});
