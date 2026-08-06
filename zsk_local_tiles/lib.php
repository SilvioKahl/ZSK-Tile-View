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

/**
 * Primary navigation (Moodle ≤ 4.3): tile content maintenance for allowlisted users only.
 * Site admins do NOT get this entry automatically.
 *
 * @param global_navigation $navigation
 * @return void
 */
function local_zsk_local_tiles_extend_navigation(global_navigation $navigation) {
    if (!local_zsk_local_tiles_user_can_manage_content()) {
        return;
    }

    $node = $navigation->add(
        get_string('nav_manage_tiles', 'local_zsk_local_tiles'),
        new moodle_url('/local/zsk_local_tiles/manage_content.php'),
        navigation_node::TYPE_CUSTOM,
        null,
        'local_zsk_local_tiles_manage',
        new pix_icon('i/edit', '')
    );
    $node->showinflatnavigation = true;
}

/**
 * Whether the current user is on the tile-content allowlist.
 * Intentionally does NOT grant access to site admins automatically.
 *
 * @param int|null $userid
 * @return bool
 */
function local_zsk_local_tiles_user_can_manage_content(?int $userid = null): bool {
    global $DB, $USER;

    if ($userid === null) {
        $userid = (int) $USER->id;
    }
    if ($userid <= 0 || !isloggedin() || isguestuser($userid)) {
        return false;
    }

    return $DB->record_exists('local_zsk_local_tiles_allow', ['userid' => $userid]);
}

/**
 * @return void
 */
function local_zsk_local_tiles_require_manage_content(): void {
    if (!local_zsk_local_tiles_user_can_manage_content()) {
        throw new \moodle_exception('nopermissions', 'error');
    }
}

/**
 * @return int[]
 */
function local_zsk_local_tiles_get_allowed_userids(): array {
    global $DB;
    return array_map('intval', $DB->get_fieldset_select('local_zsk_local_tiles_allow', 'userid', '1=1'));
}

/**
 * @return array userid => display name
 */
function local_zsk_local_tiles_get_allowed_user_options(): array {
    global $DB;
    $userids = local_zsk_local_tiles_get_allowed_userids();
    if (empty($userids)) {
        return [];
    }
    list($insql, $params) = $DB->get_in_or_equal($userids, SQL_PARAMS_NAMED);
    $users = $DB->get_records_select('user', "id $insql AND deleted = 0", $params, 'lastname, firstname');
    $options = [];
    foreach ($users as $user) {
        $options[$user->id] = fullname($user);
    }
    return $options;
}

/**
 * @param array $userids
 * @return void
 */
function local_zsk_local_tiles_set_allowed_userids(array $userids): void {
    global $DB, $USER;

    $userids = array_values(array_unique(array_filter(array_map('intval', $userids))));
    $existing = local_zsk_local_tiles_get_allowed_userids();
    $now = time();

    foreach (array_diff($existing, $userids) as $removeid) {
        $DB->delete_records('local_zsk_local_tiles_allow', ['userid' => $removeid]);
    }
    foreach (array_diff($userids, $existing) as $addid) {
        if ($addid <= 0) {
            continue;
        }
        $DB->insert_record('local_zsk_local_tiles_allow', (object) [
            'userid' => $addid,
            'timecreated' => $now,
            'usermodified' => (int) $USER->id,
        ]);
    }
}

function local_zsk_local_tiles_is_category_index_page(): bool {
    global $PAGE, $SCRIPT;

    $script = !empty($SCRIPT) ? (string) $SCRIPT : '';
    if ($script === '/course/index.php' || preg_match('#^/course/index\.php$#', $script)) {
        return true;
    }

    if (!empty($PAGE->url)) {
        $path = (string) $PAGE->url->get_path(false);
        if (preg_match('#^/course/index\.php$#', $path)) {
            return true;
        }
    }

    return false;
}

/**
 * Whether a request path starts with a prefix (script or PAGE url path).
 *
 * @param string $prefix e.g. "/message/"
 * @param string|null $script
 * @param string|null $path
 * @return bool
 */
function local_zsk_local_tiles_request_path_has_prefix(string $prefix, ?string $script = null, ?string $path = null): bool {
    if ($script === null || $path === null) {
        $script = local_zsk_local_tiles_get_request_script();
        global $PAGE;
        $path = !empty($PAGE->url) ? (string) $PAGE->url->get_path(false) : '';
    }

    foreach ([$script, $path] as $candidate) {
        if ($candidate !== '' && strpos($candidate, $prefix) === 0) {
            return true;
        }
    }

    return false;
}

