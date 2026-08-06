# Settings – ZSK course tiles

**Menu:** Site administration → **ZSK course tiles**

---

## License (`local_zsk_local_tiles_license`)

| Setting | Config key | Description |
|---------|------------|-------------|
| License server URL | `license_server_url` | Verify endpoint |
| Offline grace (days) | `license_grace_days` | Premium when server unreachable (default: 7) |
| Premium license key | `license_key` | Prefix `ZSK-KA-` |
| License status | *(display)* | Free, Premium, Grace, error |

---

## Tile content: two options

Under **Course tiles** you will find:

**“Take tile images and texts from”** (`tiles_content_source`)

| Option | Meaning |
|--------|---------|
| **Course settings** (default) | Image and text come from normal Moodle course settings (course image / summary) or from the category description. |
| **Separate upload** | Image and preview text are maintained **independently** of the course form on dedicated maintenance pages. |

Preview text on the tile is limited to **300 characters**.

### When “Course settings” is selected

- Course image: Course → Settings → Course image (`overviewfiles`)
- Text: course summary
- Categories: category description / embedded image

Teachers maintain this as usual in the course.

### When “Separate upload” is selected

1. Under **Site administration → ZSK course tiles → Users allowed to maintain tile content**, add authorised users (allowlist).
2. Only these users see **“Maintain tile content”** in the navigation (site admins do **not** get it automatically).
3. Via the maintenance UI:
   - **Maintain course tiles** – pick a category, then set image and text for multiple courses
   - **Maintain category tiles** – image and text for categories
4. Multilingual texts can use the Moodle multi-language filter (e.g. `{mlang de}…{mlang}{mlang en}…{mlang}`).

**Note:** While the switch is set to “Course settings”, separately uploaded content is **not** used on tiles (you can still prepare content on the maintenance pages).

---

## Course tiles (`local_zsk_local_tiles_config`)

### Tile content

| Setting | Config key | Description |
|---------|------------|-------------|
| Take tile images and texts from | `tiles_content_source` | `course` = course settings, `custom` = separate upload |

### Free tier

| Setting | Config key | Default |
|---------|------------|---------|
| Allow course tiles on Dashboard (centre) | `tiles_dashboard` | On |
| Show tiles on My courses | `tiles_mycourses` | On |

### Premium

| Setting | Config key | Description |
|---------|------------|-------------|
| Allow course tiles on site home (centre) | `tiles_frontpage` | With front page item “Course tiles” |
| Show tiles on category pages | `tiles_category` | Catalogue + search |
| Show courses without enrolment | `tiles_showunenrolled` | Footer “not yet enrolled” |
| Tile view up to level | `tiles_category_maxdepth` | Category depth (0 = unlimited) |
| Placeholder image | `tiles_placeholderimage` | For courses without image |
| Columns per row | `tiles_grid_columns` | 1–3 |
| Image height (px) | `tiles_image_height` | Default 175 |
| Description lines | `tiles_desc_lines` | Default 7 |
| Footer colours | `footer_color_*` | Background/text per status |

---

## Users allowed to maintain tile content

**Site administration → ZSK course tiles → Users allowed to maintain tile content**

Only listed users may use **Maintain tile content** and see the nav entry. Independent of admin status.

---

## Front page layout (Moodle core)

**Site administration → Front page → Front page settings**

Add **Course tiles** (slot `8`) to `frontpageloggedin` / `frontpage`.

---

## Cron

Daily license check: task `verify_license_task` (around 03:45).
