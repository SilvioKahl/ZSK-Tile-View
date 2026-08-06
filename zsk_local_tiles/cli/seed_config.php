<?php
// This file is part of Moodle - http://moodle.org/
/**
 * CLI: seed missing plugin config defaults (breaks admin redirect loops).
 *
 * Usage (from Moodle root):
 *   php local/zsk_local_tiles/cli/seed_config.php
 *
 * @package    local_zsk_local_tiles
 * @copyright  2026 Silvio Kuhn
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define('CLI_SCRIPT', true);

require(__DIR__ . '/../../../config.php');
require_once($CFG->libdir . '/clilib.php');
require_once($CFG->dirroot . '/local/zsk_local_tiles/lib.php');

cli_heading('ZSK local_zsk_local_tiles – seed config defaults');

$written = local_zsk_local_tiles_seed_config_defaults();
cli_writeln("Tiles: wrote {$written} missing config key(s).");

if (is_readable($CFG->dirroot . '/local/zsk_local_statistics/lib.php')) {
    require_once($CFG->dirroot . '/local/zsk_local_statistics/lib.php');
    if (function_exists('local_zsk_local_statistics_seed_config_defaults')) {
        $w2 = local_zsk_local_statistics_seed_config_defaults();
        cli_writeln("Statistics: wrote {$w2} missing config key(s).");
    }
}

purge_all_caches();
cli_writeln('Caches purged.');
cli_writeln('Done. Open /login/index.php (not /admin/index.php first), then continue.');
exit(0);
