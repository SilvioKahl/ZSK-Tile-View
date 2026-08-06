# Installation guide for administrators

**Plugin:** `local_zsk_local_tiles` (ZSK course tiles)  
**Version:** 1.1.5

---

## Purpose

Replaces or supplements Moodle standard lists with a **tile grid** (image, title, description, optional footer with progress).

| Context | Page | Required plugin |
|---------|------|-----------------|
| Dashboard (centre) | `/my/` | **this plugin +** [`block_coursetiles`](../../../../blocks/coursetiles/docs/en/INSTALLATION_ADMIN.md) |
| My courses | `/my/courses.php` | this plugin only |
| Site home | when logged in (premium) | this plugin only |
| Categories / course search | `/course/index.php`, `/course/search.php` (premium) | this plugin only |

**Important:** For tiles in the **dashboard centre**, this local plugin alone is **not** enough. You also need the separate block plugin **`block_coursetiles`** (separate moodle.org listing, folder `blocks/coursetiles/`).

---

## Requirements

- Moodle **4.1+** (4.4+ recommended)
- Target path: `moodle/local/zsk_local_tiles/`
- **For dashboard tiles:** additionally install `block_coursetiles` to `blocks/coursetiles/` (see [block installation guide](../../../../blocks/coursetiles/docs/en/INSTALLATION_ADMIN.md))
- Optional: `local_zsk_frontpage_elements` for site home picker
- Do **not** run in parallel with legacy `local_tiles2`

---

## Installation

### Step 1: Local plugin (this plugin)

1. Unpack ZIP → copy `zsk_local_tiles` to `local/zsk_local_tiles/`
2. **Site administration → Notifications**
3. **Purge all caches**

### Step 2: Block plugin (for dashboard centre)

If you want tiles on the **dashboard** (`/my/`):

1. Unpack `coursetiles` ZIP to `blocks/coursetiles/`
2. Run **Site administration → Notifications** again
3. Full guide: [block_coursetiles installation guide](../../../../blocks/coursetiles/docs/en/INSTALLATION_ADMIN.md)

On moodle.org, the local and block plugins are **two separate listings** – both must be installed individually.

---

## Basic setup (free tier)

**Site administration → ZSK course tiles → Course tiles**

| Setting | Recommendation |
|---------|----------------|
| Allow course tiles on Dashboard (centre) | Yes |
| Show tiles on My courses | Yes |

Once **`block_coursetiles` is installed**, the block instance is created automatically in the dashboard centre when needed. Without the block plugin, the dashboard shows no tiles – **My courses** still works.

Details: [SETTINGS.md](SETTINGS.md)

---

## Tile images and texts (two options)

**Site administration → ZSK course tiles → Course tiles**

Setting **“Take tile images and texts from”**:

| Option | Typical use |
|--------|-------------|
| **Course settings** (default) | Teachers maintain course image and summary in the course form. |
| **Separate upload** | Dedicated maintenance pages; allowlist under **Users allowed to maintain tile content**; nav **“Maintain tile content”** only for authorised users. |

Full detail: [SETTINGS.md](SETTINGS.md) · Teachers: [TEACHER_GUIDE.md](TEACHER_GUIDE.md)

---

## Premium license (optional)

**Site administration → ZSK course tiles → License**

1. Save **License server URL** (e.g. `…/api/v1/verify.php`)
2. Enter key with prefix **`ZSK-KA-`**
3. Check status (Free / Premium / Grace)

Premium enables site home, categories, layout options, and footer details.

---

## Site home (premium)

1. Enable **Allow course tiles on site home (centre)**
2. **Site administration → Front page → Front page settings**
3. Add **Course tiles** to **Front page items when logged in** and set order

---

## Checklist

- [ ] Local plugin installed, upgrade completed without errors
- [ ] `block_coursetiles` installed (if dashboard tiles are required)
- [ ] Dashboard and My courses show tiles
- [ ] Optional: tile content source chosen (course settings vs. separate upload)
- [ ] Optional: allowlist for “Maintain tile content” configured
- [ ] Optional: premium license active
- [ ] Optional: site home configured

---

## Further help

- [Course tiles block – installation guide](../../../../blocks/coursetiles/docs/en/INSTALLATION_ADMIN.md)
- [SETTINGS.md](SETTINGS.md)
- [../MIGRATION.md](../MIGRATION.md)

*Version: ZSK course tiles 1.0.10*
