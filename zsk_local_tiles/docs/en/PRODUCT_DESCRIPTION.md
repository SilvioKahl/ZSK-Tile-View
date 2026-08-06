# ZSK course tiles – product description for Moodle operators

**ZSK course tiles** (`local_zsk_local_tiles`) replaces Moodle’s **standard course lists** with a modern **tile grid** showing course image, title, short summary, and optional footer details — on the dashboard, My courses, site home, category pages, and course search.

| | |
|---|---|
| **Plugin** | `local_zsk_local_tiles` |
| **Moodle** | 4.1 or later (4.4+ recommended) |
| **Optional add-on** | **Course tiles** block (`block_coursetiles`) for dashboard centre — separate moodle.org listing |
| **License** | Freemium: dashboard + My courses free; premium via key `ZSK-KA-` |
| **Languages** | German and English (UI and documentation) |

---

## What problems does the plugin solve?

Moodle shows courses as **text lists** by default — functional but not very visual and often hard to scan on mobile. Operators and users commonly face:

### Unattractive, hard-to-scan course overview

Long lists with title and link only make orientation difficult with many concurrent courses or nested categories.

### Missing course images and context in the overview

The default list barely uses course images and summaries. Users cannot recognise courses at a glance.

### No progress or enrolment status at a glance

Learners must open each course to see completion or enrolment — the overview does not surface this.

### Site home and dashboard feel outdated

Institutions want a **modern site home** with course tiles in the centre — not only the built-in list or a sidebar block without consistent styling.

### Course catalogue and search stay list-based

On `/course/index.php` and course search, tables and lists dominate; categories with descriptions and course counts are hard to present visually.

### Theme customisation is costly to maintain

Without a plugin, tile layouts are often built with **custom CSS**, child themes, or external development — re-tested on every Moodle upgrade.

### Inconsistent experience per page

Dashboard, My courses, and the catalogue may look different. One plugin provides a **unified appearance**.

---

## Benefits

### Visual course tiles instead of lists

Each tile shows **course image** (or placeholder), **title**, and **short description** in a responsive grid aligned with Boost themes.

### Two ways for image and text

| Source | Description |
|--------|-------------|
| **Course settings** | Classic: course image and summary in the course form (or category description). |
| **Separate upload** | Dedicated maintenance UI with allowlist; nav “Maintain tile content” only for authorised users — independent of the course form. |

The switch is in the plugin settings (`tiles_content_source`). Details: [SETTINGS.md](SETTINGS.md).

### Multiple contexts from one source

| Context | Page | Availability |
|---------|------|----------------|
| **Dashboard** | `/my/` (centre) | Free |
| **My courses** | `/my/courses.php` | Free |
| **Site home** | when logged in | Premium |
| **Categories** | `/course/index.php` | Premium |
| **Course search** | `/course/search.php` | Premium |

### Freemium with clear value

**Free:** dashboard and My courses, standard layout (2 columns, default colours, no footer info).

**Premium** (`ZSK-KA-`): all views, custom layout (columns, image height, description lines), footer colours, placeholder image, footer info (progress, enrolment, category course count).

### Footer information (premium)

At the bottom of a tile, e.g.:

- learning progress / completion status
- “not yet enrolled” in the catalogue
- course count on category tiles

### Site home integration

**Course tiles** item in Moodle front page settings — orderable alongside other ZSK elements (events, custom site home blocks).

### Automatic integration without manual HTML

On category pages and My courses the plugin replaces the standard list via **hooks/injection** — no course template edits required.

### License server with offline grace

Premium via central ZSK license server; short outages keep premium active for a configurable grace period.

### Migration from legacy plugins

Documented upgrade path from `local_tiles2` and older tile solutions ([MIGRATION.md](../MIGRATION.md)).

---

## Feature overview

| Area | What you get |
|------|----------------|
| **Dashboard** | “Course tiles” block in centre (auto-created when enabled) |
| **My courses** | Tile grid of enrolled courses only |
| **Site home** | Course tiles as layout element (premium) |
| **Catalogue** | Tiles in categories, depth limit optional (premium) |
| **Layout** | Columns, image height, description lines (premium) |
| **Design** | Footer colours per status, placeholder image (premium) |
| **License** | Free / premium / grace, CLI test `test_license.php` |
| **Permissions** | Uses Moodle core rights (enrolment, catalogue visibility) |

---

## Who is it for?

- **Universities and training providers** with many parallel courses and categories
- **Companies** wanting a clear learning catalogue on dashboard and site home
- **Moodle partners** offering a modern course overview without a theme project

---

## What it is not

- **Not** a replacement for course management or enrolment
- **Not** course content — only **presentation** of course lists
- Block `block_coursetiles` is **optional** (recommended for dashboard); core logic is in the local plugin
- Do not run in parallel with legacy `local_tiles2`

---

## Technical requirements (brief)

- Moodle **4.1+**
- Optional: `local_zsk_frontpage_elements` for extended site home picker integration
- Premium: reachable ZSK license server, key prefix `ZSK-KA-`

Setup: [INSTALLATION_ADMIN.md](INSTALLATION_ADMIN.md) · Settings: [SETTINGS.md](SETTINGS.md)

---

*Silvio Kuhn – die-schulungsexperten.de · Version: plugin 1.0.10*
