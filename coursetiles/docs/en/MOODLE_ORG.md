# moodle.org – plugin listing (English)

Template for [moodle.org/plugins](https://moodle.org/plugins/).  
Plugin: `block_coursetiles` · Release 1.1.0 · Moodle 4.1+

**Note:** This is an **add-on** for **ZSK Course tiles** (`local_zsk_local_tiles`). Both plugins require **separate** moodle.org listings.

---

## Title (plugin name)

**ZSK Course tiles (dashboard block)**

*(Shorter alternative: “Course tiles (ZSK dashboard block)” – the ZSK reference should be visible in the title.)*

---

## Short description (Summary)

Dashboard block for the course tile view in the **centre column** of the personal dashboard (`/my/`). **Requires the local plugin ZSK Course tiles** (`local_zsk_local_tiles`) – install that plugin first.

---

## Description

The **ZSK Course tiles (dashboard block)** embeds the tile view from **ZSK Course tiles** as a standard Moodle block in the **centre of the dashboard** (*content* region on `/my/`).

### Important: two plugins, one solution

| Plugin | Role |
|--------|------|
| **ZSK Course tiles** (`local_zsk_local_tiles`) | Core: tile logic, appearance, license, My courses, site home, category pages |
| **This block** (`block_coursetiles`) | Dashboard centre only – not usable without the local plugin |

**Install order:** `local_zsk_local_tiles` first, then this block.

### Features

- Shows courses as **tiles** in the dashboard centre (image, title, short summary)
- Uses layout, styles and premium features from **ZSK Course tiles**
- **Automatically** placed on the dashboard on install/upgrade when enabled and not yet present
- Block can be moved or removed like any other Moodle block

### Not included (provided by the local plugin)

- **My courses** (`/my/courses.php`)
- **Site home** (“Course tiles” item in front page settings)
- **Category pages** and **course search**
- Own settings or license key

### Dependencies

| Plugin | Required | Minimum version |
|--------|----------|-----------------|
| ZSK Course tiles (`local_zsk_local_tiles`) | **Yes** | 1.0.0 (2025061300) |
| Moodle | Yes | 4.1+ |

### Installation (brief)

1. Install **ZSK Course tiles** → run notifications
2. Install this block to `blocks/coursetiles/` → run notifications again
3. In **ZSK Course tiles → Appearance:** enable “Allow course tiles on dashboard (centre)”
4. Check the dashboard

Full guide: `blocks/coursetiles/docs/en/INSTALLATION_ADMIN.md`

### Related plugins (moodle.org field)

Requires the local plugin **ZSK Course tiles** (`local_zsk_local_tiles`). Install it **first**. For My courses, site home and category pages, the local plugin alone is sufficient; for tiles in the dashboard centre, this block is required.

### Tags (suggested)

`block`, `course tiles`, `dashboard`, `tile view`, `zsk`
