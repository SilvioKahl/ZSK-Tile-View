<?php
// This file is part of Moodle - http://moodle.org/
/**
 * Custom tile content storage (separate upload mode).
 *
 * @package    local_zsk_local_tiles
 * @copyright  2026 Silvio Kuhn
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_zsk_local_tiles\local;

defined('MOODLE_INTERNAL') || die();

/**
 * Read/write course and category tile overrides.
 */
class content_store {

    public const TABLE_COURSE = 'local_zsk_tiles_course';
    public const TABLE_CATEGORY = 'local_zsk_tiles_category';
    public const FILEAREA_COURSE = 'coursetile';
    public const FILEAREA_CATEGORY = 'cattile';
    public const SOURCE_COURSE = 'course';
    public const SOURCE_CUSTOM = 'custom';
    public const SUMMARY_MAX_CHARS = 300;

    /**
     * @return string
     */
    public static function get_content_source(): string {
        $source = (string) get_config('local_zsk_local_tiles', 'tiles_content_source');
        return $source === self::SOURCE_CUSTOM ? self::SOURCE_CUSTOM : self::SOURCE_COURSE;
    }

    /**
     * @return bool
     */
    public static function uses_custom_content(): bool {
        return self::get_content_source() === self::SOURCE_CUSTOM;
    }

    /**
     * Truncate plain preview text to SUMMARY_MAX_CHARS.
     *
     * @param string $text
     * @return string
     */
    public static function truncate_summary(string $text): string {
        $text = trim($text);
        if ($text === '') {
            return '';
        }
        if (\core_text::strlen($text) <= self::SUMMARY_MAX_CHARS) {
            return $text;
        }
        return rtrim(\core_text::substr($text, 0, self::SUMMARY_MAX_CHARS - 1)) . '…';
    }

    /**
     * @param int $courseid
     * @return \stdClass|null
     */
    public static function get_course_record(int $courseid): ?\stdClass {
        global $DB;
        if ($courseid <= 1) {
            return null;
        }
        $record = $DB->get_record(self::TABLE_COURSE, ['courseid' => $courseid]);
        return $record ?: null;
    }

    /**
     * @param int $categoryid
     * @return \stdClass|null
     */
    public static function get_category_record(int $categoryid): ?\stdClass {
        global $DB;
        if ($categoryid <= 0) {
            return null;
        }
        $record = $DB->get_record(self::TABLE_CATEGORY, ['categoryid' => $categoryid]);
        return $record ?: null;
    }

    /**
     * @param int $courseid
     * @param string $summarytext
     * @param int $draftitemid
     * @return void
     */
    public static function save_course(int $courseid, string $summarytext, int $draftitemid): void {
        global $DB, $USER;

        $context = \context_course::instance($courseid);
        $now = time();
        $existing = self::get_course_record($courseid);
        $record = (object) [
            'courseid' => $courseid,
            'summarytext' => $summarytext,
            'timemodified' => $now,
            'usermodified' => (int) $USER->id,
        ];
        if ($existing) {
            $record->id = $existing->id;
            $DB->update_record(self::TABLE_COURSE, $record);
        } else {
            $DB->insert_record(self::TABLE_COURSE, $record);
        }

        file_save_draft_area_files(
            $draftitemid,
            $context->id,
            'local_zsk_local_tiles',
            self::FILEAREA_COURSE,
            0,
            self::file_options()
        );
    }

    /**
     * @param int $categoryid
     * @param string $summarytext
     * @param int $draftitemid
     * @return void
     */
    public static function save_category(int $categoryid, string $summarytext, int $draftitemid): void {
        global $DB, $USER;

        $context = \context_coursecat::instance($categoryid);
        $now = time();
        $existing = self::get_category_record($categoryid);
        $record = (object) [
            'categoryid' => $categoryid,
            'summarytext' => $summarytext,
            'timemodified' => $now,
            'usermodified' => (int) $USER->id,
        ];
        if ($existing) {
            $record->id = $existing->id;
            $DB->update_record(self::TABLE_CATEGORY, $record);
        } else {
            $DB->insert_record(self::TABLE_CATEGORY, $record);
        }

        file_save_draft_area_files(
            $draftitemid,
            $context->id,
            'local_zsk_local_tiles',
            self::FILEAREA_CATEGORY,
            0,
            self::file_options()
        );
    }

    /**
     * @return array
     */
    public static function file_options(): array {
        global $CFG;
        return [
            'subdirs' => 0,
            'maxfiles' => 1,
            'maxbytes' => $CFG->maxbytes,
            'accepted_types' => ['image'],
        ];
    }

    /**
     * @param int $courseid
     * @return string
     */
    public static function get_course_image_url(int $courseid): string {
        $context = \context_course::instance($courseid, IGNORE_MISSING);
        if (!$context) {
            return '';
        }
        return self::first_image_url($context->id, self::FILEAREA_COURSE);
    }

    /**
     * @param int $categoryid
     * @return string
     */
    public static function get_category_image_url(int $categoryid): string {
        $context = \context_coursecat::instance($categoryid, IGNORE_MISSING);
        if (!$context) {
            return '';
        }
        return self::first_image_url($context->id, self::FILEAREA_CATEGORY);
    }

    /**
     * @param int $contextid
     * @param string $filearea
     * @return string
     */
    protected static function first_image_url(int $contextid, string $filearea): string {
        $fs = get_file_storage();
        $files = $fs->get_area_files($contextid, 'local_zsk_local_tiles', $filearea, 0, 'itemid, filepath, filename', false);
        foreach ($files as $file) {
            if ($file->is_valid_image()) {
                $url = \moodle_url::make_pluginfile_url(
                    $file->get_contextid(),
                    $file->get_component(),
                    $file->get_filearea(),
                    $file->get_itemid(),
                    $file->get_filepath(),
                    $file->get_filename()
                );
                return $url->out(false);
            }
        }
        return '';
    }

    /**
     * Prepare draft area for a course tile image.
     *
     * @param int $courseid
     * @return int draft item id
     */
    public static function prepare_course_draft(int $courseid): int {
        $context = \context_course::instance($courseid);
        $draftid = 0;
        file_prepare_draft_area(
            $draftid,
            $context->id,
            'local_zsk_local_tiles',
            self::FILEAREA_COURSE,
            0,
            self::file_options()
        );
        return $draftid;
    }

    /**
     * @param int $categoryid
     * @return int
     */
    public static function prepare_category_draft(int $categoryid): int {
        $context = \context_coursecat::instance($categoryid);
        $draftid = 0;
        file_prepare_draft_area(
            $draftid,
            $context->id,
            'local_zsk_local_tiles',
            self::FILEAREA_CATEGORY,
            0,
            self::file_options()
        );
        return $draftid;
    }

    /**
     * Format stored summary for display (multilang filters supported).
     *
     * @param string $raw
     * @param \context|null $context
     * @return string plain text, truncated
     */
    public static function format_summary_plain(string $raw, ?\context $context = null): string {
        $html = format_text($raw, FORMAT_HTML, [
            'context' => $context ?? \context_system::instance(),
            'noclean' => false,
            'filter' => true,
        ]);
        return self::truncate_summary(trim(strip_tags($html)));
    }
}