/**
 * Whether the request targets an allowed /course/ script (listing or site-home course view).
 *
 * @param string|null $script
 * @param string|null $path
 * @return bool
 */
function local_zsk_local_tiles_is_allowed_course_tile_script(?string $script = null, ?string $path = null): bool {
    if ($script === null || $path === null) {
        $script = local_zsk_local_tiles_get_request_script();
        global $PAGE;
        $path = !empty($PAGE->url) ? (string) $PAGE->url->get_path(false) : '';
    }

    $allowed = ['/course/index.php', '/course/search.php'];
    foreach ([$script, $path] as $candidate) {
        if ($candidate === '') {
            continue;
        }
        foreach ($allowed as $suffix) {
            if ($candidate === $suffix || substr($candidate, -strlen($suffix)) === $suffix) {
                return true;
            }
        }
    }

    return false;
}

/**
 * Pages where tiles must never appear (admin, course management, etc.).
 *
 * @return bool
 */
function local_zsk_local_tiles_is_excluded_tile_page(): bool {
    global $PAGE;

    $script = local_zsk_local_tiles_get_request_script();
    $path = !empty($PAGE->url) ? (string) $PAGE->url->get_path(false) : '';
    $pagelayout = !empty($PAGE->pagelayout) ? (string) $PAGE->pagelayout : '';
    $pagetype = !empty($PAGE->pagetype) ? (string) $PAGE->pagetype : '';

    if (local_zsk_local_tiles_request_path_has_prefix('/admin/', $script, $path)) {
        return true;
    }

    if ($pagelayout === 'admin' || strpos($pagelayout, 'admin') === 0) {
        return true;
    }

    if (strpos($pagetype, 'admin-') === 0 || $pagetype === 'admin') {
        return true;
    }

    if (strpos($script, '/local/statistics/') !== false || strpos($path, '/local/statistics/') !== false) {
        return true;
    }

    if (strpos($script, '/local/zsk_local_tiles/') !== false || strpos($path, '/local/zsk_local_tiles/') !== false) {
        return true;
    }

    if (strpos($script, '/local/tiles/') !== false || strpos($path, '/local/tiles/') !== false) {
        return true;
    }

    if (strpos($script, '/local/newsletter/') !== false || strpos($path, '/local/newsletter/') !== false) {
        return true;
    }

    if (local_zsk_local_tiles_request_path_has_prefix('/user/', $script, $path)) {
        return true;
    }

    if (local_zsk_local_tiles_request_path_has_prefix('/login/', $script, $path)) {
        return true;
    }

    if (local_zsk_local_tiles_request_path_has_prefix('/mod/', $script, $path)) {
        return true;
    }

    // Never inject tiles on course home pages.
    if ($script === '/course/view.php' || preg_match('#/course/view\.php$#', $script)
        || $path === '/course/view.php' || preg_match('#/course/view\.php$#', $path)) {
        return true;
    }

    $noncourseprefixes = [
        '/message/',
        '/blog/',
        '/calendar/',
        '/grade/',
        '/group/',
        '/notes/',
        '/tag/',
        '/badges/',
        '/report/',
        '/payment/',
        '/portfolio/',
        '/search/',
        '/cohort/',
        '/enrol/',
        '/files/',
        '/privacy/',
        '/communication/',
        '/contentbank/',
        '/h5p/',
        '/comment/',
        '/rating/',
        '/customfield/',
        '/availability/',
        '/backup/',
        '/restore/',
        '/error/',
        '/help/',
        '/filter/',
        '/repository/',
        '/media/',
        '/mnet/',
        '/competency/',
        '/analytics/',
        '/ai/',
        '/plagiarism/',
        '/question/',
    ];
    foreach ($noncourseprefixes as $prefix) {
        if (local_zsk_local_tiles_request_path_has_prefix($prefix, $script, $path)) {
            return true;
        }
    }

    if (local_zsk_local_tiles_request_path_has_prefix('/my/', $script, $path)
        && !local_zsk_local_tiles_is_mycourses_request()
        && !local_zsk_local_tiles_is_dashboard_request()) {
        return true;
    }

    if (local_zsk_local_tiles_request_path_has_prefix('/course/', $script, $path)
        && !local_zsk_local_tiles_is_allowed_course_tile_script($script, $path)) {
        return true;
    }

    $excludedscripts = [
        '/course/management.php',
        '/course/edit.php',
        '/course/editbulk.php',
        '/course/delete.php',
        '/course/reset.php',
    ];
    foreach ($excludedscripts as $excluded) {
        if ($script === $excluded || strpos($script, $excluded) !== false) {
            return true;
        }
        if ($path === $excluded || strpos($path, $excluded) !== false) {
            return true;
        }
    }

    return false;
}

