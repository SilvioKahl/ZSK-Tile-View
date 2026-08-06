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

use local_zsk_local_tiles\util\license;

defined('MOODLE_INTERNAL') || die();

/**
 * Builds tile data for course category pages.
 */
class category_tiles {

    /**
     * Build tile payload for one category.
     *
     * @param int $categoryid
     * @return array
     */
    public static function build_payload(int $categoryid, array $options = []): array {
        global $DB, $SITE;

        $category = $DB->get_record('course_categories', ['id' => $categoryid], '*', IGNORE_MISSING);
        if (!$category) {
            return ['items' => []];
        }

        $includeunenrolled = !empty($options['includeunenrolled']);

        $items = [];

        $subcategories = $DB->get_records('course_categories', ['parent' => $categoryid], 'sortorder ASC');
        foreach ($subcategories as $subcategory) {
            if (!self::can_user_view_category($subcategory)) {
                continue;
            }
            $items[] = self::build_category_tile($subcategory, $includeunenrolled);
        }

        foreach (self::get_courses_in_category($categoryid, $includeunenrolled) as $course) {
            $enrolled = self::is_user_enrolled_in_course((int) $course->id);
            $items[] = self::build_course_tile($course, $enrolled);
        }

        return [
            'categoryid' => $categoryid,
            'items' => $items,
        ];
    }

    /**
     * Enrolled courses with fields required for tile rendering (incl. summary).
     *
     * @return \stdClass[] id => course
     */
    protected static function get_enrolled_courses_with_details(): array {
        global $CFG;

        require_once($CFG->libdir . '/enrollib.php');

        return enrol_get_my_courses(
            'summary, summaryformat, enablecompletion, visible, category, fullname, sortorder'
        );
    }

    /**
     * Build tiles for enrolled courses only (e.g. My courses page).
     *
     * @return array{items: array}
     */
    public static function build_enrolled_courses_payload(): array {
        $items = [];
        foreach (self::get_enrolled_courses_with_details() as $course) {
            $items[] = self::build_course_tile($course, true);
        }

        return ['items' => $items];
    }

    /**
     * Build tiles for dashboard / site home (enrolled + optional unenrolled).
     *
     * @param bool $includeunenrolled
     * @return array{items: array}
     */
    /**
     * Courses for dashboard / site home – same visibility as Moodle course listings.
     *
     * @param bool $includeunenrolled When true, include courses from the site catalogue (not only enrolled).
     * @return array{items: array}
     */
    public static function build_frontpage_courses_payload(bool $includeunenrolled): array {
        return self::build_browsable_courses_payload($includeunenrolled);
    }

    /**
     * Courses for dashboard.
     *
     * @param bool $includeunenrolled
     * @return array{items: array}
     */
    public static function build_explorable_courses_payload(bool $includeunenrolled): array {
        return self::build_browsable_courses_payload($includeunenrolled);
    }

    /**
     * Build tiles for course search results (/course/search.php).
     *
     * @param array $searchcriteria Same keys as core course/search.php (search, blocklist, modulelist, tagid).
     * @return array{items: array}
     */
    public static function build_search_courses_payload(array $searchcriteria): array {
        global $CFG;

        if ($searchcriteria === [] || !class_exists('\core_course_category')) {
            return ['items' => []];
        }

        $displayoptions = ['sort' => ['displayname' => 1]];
        $perpage = optional_param('perpage', 0, PARAM_RAW);
        if ($perpage !== 'all') {
            $displayoptions['limit'] = ((int) $perpage <= 0) ? (int) $CFG->coursesperpage : (int) $perpage;
            $page = optional_param('page', 0, PARAM_INT);
            $displayoptions['offset'] = $displayoptions['limit'] * $page;
        }

        $courses = \core_course_category::search_courses($searchcriteria, $displayoptions);
        $items = [];
        foreach ($courses as $course) {
            $enrolled = self::is_user_enrolled_in_course((int) $course->id);
            $items[] = self::build_course_tile($course, $enrolled);
        }

        return ['items' => $items];
    }

