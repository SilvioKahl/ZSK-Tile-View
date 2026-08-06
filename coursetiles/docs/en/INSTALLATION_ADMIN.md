# Installation guide for administrators

**Plugin:** `block_coursetiles` (Course tiles – dashboard block)  
**Audience:** System administrators and Moodle managers  
**Version:** 1.1.0

**Related core plugin:** [`local_zsk_local_tiles`](../../../../local/zsk_local_tiles/docs/en/INSTALLATION_ADMIN.md) (ZSK Course tiles)

---

## What this plugin is – and what it is not

The **Course tiles** block is **not** a standalone tile product. It is the **dashboard add-on** for **ZSK Course tiles** (`local_zsk_local_tiles`).

| | `local_zsk_local_tiles` (core) | `block_coursetiles` (this plugin) |
|---|---|---|
| **Role** | Tile logic, styles, license, settings | Moodle block for the **dashboard centre** |
| **Folder** | `local/zsk_local_tiles/` | `blocks/coursetiles/` |
| **moodle.org** | Separate listing (core product) | Separate listing (add-on) |
| **Settings** | Yes (appearance, license, contexts) | No (no own configuration) |
| **License key** | Yes (`ZSK-KA-…`) | No (uses the core plugin) |

**Rule of thumb:** Everything that controls how tiles *look*, *filter* and *unlock* is managed by the **local plugin**. This block only displays tiles as a **standard Moodle block in the centre of the dashboard** (`/my/`).

---

## Which plugin handles which page

| Page / context | URL (typical) | Required plugin |
|----------------|---------------|-----------------|
| **Dashboard (centre)** | `/my/` | **`block_coursetiles`** + `local_zsk_local_tiles` |
| **My courses** | `/my/courses.php` | `local_zsk_local_tiles` only |
| **Site home** (premium) | `/` when logged in | `local_zsk_local_tiles` only (“Course tiles” front page item) |
| **Category pages** (premium) | `/course/index.php` | `local_zsk_local_tiles` only |
| **Course search** (premium) | `/course/search.php` | `local_zsk_local_tiles` only |

```text
ZSK Course tiles (local_zsk_local_tiles)
├── My courses, site home, categories, search  → handled by the local plugin
└── Dashboard centre (/my/)                    → via block_coursetiles
```

**Important:** The **site home** does **not** use a sidebar block. There you select the **“Course tiles”** item in front page settings – that comes from the local plugin, not from this block.

---

## Requirements

| Requirement | Details |
|-------------|---------|
| Moodle | **4.1+** (4.4+ recommended) |
| Core plugin | **`local_zsk_local_tiles`** must be installed **first** (minimum version 1.0.0) |
| Install order | 1. Local plugin → 2. Block |
| Target folder | `moodle/blocks/coursetiles/` |

Without `local_zsk_local_tiles`, this block **cannot be installed** (dependency in `version.php`).

---

## Installation

### Step 1: Install ZSK Course tiles

If not already done:

1. Unzip `zsk_local_tiles` to `local/zsk_local_tiles/`
2. Run **Site administration → Notifications**
3. Complete basic setup in the local plugin

Details: [ZSK Course tiles installation guide](../../../../local/zsk_local_tiles/docs/en/INSTALLATION_ADMIN.md)

### Step 2: Install the Course tiles block

1. Unzip `coursetiles` to `blocks/coursetiles/`
2. Run **Site administration → Notifications** again
3. **Purge caches** (recommended)

### Step 3: Enable dashboard tiles

**Site administration → ZSK Course tiles → Appearance**

| Setting | Recommendation |
|---------|----------------|
| Allow course tiles on dashboard (centre) | **Yes** |

On install or upgrade, the system **automatically creates** the block in the dashboard centre (*content* region) when enabled and not yet present.

### Step 4: Verify

1. Log in as a user enrolled in courses
2. Open the **dashboard** (`/my/`)
3. **Course tiles** should appear in the **centre column** (not only in the sidebar)

---

## How the two plugins work together

### What the block does

- Renders the dashboard course list as **tiles** (image, title, summary)
- Uses **data, layout and premium features** from `local_zsk_local_tiles`
- Is only allowed on **`my-index`** (personal dashboard)
- Allows **only one instance** per dashboard

### What the local plugin does for the block

- Enable/disable dashboard tiles
- Tile design (columns, colours, footer info – premium)
- License check (free/premium)
- Automatic block instance creation (`dashboard_block.php`)

### Customising the block manually

The block behaves like any other Moodle block:

- Move: **Customise dashboard** (edit mode)
- Remove: delete the block (dashboard tiles will disappear)
- Re-add: drag the **“Course tiles”** block into the dashboard centre

Without the corresponding setting in the local plugin, the block stays **empty** even if visible.

---

## Frequently asked questions

### Is the local plugin enough on its own?

**For My courses, site home, category pages and course search: yes.**  
**For tiles in the dashboard centre: no** – this block is required.

### Does the block need its own license?

No. Premium features are unlocked via the license key in the **local plugin** (`ZSK-KA-…`).

### Why two moodle.org listings?

moodle.org allows exactly **one** ZIP with **one** plugin folder per listing. The local and block plugins must therefore be published separately. For manual installation, both folders can also be copied from a combined package into the Moodle directory.

### The block is present but empty

Check:

1. Is **“Allow course tiles on dashboard (centre)”** enabled?
2. Is the user logged in (not a guest)?
3. Does the user have visible courses?
4. Is `local_zsk_local_tiles` installed and up to date?

---

## Checklist

- [ ] `local_zsk_local_tiles` installed and notifications run
- [ ] `block_coursetiles` installed and notifications run
- [ ] Dashboard tiles enabled in settings
- [ ] Dashboard shows tiles in the centre
- [ ] Optional: premium license configured in the local plugin

---

## Further reading

| Topic | Document |
|-------|----------|
| Core plugin installation | [local/zsk_local_tiles/docs/en/INSTALLATION_ADMIN.md](../../../../local/zsk_local_tiles/docs/en/INSTALLATION_ADMIN.md) |
| Settings (appearance, license) | [local/zsk_local_tiles/docs/en/SETTINGS.md](../../../../local/zsk_local_tiles/docs/en/SETTINGS.md) |
| moodle.org texts for this block | [MOODLE_ORG.md](MOODLE_ORG.md) |
| ZSK Course tiles product overview | [local/zsk_local_tiles/docs/en/PRODUCT_DESCRIPTION.md](../../../../local/zsk_local_tiles/docs/en/PRODUCT_DESCRIPTION.md) |

*Version: block_coursetiles 1.1.0 · local_zsk_local_tiles 1.0.10*