/**
 * Resolve category id from request/page URL.
 *
 * @return int
 */
function local_zsk_local_tiles_get_request_categoryid(): int {
    global $PAGE;

    $categoryid = optional_param('categoryid', 0, PARAM_INT);
    if ($categoryid > 0) {
        return $categoryid;
    }

    if (!empty($PAGE->url)) {
        $categoryid = (int) ($PAGE->url->get_param('categoryid') ?? 0);
        if ($categoryid > 0) {
            return $categoryid;
        }
    }

    return 0;
}

/**
 * Whether the current request is the course search results page.
 *
 * @return bool
 */
function local_zsk_local_tiles_is_course_search_request(): bool {
    $script = local_zsk_local_tiles_get_request_script();

    return $script === '/course/search.php' || preg_match('#/course/search\.php$#', $script) === 1;
}

/**
 * Active course search criteria (same parameters as core course/search.php).
 *
 * @return array
 */
function local_zsk_local_tiles_get_course_search_criteria(): array {
    $q = optional_param('q', '', PARAM_RAW);
    $search = optional_param('search', '', PARAM_RAW);
    if ($q !== '') {
        $search = $q;
    }
    $search = trim(strip_tags($search));

    $criteria = [];
    if ($search !== '') {
        $criteria['search'] = $search;
    }

    $blocklist = optional_param('blocklist', 0, PARAM_INT);
    if ($blocklist > 0) {
        $criteria['blocklist'] = $blocklist;
    }

    $modulelist = optional_param('modulelist', '', PARAM_PLUGIN);
    if ($modulelist !== '') {
        $criteria['modulelist'] = $modulelist;
    }

    $tagid = optional_param('tagid', 0, PARAM_INT);
    if ($tagid > 0) {
        $criteria['tagid'] = $tagid;
    }

    return $criteria;
}

/**
 * Whether tile view is enabled for a page context.
 *
 * @param string $context category|dashboard|frontpage|mycourses|search
 * @return bool
 */
function local_zsk_local_tiles_enabled_for(string $context): bool {
    $defaults = [
        'category' => 1,
        'dashboard' => 0,
        'frontpage' => 0,
        'mycourses' => 0,
        'search' => 1,
    ];

    $keys = [
        'category' => 'tiles_category',
        'dashboard' => 'tiles_dashboard',
        'frontpage' => 'tiles_frontpage',
        'mycourses' => 'tiles_mycourses',
        'search' => 'tiles_category',
    ];

    if (!isset($keys[$context])) {
        return false;
    }

    $value = get_config('local_zsk_local_tiles', $keys[$context]);
    if ($value === false || $value === null || $value === '') {
        return !empty($defaults[$context]);
    }

    return ((string) $value === '1') && \local_zsk_local_tiles\util\license::is_context_allowed($context);
}

/**
 * Maximum category hierarchy depth for tile view (0 = unlimited).
 *
 * Moodle stores depth starting at 1 for top-level categories. Setting „3“ means
 * depths 1–3 (top area plus two sub-levels) use tiles.
 *
 * @return int
 */
function local_zsk_local_tiles_get_category_max_depth(): int {
    $value = get_config('local_zsk_local_tiles', 'tiles_category_maxdepth');
    if ($value === false || $value === null || $value === '') {
        return 0;
    }

    return max(0, (int) $value);
}

/**
 * Resolve Moodle depth for a course category (1 = top level).
 *
 * @param int $categoryid
 * @return int 0 if unknown
 */
function local_zsk_local_tiles_get_category_depth(int $categoryid): int {
    global $DB;

    if ($categoryid <= 0) {
        return 0;
    }

    try {
        $category = \core_course_category::get($categoryid, IGNORE_MISSING);
        if ($category) {
            return (int) $category->depth;
        }
    } catch (\Throwable $e) {
        // Fall through to DB lookup.
    }

    return (int) $DB->get_field('course_categories', 'depth', ['id' => $categoryid]);
}

/**
 * Whether the current category page is within the configured tile depth.
 *
 * @param int $categoryid
 * @return bool
 */