    /**
     * Build course tiles for pages that may list the full course catalogue.
     *
     * @param bool $includeunenrolled
     * @return array{items: array}
     */
    protected static function build_browsable_courses_payload(bool $includeunenrolled): array {
        global $CFG;

        require_once($CFG->libdir . '/enrollib.php');

        $items = [];
        $seen = [];

        if ($includeunenrolled) {
            foreach (self::get_courses_visible_in_listing() as $course) {
                $courseid = (int) $course->id;
                $seen[$courseid] = true;
                $enrolled = self::is_user_enrolled_in_course($courseid);
                $items[] = self::build_course_tile($course, $enrolled);
            }
        } else {
            foreach (self::get_enrolled_courses_with_details() as $course) {
                $seen[(int) $course->id] = true;
                $items[] = self::build_course_tile($course, true);
            }
        }

        usort($items, static function(array $a, array $b): int {
            return strcasecmp($a['title'] ?? '', $b['title'] ?? '');
        });

        return ['items' => $items];
    }

    /**
     * Courses visible in Moodle's course list (same logic as front page "Kursliste").
     *
     * @return \stdClass[] id => course
     */
    protected static function get_courses_visible_in_listing(): array {
        global $DB;

        if (!class_exists('\core_course_category')) {
            return [];
        }

        $categorycourses = \core_course_category::top()->get_courses([
            'recursive' => true,
        ]);

        if (empty($categorycourses)) {
            return [];
        }

        $ids = array_keys($categorycourses);
        [$insql, $params] = $DB->get_in_or_equal($ids, SQL_PARAMS_NAMED);

        return $DB->get_records_select('course', "id {$insql}", $params, 'fullname ASC');
    }

    /**
     * @param object $course stdClass or core_course_list_element
     * @return \stdClass
     */
    protected static function normalize_course_record(object $course): \stdClass {
        if ($course instanceof \stdClass) {
            return $course;
        }

        if (class_exists('\core_course_list_element') && $course instanceof \core_course_list_element) {
            $record = new \stdClass();
            $record->id = (int) $course->id;
            $record->fullname = (string) $course->fullname;
            $record->summary = (string) ($course->summary ?? '');
            $record->summaryformat = (int) ($course->summaryformat ?? FORMAT_HTML);
            $record->category = (int) ($course->category ?? 0);
            $record->enablecompletion = (int) ($course->enablecompletion ?? 0);

            return $record;
        }

        return (object) (array) $course;
    }

    /**
     * @param object $course
     * @return array
     */
    protected static function build_course_tile(object $course, bool $enrolled = true): array {
        $course = self::normalize_course_record($course);
        $context = \context_course::instance((int) $course->id, IGNORE_MISSING);
        $summaryhtml = $course->summary ?? '';
        if ($context) {
            $summaryhtml = file_rewrite_pluginfile_urls(
                $summaryhtml,
                'pluginfile.php',
                $context->id,
                'course',
                'summary',
                0
            );
        }

        $summary = trim(strip_tags(format_text(
            $summaryhtml,
            $course->summaryformat ?? FORMAT_HTML,
            [
                'context' => $context,
                'noclean' => false,
                'filter' => true,
            ]
        )));

        $image = self::get_course_image_url($course);
        if ($image === '') {
            $image = self::extract_first_image_src($summaryhtml);
        }

        // Optional overrides from separate tile content store.
        if (\local_zsk_local_tiles\local\content_store::uses_custom_content()) {
            $custom = \local_zsk_local_tiles\local\content_store::get_course_record((int) $course->id);
            if ($custom && trim((string) $custom->summarytext) !== '') {
                $summary = \local_zsk_local_tiles\local\content_store::format_summary_plain(
                    (string) $custom->summarytext,
                    $context
                );
            }
            $customimage = \local_zsk_local_tiles\local\content_store::get_course_image_url((int) $course->id);
            if ($customimage !== '') {
                $image = $customimage;
            }
        }

        $summary = \local_zsk_local_tiles\local\content_store::truncate_summary($summary);
        $image = self::apply_course_image_with_placeholder($image);

        if (!$enrolled) {
            if (!license::can_show_tile_footer_info()) {
                $completion = ['text' => '', 'state' => 'disabled'];
            } else {
                $completion = [
                    'text' => get_string('tile_not_enrolled', 'local_zsk_local_tiles'),
                    'state' => 'notenrolled',
                ];
            }
        } else if (!license::can_show_tile_footer_info()) {
            $completion = ['text' => '', 'state' => 'disabled'];
        } else {
            $completion = self::get_course_completion_data($course);
        }

        return [
            'type' => 'course',
            'title' => format_string($course->fullname),
            'text' => $summary,
            'image' => $image,
            'url' => (new \moodle_url('/course/view.php', ['id' => (int) $course->id]))->out(false),
            'completiontext' => $completion['text'],
            'completionstate' => $completion['state'],
        ];
    }