function local_zsk_local_tiles_category_depth_allows_tiles(int $categoryid): bool {
    $maxdepth = local_zsk_local_tiles_get_category_max_depth();
    if ($maxdepth <= 0) {
        return true;
    }

    $depth = local_zsk_local_tiles_get_category_depth($categoryid);
    return $depth > 0 && $depth <= $maxdepth;
}

/**
 * Whether courses without enrolment may be included (never on My courses).
 *
 * @param string $context
 * @return bool
 */
function local_zsk_local_tiles_include_unenrolled(string $context): bool {
    if ($context === 'mycourses') {
        return false;
    }

    if (!\local_zsk_local_tiles\util\license::can_use_unenrolled_courses()) {
        return false;
    }

    return (bool) get_config('local_zsk_local_tiles', 'tiles_showunenrolled');
}

/**
 * Register plugin stylesheet via Moodle core callback.
 *
 * @param context $context
 * @return string[]
 */
function local_zsk_local_tiles_get_stylesheets_for_context(context $context): array {
    if (!local_zsk_local_tiles_page_needs_tile_styles()) {
        return [];
    }

    return ['/local/zsk_local_tiles/styles.css'];
}

/**
 * Whether the current page will render server-side course tiles (needs styles.css).
 *
 * @return bool
 */
function local_zsk_local_tiles_page_needs_tile_styles(): bool {
    global $PAGE;

    if (empty($PAGE) || !isloggedin() || isguestuser()) {
        return false;
    }

    $context = local_zsk_local_tiles_get_tile_page_context();
    if ($context === null) {
        return false;
    }

    if ($context === 'frontpage') {
        require_once(__DIR__ . '/classes/frontpage.php');
        return local_zsk_local_tiles_enabled_for('frontpage')
            && \local_zsk_local_tiles\frontpage::layout_includes_coursetiles(true);
    }

    if ($context === 'category') {
        $categoryid = local_zsk_local_tiles_get_request_categoryid();
        return local_zsk_local_tiles_enabled_for('category')
            && $categoryid > 0
            && local_zsk_local_tiles_category_depth_allows_tiles($categoryid);
    }

    return local_zsk_local_tiles_enabled_for($context);
}

/**
 * Stylesheet URL with cache-busting revision.
 *
 * @return string
 */
function local_zsk_local_tiles_get_stylesheet_url(): string {
    $rev = get_config('local_zsk_local_tiles', 'version');
    if (empty($rev)) {
        $plugin = new \stdClass();
        require(__DIR__ . '/version.php');
        $rev = $plugin->version;
    }

    $url = new moodle_url('/local/zsk_local_tiles/styles.css');
    $url->param('rev', $rev);

    return $url->out(false);
}

/**
 * Register tile stylesheet in <head> or return a late <link> fallback.
 *
 * @return string Optional <link> tag when <head> was already printed.
 */
function local_zsk_local_tiles_require_styles(): string {
    global $PAGE;

    static $handled = false;
    if ($handled || empty($PAGE)) {
        return '';
    }

    $customcss = local_zsk_local_tiles_get_custom_css();

    if (!$PAGE->requires->is_head_done()) {
        $handled = true;
        if ($customcss !== '') {
            return html_writer::tag('style', $customcss, ['data-local-zsk-tiles-custom' => '1']);
        }
        return '';
    }

    $handled = true;

    $html = html_writer::empty_tag('link', [
        'rel' => 'stylesheet',
        'href' => local_zsk_local_tiles_get_stylesheet_url(),
        'data-local-zsk-tiles-tiles' => '1',
    ]);

    if ($customcss !== '') {
        $html .= html_writer::tag('style', $customcss, ['data-local-zsk-tiles-custom' => '1']);
    }

    return $html;
}

/**
 * Premium layout and colour overrides as CSS.
 *
 * @return string Raw CSS (without style tags).
 */