    /**
     * @param int $courseid
     * @return bool
     */
    protected static function is_user_enrolled_in_course(int $courseid): bool {
        global $USER;

        if (!isloggedin() || isguestuser()) {
            return false;
        }

        $context = \context_course::instance($courseid, IGNORE_MISSING);
        return $context && is_enrolled($context, $USER->id);
    }

    /**
     * Whether a visible course may be shown without enrolment.
     *
     * @param \stdClass $course
     * @return bool
     */
    protected static function can_user_view_unenrolled_course(\stdClass $course): bool {
        global $USER;

        // Same check as Moodle's course catalogue (not full course access / enrolment).
        if (class_exists('\core_course_category')) {
            return \core_course_category::can_view_course_info($course, $USER);
        }

        $catcontext = \context_coursecat::instance((int) $course->category, IGNORE_MISSING);
        return $catcontext && has_capability('moodle/category:viewcourselist', $catcontext, $USER->id);
    }

    /**
     * Visible courses the current user can open but is not enrolled in.
     *
     * @return \stdClass[]
     */
    protected static function get_accessible_unenrolled_courses(): array {
        global $DB, $CFG;

        require_once($CFG->libdir . '/enrollib.php');

        $enrolledids = array_keys(enrol_get_my_courses());
        $courses = $DB->get_records_select(
            'course',
            'visible = 1 AND id <> :siteid',
            ['siteid' => SITEID],
            'fullname ASC'
        );

        $result = [];
        foreach ($courses as $course) {
            if (in_array((int) $course->id, $enrolledids, true)) {
                continue;
            }
            if (!self::can_user_view_unenrolled_course($course)) {
                continue;
            }
            $result[(int) $course->id] = $course;
        }

        return $result;
    }

    /**
     * Completion status for the current user on a course tile.
     *
     * @param \stdClass $course
     * @return array{text: string, state: string, percent: int}
     */
    protected static function get_course_completion_data(\stdClass $course): array {
        global $USER, $CFG;

        if (!isloggedin() || isguestuser() || empty($CFG->enablecompletion)) {
            return [
                'text' => get_string('tile_completion_disabled', 'local_zsk_local_tiles'),
                'state' => 'disabled',
                'percent' => 0,
            ];
        }

        require_once($CFG->libdir . '/completionlib.php');

        $completion = new \completion_info($course);
        if (!$completion->is_enabled()) {
            return [
                'text' => get_string('tile_completion_disabled', 'local_zsk_local_tiles'),
                'state' => 'disabled',
                'percent' => 0,
            ];
        }

        $percent = 0;
        if (class_exists('\core_completion\progress')) {
            $progress = \core_completion\progress::get_course_progress_percentage($course, (int) $USER->id);
            if ($progress !== false && $progress !== null) {
                $percent = max(0, min(100, (int) round($progress)));
            }
        }

        if ($percent >= 100) {
            $state = 'complete';
        } else if ($percent > 0) {
            $state = 'progress';
        } else {
            $state = 'notstarted';
        }

        return [
            'text' => get_string('tile_completion_percent', 'local_zsk_local_tiles', $percent),
            'state' => $state,
            'percent' => $percent,
        ];
    }

    /**
     * @param \stdClass $category
     * @return array
     */
    /**
     * Courses in one category (Moodle listing visibility or enrolled only).
     *
     * @param int $categoryid
     * @param bool $includeunenrolled
     * @return \stdClass[]
     */
    protected static function get_courses_in_category(int $categoryid, bool $includeunenrolled): array {
        global $DB, $CFG;

        require_once($CFG->libdir . '/enrollib.php');

        if ($includeunenrolled && class_exists('\core_course_category')) {
            try {
                $cat = \core_course_category::get($categoryid, IGNORE_MISSING);
                if (!$cat) {
                    return [];
                }
                $categorycourses = $cat->get_courses([]);
                if (empty($categorycourses)) {
                    return [];
                }
                $ids = array_keys($categorycourses);
                [$insql, $params] = $DB->get_in_or_equal($ids, SQL_PARAMS_NAMED);

                return $DB->get_records_select(
                    'course',
                    "id {$insql}",
                    $params,
                    'sortorder ASC, fullname ASC'
                );
            } catch (\Throwable $e) {
                // Fall through to enrolled-only list.
            }
        }

        $courses = [];
        foreach (self::get_enrolled_courses_with_details() as $course) {
            if ((int) $course->category === $categoryid) {
                $courses[(int) $course->id] = $course;
            }
        }

        return $courses;
    }