function local_zsk_local_tiles_get_custom_css(): string {
    if (!\local_zsk_local_tiles\util\license::can_use_custom_layout()
        && !\local_zsk_local_tiles\util\license::can_use_custom_colors()) {
        return '';
    }

    $css = '';

    if (\local_zsk_local_tiles\util\license::can_use_custom_layout()) {
        $height = (int) get_config('local_zsk_local_tiles', 'tiles_image_height');
        if ($height < 80) {
            $height = 175;
        }
        $columns = (int) get_config('local_zsk_local_tiles', 'tiles_grid_columns');
        if ($columns < 1 || $columns > 3) {
            $columns = 2;
        }
        $desclines = (int) get_config('local_zsk_local_tiles', 'tiles_desc_lines');
        if ($desclines < 2) {
            $desclines = 7;
        }

        $css .= ':root {'
            . '--local-zsk-tiles-image-height: ' . $height . 'px;'
            . '--local-zsk-tiles-desc-lines: ' . $desclines . ';'
            . '--local-zsk-tiles-grid-columns: ' . $columns . ';'
            . '}' . "\n";

        if ($columns !== 2) {
            $css .= '@media (min-width: 520px) {'
                . '.local-zsk-tiles-category-tiles > .local-zsk-tiles-category-grid,'
                . '.block_coursetiles .local-zsk-tiles-category-grid,'
                . '#local-zsk-tiles-category-tiles .local-zsk-tiles-category-grid,'
                . '#frontpage-course-tiles .local-zsk-tiles-category-grid {'
                . 'grid-template-columns: repeat(' . $columns . ', minmax(0, 1fr));'
                . '}' . "}\n";
        }
    }

    if (\local_zsk_local_tiles\util\license::can_use_custom_colors()) {
        $states = [
            'complete' => 'footer_color_complete',
            'progress' => 'footer_color_progress',
            'notstarted' => 'footer_color_notstarted',
            'disabled' => 'footer_color_disabled',
            'notenrolled' => 'footer_color_notenrolled',
            'category-count' => 'footer_color_categorycount',
        ];
        foreach ($states as $class => $prefix) {
            $bg = trim((string) get_config('local_zsk_local_tiles', $prefix . '_bg'));
            $fg = trim((string) get_config('local_zsk_local_tiles', $prefix . '_fg'));
            if ($bg === '' && $fg === '') {
                continue;
            }
            $selector = $class === 'category-count'
                ? '.local-zsk-tiles-category-count'
                : '.local-zsk-tiles-completion-' . $class;
            $css .= $selector . ' {';
            if ($bg !== '') {
                $css .= 'background-color:' . $bg . ';border-top-color:' . $bg . ';';
            }
            if ($fg !== '') {
                $css .= 'color:' . $fg . ';';
            }
            $css .= '}' . "\n";
        }
    }

    return $css;
}

/**
 * Whether the user must complete login-related actions before normal browsing.
 *
 * @return bool
 */
function local_zsk_local_tiles_user_has_pending_login_actions(): bool {
    global $USER;

    if (get_user_preferences('auth_forcepasswordchange', false, $USER)) {
        return true;
    }

    if (empty($USER->confirmed)) {
        return true;
    }

    return false;
}

/**
 * Current request script path (leading slash, no query string).
 *
 * @return string
 */
function local_zsk_local_tiles_get_request_script(): string {
    global $SCRIPT, $PAGE;

    $script = '';
    if (!empty($SCRIPT)) {
        $script = (string) $SCRIPT;
    } else if (!empty($PAGE->url)) {
        $script = (string) $PAGE->url->get_path(false);
    }

    if ($script !== '' && $script[0] !== '/') {
        $script = '/' . $script;
    }

    return $script;
}

/**
 * Whether the current request is the "My courses" page.
 *
 * @return bool
 */
function local_zsk_local_tiles_is_mycourses_request(): bool {
    global $PAGE;

    $script = local_zsk_local_tiles_get_request_script();
    if ($script === '/my/courses.php' || preg_match('#/my/courses\.php$#', $script)) {
        return true;
    }

    if (!empty($PAGE->url)) {
        $path = (string) $PAGE->url->get_path(false);
        if ($path === '/my/courses.php' || preg_match('#/my/courses\.php$#', $path)) {
            return true;
        }
    }

    if (!empty($PAGE->pagetype) && $PAGE->pagetype === 'my-courses') {
        return true;
    }

    return false;
}

/**
 * Whether the current request is the personal dashboard (/my/index.php).
 *
 * @return bool
 */
function local_zsk_local_tiles_is_dashboard_request(): bool {
    global $PAGE;

    if (local_zsk_local_tiles_is_mycourses_request()) {
        return false;
    }

    $script = local_zsk_local_tiles_get_request_script();
    if ($script === '/my/index.php' || preg_match('#/my/index\.php$#', $script)) {
        return true;
    }

    if (!empty($PAGE->url)) {
        $path = (string) $PAGE->url->get_path(false);
        if ($path === '/my/index.php' || preg_match('#/my/index\.php$#', $path)) {
            return true;
        }
    }

    if (!empty($PAGE->pagetype) && $PAGE->pagetype === 'my-index') {
        return true;
    }

    return false;
}

/**
 * Course id configured as the site home page.
 *
 * @return int
 */
function local_zsk_local_tiles_get_frontpage_course_id(): int {
    global $CFG;

    $frontpage = (int) $CFG->frontpage;
    return $frontpage > 0 ? $frontpage : (int) SITEID;
}

/**
 * Whether the user is inside a course (activity or course interior), not a listing page.
 *
 * @return bool
 */
function local_zsk_local_tiles_is_course_interior_page(): bool {
    global $PAGE, $CFG;

    $script = local_zsk_local_tiles_get_request_script();

    if (preg_match('#^/mod/#', $script)) {
        return true;
    }

    if (!empty($PAGE->cm)) {
        return true;
    }

    $pagetype = !empty($PAGE->pagetype) ? (string) $PAGE->pagetype : '';
    if (strncmp($pagetype, 'mod-', 4) === 0) {
        return true;
    }

    if ($script !== '/course/view.php' && !preg_match('#/course/view\.php$#', $script)) {
        return false;
    }

    $courseid = optional_param('id', 0, PARAM_INT);
    if ($courseid <= 0 && !empty($PAGE->course)) {
        $courseid = (int) $PAGE->course->id;
    }

    $frontpageid = local_zsk_local_tiles_get_frontpage_course_id();

    // Any course other than the configured site home is always "inside" the course.
    if ($courseid > 0 && $courseid !== $frontpageid) {
        return true;
    }

    // Site home course: only the bare course home is a listing; sections/activities are interior.
    if (optional_param('section', null, PARAM_INT) !== null) {
        return true;
    }

    $pagelayout = !empty($PAGE->pagelayout) ? (string) $PAGE->pagelayout : '';
    if ($pagelayout === 'incourse') {
        return true;
    }

    return false;
}

/**
 * Whether the current request is the site front page (Startseite).
 *
 * @return bool
 */
function local_zsk_local_tiles_is_site_frontpage(): bool {
    global $PAGE;

    if (local_zsk_local_tiles_is_excluded_tile_page()) {
        return false;
    }

    if (local_zsk_local_tiles_is_mycourses_request() || local_zsk_local_tiles_is_dashboard_request()) {
        return false;
    }

    if (local_zsk_local_tiles_is_course_interior_page()) {
        return false;
    }

    $script = local_zsk_local_tiles_get_request_script();
    $path = !empty($PAGE->url) ? (string) $PAGE->url->get_path(false) : '';
    $pagetype = !empty($PAGE->pagetype) ? (string) $PAGE->pagetype : '';

    if ($pagetype === 'site-index') {
        if ($script === '/index.php' || preg_match('#^/index\.php$#', $script)) {
            return true;
        }
        if ($script === '/course/view.php' && local_zsk_local_tiles_is_site_frontpage_course_home()) {
            return true;
        }
        return false;
    }

    if ($script === '/index.php' || $path === '/' || preg_match('#^/index\.php$#', $path)) {
        if (!local_zsk_local_tiles_request_path_has_prefix('/my/', $script, $path)) {
            return true;
        }
    }

    if ($script === '/course/view.php' && local_zsk_local_tiles_is_site_frontpage_course_home()) {
        return true;
    }

    return false;
}

/**
 * Site home when displayed as the front-page course (no section/activity open).
 *
 * @return bool
 */
function local_zsk_local_tiles_is_site_frontpage_course_home(): bool {
    global $PAGE, $CFG;

    if (local_zsk_local_tiles_is_course_interior_page()) {
        return false;
    }

    $courseid = optional_param('id', 0, PARAM_INT);
    if ($courseid <= 0 && !empty($PAGE->course)) {
        $courseid = (int) $PAGE->course->id;
    }

    if ($courseid <= 0) {
        return false;
    }

    $frontpageid = local_zsk_local_tiles_get_frontpage_course_id();

    return $courseid === $frontpageid || ($courseid === (int) SITEID && (int) $CFG->frontpage === 0);
}

/**
 * Page context for the course-tiles block (dashboard or site home only).
 *
 * @return string|null dashboard|frontpage
 */
function local_zsk_local_tiles_block_get_page_context(): ?string {
    global $PAGE;

    if (empty($PAGE) || !isloggedin() || isguestuser()) {
        return null;
    }

    $pagetype = (string) $PAGE->pagetype;
    if ($pagetype === 'my-index') {
        return 'dashboard';
    }
    if ($pagetype === 'site-index') {
        return 'frontpage';
    }

    return null;
}