    /**
     * Whether the user may see a course category in listings.
     *
     * @param \stdClass $category
     * @return bool
     */
    protected static function can_user_view_category(\stdClass $category): bool {
        if (!class_exists('\core_course_category')) {
            return !empty($category->visible);
        }

        try {
            $cat = \core_course_category::get((int) $category->id, IGNORE_MISSING);

            return $cat && $cat->is_uservisible();
        } catch (\Throwable $e) {
            return false;
        }
    }

    protected static function build_category_tile(\stdClass $category, bool $includeunenrolled = false): array {
        global $DB;

        $context = \context_coursecat::instance((int) $category->id, IGNORE_MISSING);
        $descriptionhtml = $category->description ?? '';
        if ($context) {
            $descriptionhtml = file_rewrite_pluginfile_urls(
                $descriptionhtml,
                'pluginfile.php',
                $context->id,
                'coursecat',
                'description',
                0
            );
        }

        $rawdescription = format_text(
            $descriptionhtml,
            $category->descriptionformat ?? FORMAT_HTML,
            [
                'context' => $context,
                'noclean' => false,
                'filter' => true,
            ]
        );

        $text = trim(strip_tags($rawdescription));
        $image = self::extract_first_image_src($rawdescription);

        if ($image === '' && $context) {
            $image = self::get_first_image_from_filearea($context->id, 'coursecat', 'description', 0);
        }

        if (\local_zsk_local_tiles\local\content_store::uses_custom_content()) {
            $custom = \local_zsk_local_tiles\local\content_store::get_category_record((int) $category->id);
            if ($custom && trim((string) $custom->summarytext) !== '') {
                $text = \local_zsk_local_tiles\local\content_store::format_summary_plain(
                    (string) $custom->summarytext,
                    $context
                );
            }
            $customimage = \local_zsk_local_tiles\local\content_store::get_category_image_url((int) $category->id);
            if ($customimage !== '') {
                $image = $customimage;
            }
        }

        $text = \local_zsk_local_tiles\local\content_store::truncate_summary($text);

        $coursecount = count(self::get_courses_in_category((int) $category->id, $includeunenrolled));

        $countlabel = license::can_show_tile_footer_info()
            ? get_string('tile_coursecount', 'local_zsk_local_tiles', $coursecount)
            : '';

        return [
            'type' => 'category',
            'title' => format_string($category->name),
            'text' => $text,
            'categorycounttext' => $countlabel,
            'image' => self::normalize_image_url($image),
            'url' => (new \moodle_url('/course/index.php', ['categoryid' => (int) $category->id]))->out(false),
        ];
    }

    /**
     * Get course overview image URL via Moodle core API (Kursbild).
     *
     * @param \stdClass $course Full course record.
     * @return string
     */
    protected static function get_course_image_url(\stdClass $course): string {
        if (class_exists('\core_course_list_element') && method_exists('\core_course_list_element', 'get_course_overviewfiles')) {
            $list = new \core_course_list_element($course);
            foreach ($list->get_course_overviewfiles() as $file) {
                if ($file->is_valid_image()) {
                    return self::stored_file_to_image_url($file);
                }
            }
        }

        $context = \context_course::instance((int) $course->id, IGNORE_MISSING);
        if (!$context) {
            return '';
        }

        return self::get_first_image_from_filearea($context->id, 'course', 'overviewfiles', 0);
    }