/**
 * Whether dashboard/frontpage use block integration (no page replacement).
 *
 * @param string $context dashboard|frontpage
 * @return bool
 */
function local_zsk_local_tiles_uses_block_integration(string $context): bool {
    return in_array($context, ['dashboard', 'frontpage'], true);
}

/**
 * @return string|null category|dashboard|frontpage|mycourses|search
 */
function local_zsk_local_tiles_get_tile_page_context(): ?string {
    global $PAGE;

    if (!isloggedin() || isguestuser() || empty($PAGE)) {
        return null;
    }

    if (local_zsk_local_tiles_is_excluded_tile_page()) {
        return null;
    }

    if (local_zsk_local_tiles_user_has_pending_login_actions()) {
        return null;
    }

    if (local_zsk_local_tiles_is_course_interior_page()) {
        return null;
    }

    $script = local_zsk_local_tiles_get_request_script();

    if ($script === '/course/index.php' && local_zsk_local_tiles_get_request_categoryid() > 0) {
        return 'category';
    }

    if (local_zsk_local_tiles_is_course_search_request() && local_zsk_local_tiles_get_course_search_criteria() !== []) {
        return 'search';
    }

    if (local_zsk_local_tiles_is_mycourses_request()) {
        return 'mycourses';
    }

    if (local_zsk_local_tiles_is_dashboard_request()) {
        return 'dashboard';
    }

    if (local_zsk_local_tiles_is_site_frontpage()) {
        return 'frontpage';
    }

    return null;
}

/**
 * Inject tiles for a specific category id.
 *
 * @param int $categoryid
 * @return void
 */
function local_zsk_local_tiles_inject_category_tiles_for_category(int $categoryid): void {
    if (local_zsk_local_tiles_is_excluded_tile_page()) {
        return;
    }

    if ($categoryid <= 0 || !local_zsk_local_tiles_enabled_for('category')) {
        return;
    }

    if (!local_zsk_local_tiles_category_depth_allows_tiles($categoryid)) {
        return;
    }

    static $injected = [];
    if (isset($injected[$categoryid])) {
        return;
    }
    $injected[$categoryid] = true;

    $includeunenrolled = local_zsk_local_tiles_include_unenrolled('category');
    $payload = \local_zsk_local_tiles\category_tiles::build_payload($categoryid, [
        'includeunenrolled' => $includeunenrolled,
    ]);

    local_zsk_local_tiles_inject_tiles($payload['items'] ?? [], 'category');
}

/**
 * Render tile grid via JavaScript.
 *
 * @param array $items
 * @param string $mode category|courses
 * @return void
 */