    /**
     * Build pluginfile URL from stored_file (compatible with all Moodle 4.x versions).
     *
     * @param \stored_file $file
     * @return string
     */
    protected static function stored_file_to_image_url(\stored_file $file): string {
        global $CFG;

        // Course overview images: Moodle uses /overviewfiles/FILENAME (no /0/ segment).
        if ($file->get_component() === 'course' && $file->get_filearea() === 'overviewfiles') {
            $path = '/pluginfile.php/' . $file->get_contextid() . '/course/overviewfiles/' . $file->get_filename();
            return rtrim($CFG->wwwroot, '/') . $path;
        }

        // Category description images: same pattern without itemid in the URL path.
        if ($file->get_component() === 'coursecat' && $file->get_filearea() === 'description') {
            $path = '/pluginfile.php/' . $file->get_contextid() . '/coursecat/description/' . $file->get_filename();
            return rtrim($CFG->wwwroot, '/') . $path;
        }

        $url = \moodle_url::make_pluginfile_url(
            $file->get_contextid(),
            $file->get_component(),
            $file->get_filearea(),
            $file->get_itemid(),
            $file->get_filepath(),
            $file->get_filename()
        );

        $urlstring = self::normalize_image_url($url->out(true));

        // Cache-bust placeholder images when the file is replaced in settings.
        if (($file->get_component() === 'local_zsk_local_tiles' || $file->get_component() === 'local_statistics') && $file->get_filearea() === 'tileplaceholder') {
            $urlstring .= (strpos($urlstring, '?') === false ? '?' : '&') . 'rev=' . $file->get_timemodified();
        }

        return $urlstring;
    }

    /**
     * Ensure image URLs are absolute and browser-loadable.
     *
     * @param string $url
     * @return string
     */
    protected static function normalize_image_url(string $url): string {
        global $CFG;

        $url = trim($url);
        if ($url === '') {
            return '';
        }

        if (!preg_match('/^https?:\/\//i', $url) && isset($url[0]) && $url[0] === '/') {
            $url = rtrim($CFG->wwwroot, '/') . $url;
        }

        // Itemid 0 must not appear in these pluginfile paths (relative or absolute URLs).
        $url = str_replace('/overviewfiles/0/', '/overviewfiles/', $url);
        $url = str_replace('/coursecat/description/0/', '/coursecat/description/', $url);
        $url = str_replace('/tileplaceholder/0/', '/tileplaceholder/', $url);

        return $url;
    }

    /**
     * Placeholder image URL from plugin settings (for courses without overview image).
     *
     * @return string
     */
    public static function get_placeholder_image_url(): string {
        $fs = get_file_storage();
        $context = \context_system::instance();
        $files = $fs->get_area_files(
            $context->id,
            'local_zsk_local_tiles',
            'tileplaceholder',
            0,
            'timemodified DESC',
            false
        );

        $latest = null;
        foreach ($files as $file) {
            if ($file->is_directory() || !$file->is_valid_image()) {
                continue;
            }
            if ($latest === null || $file->get_timemodified() > $latest->get_timemodified()) {
                $latest = $file;
            }
        }

        if ($latest !== null) {
            return self::stored_file_to_image_url($latest);
        }

        $legacyfiles = $fs->get_area_files(
            $context->id,
            'local_statistics',
            'tileplaceholder',
            0,
            'timemodified DESC',
            false
        );
        foreach ($legacyfiles as $file) {
            if ($file->is_directory() || !$file->is_valid_image()) {
                continue;
            }
            return self::stored_file_to_image_url($file);
        }

        return '';
    }

    /**
     * @param string $image
     * @return string
     */
    protected static function apply_course_image_with_placeholder(string $image): string {
        $image = self::normalize_image_url($image);
        if ($image !== '') {
            return $image;
        }

        if (!license::can_use_custom_placeholder()) {
            return '';
        }

        return self::get_placeholder_image_url();
    }

    /**
     * @param int $contextid
     * @param string $component
     * @param string $filearea
     * @param int $itemid
     * @return string
     */
    protected static function get_first_image_from_filearea(
        int $contextid,
        string $component,
        string $filearea,
        int $itemid
    ): string {
        $fs = get_file_storage();
        $files = $fs->get_area_files($contextid, $component, $filearea, $itemid, 'itemid', false);

        foreach ($files as $file) {
            if ($file->is_directory()) {
                continue;
            }
            if (!$file->is_valid_image()) {
                continue;
            }

            return self::stored_file_to_image_url($file);
        }

        return '';
    }

    /**
     * @param string $html
     * @return string
     */
    protected static function extract_first_image_src(string $html): string {
        if ($html === '') {
            return '';
        }

        if (!preg_match('/<img[^>]+src=["\']([^"\']+)["\']/i', $html, $matches)) {
            return '';
        }

        return trim($matches[1] ?? '');
    }
}