function local_zsk_local_tiles_inject_tiles(array $items, string $mode = 'category'): void {
    global $PAGE;

    static $rendered = [];
    $renderkey = $mode . ':' . md5(json_encode($items));
    if (empty($PAGE) || empty($items) || isset($rendered[$renderkey])) {
        return;
    }
    $rendered[$renderkey] = true;

    $json = json_encode($items, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
    if ($json === false) {
        return;
    }

    $cssurl = local_zsk_local_tiles_get_stylesheet_url();

    $PAGE->requires->js_call_amd('local_zsk_local_tiles/tile_inject', 'init', [$items, $mode, $cssurl]);
}

/**
 * Inject tiles on supported pages when enabled in settings.
 *
 * @return void
 */
function local_zsk_local_tiles_try_inject_tiles(): void {
    if (local_zsk_local_tiles_is_excluded_tile_page()) {
        return;
    }

    $context = local_zsk_local_tiles_get_tile_page_context();
    if ($context === null || !local_zsk_local_tiles_enabled_for($context)) {
        return;
    }

    switch ($context) {
        case 'category':
            $categoryid = local_zsk_local_tiles_get_request_categoryid();
            if ($categoryid > 0) {
                local_zsk_local_tiles_inject_category_tiles_for_category($categoryid);
            }
            break;

        case 'mycourses':
            $payload = \local_zsk_local_tiles\category_tiles::build_enrolled_courses_payload();
            local_zsk_local_tiles_inject_tiles($payload['items'] ?? [], 'courses');
            break;

        case 'search':
            $criteria = local_zsk_local_tiles_get_course_search_criteria();
            if ($criteria !== []) {
                $payload = \local_zsk_local_tiles\category_tiles::build_search_courses_payload($criteria);
                local_zsk_local_tiles_inject_tiles($payload['items'] ?? [], 'search');
            }
            break;

        case 'dashboard':
        case 'frontpage':
            // Startseite und Dashboard: Block block_coursetiles (kein Seitenersatz).
            break;
    }
}

/**
 * @deprecated Use local_zsk_local_tiles_try_inject_tiles()
 * @return void
 */
function local_zsk_local_tiles_try_inject_category_tiles(): void {
    local_zsk_local_tiles_try_inject_tiles();
}

/**
 * Build inline bootstrap HTML (style+script) for category tiles.
 *
 * Used by callbacks that can print HTML directly.
 *
 * @return string
 */
function local_zsk_local_tiles_category_tiles_bootstrap_html(): string {
    // Injection runs in the footer hook when PAGE context is complete.
    return '';
}

/**
 * Inject tiles once the page context is fully known (footer hook).
 *
 * @return void
 */
function local_zsk_local_tiles_inject_tiles_when_ready(): void {
    static $done = false;
    if ($done) {
        return;
    }
    $done = true;
    local_zsk_local_tiles_try_inject_tiles();
}
/**
 * Serve tile placeholder images (legacy files may still be under local_statistics).
 */
function local_zsk_local_tiles_pluginfile(
    $course,
    $cm,
    $context,
    $filearea,
    $args,
    $forcedownload,
    array $options = []
) {
    $filearea = (string) $filearea;

    if ($filearea === 'coursetile') {
        if ($context->contextlevel != CONTEXT_COURSE) {
            return false;
        }
        require_login();
    } else if ($filearea === 'cattile') {
        if ($context->contextlevel != CONTEXT_COURSECAT) {
            return false;
        }
        require_login();
    } else if ($filearea === 'tileplaceholder') {
        if ($context->contextlevel != CONTEXT_SYSTEM) {
            return false;
        }
    } else {
        return false;
    }

    if (empty($args)) {
        return false;
    }

    $filename = array_pop($args);
    $itemid = 0;
    if (!empty($args) && is_numeric($args[0])) {
        $itemid = (int) array_shift($args);
    }
    $filepath = $args ? '/' . implode('/', $args) . '/' : '/';

    $fs = get_file_storage();
    $file = $fs->get_file($context->id, 'local_zsk_local_tiles', $filearea, $itemid, $filepath, $filename);
    if (!$file || $file->is_directory()) {
        if ($filearea === 'tileplaceholder') {
            foreach (['local_tiles', 'local_tiles2', 'local_statistics'] as $component) {
                $legacy = $fs->get_file($context->id, $component, $filearea, $itemid, $filepath, $filename);
                if ($legacy && !$legacy->is_directory()) {
                    $file = $legacy;
                    break;
                }
            }
        }
        if (!$file || $file->is_directory()) {
            return false;
        }
    }

    send_stored_file($file, null, 0, $forcedownload, $options);
}

/**
 * Write default plugin config so admin/upgradesettings.php does not redirect-loop.
 *
 * @return int
 */
function local_zsk_local_tiles_seed_config_defaults(): int {
    $defaults = [
        'license_server_url' => '',
        'license_grace_days' => '7',
        'license_key' => '',
        'tiles_dashboard' => '1',
        'tiles_mycourses' => '1',
        'tiles_frontpage' => '0',
        'tiles_category' => '1',
        'tiles_showunenrolled' => '0',
        'tiles_category_maxdepth' => '2',
        'tiles_grid_columns' => '2',
        'tiles_image_height' => '175',
        'tiles_desc_lines' => '7',
        'footer_color_complete_bg' => '',
        'footer_color_complete_fg' => '',
        'footer_color_progress_bg' => '',
        'footer_color_progress_fg' => '',
        'footer_color_notstarted_bg' => '',
        'footer_color_notstarted_fg' => '',
        'footer_color_disabled_bg' => '',
        'footer_color_disabled_fg' => '',
        'footer_color_notenrolled_bg' => '',
        'footer_color_notenrolled_fg' => '',
        'footer_color_categorycount_bg' => '',
        'footer_color_categorycount_fg' => '',
        'tiles_content_source' => 'course',
    ];

    $written = 0;
    foreach ($defaults as $name => $value) {
        if (get_config('local_zsk_local_tiles', $name) === false) {
            set_config($name, $value, 'local_zsk_local_tiles');
            $written++;
        }
    }

    return $written;
}

