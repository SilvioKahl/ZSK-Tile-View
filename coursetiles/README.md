# Course tiles – dashboard block (`block_coursetiles`)

Moodle block that shows the **course tile view** in the **centre column** of the personal dashboard (`/my/`). This is an **add-on** for **ZSK Course tiles** (`local_zsk_local_tiles`) – install the local plugin first.

## Features

- Course tiles (image, title, short summary) in the dashboard **content** region
- Uses layout, styles, and premium options from **ZSK Course tiles**
- **Automatic placement** on install/upgrade when dashboard tiles are enabled and no instance exists yet
- Block can be moved or removed like any standard Moodle block
- German and English language packs

This block does **not** provide its own settings or license key. Appearance, contexts, and licensing are managed by `local_zsk_local_tiles`.

## Git repository naming

For a public GitHub/GitLab repo, prefer:

```text
moodle-block_coursetiles
```

(instead of e.g. `ZSK-Tile-View`). The install folder inside Moodle remains `blocks/coursetiles/`.

## Requirements

| Requirement | Version |
|-------------|---------|
| Moodle | 4.1 or later (4.4+ recommended) |
| **ZSK Course tiles** (`local_zsk_local_tiles`) | **Required** – minimum 1.0.0 (2025061300) |
| PHP | As required by your Moodle version |

**Install order:** `local_zsk_local_tiles` first, then `block_coursetiles`.

## Installation

1. Install **ZSK Course tiles** to `moodle/local/zsk_local_tiles/` and run **Site administration → Notifications**.
2. Copy the folder `coursetiles` to `moodle/blocks/coursetiles/`.
3. Run **Site administration → Notifications** again.
4. Purge caches (recommended).
5. Enable dashboard tiles under **Site administration → ZSK Course tiles → Appearance** (“Allow course tiles on dashboard (centre)”).

### ZIP layout (important)

After extracting the ZIP, the path must be:

```text
moodle/blocks/coursetiles/version.php
```

The ZIP must contain the folder `coursetiles/`, not the plugin files directly at the archive root.

## Which plugin handles which page

| Page / context | Required plugin |
|----------------|-----------------|
| **Dashboard centre** (`/my/`) | `block_coursetiles` + `local_zsk_local_tiles` |
| **My courses** (`/my/courses.php`) | `local_zsk_local_tiles` only |
| **Site home** (premium) | `local_zsk_local_tiles` only |
| **Category pages** (premium) | `local_zsk_local_tiles` only |
| **Course search** (premium) | `local_zsk_local_tiles` only |

The site home uses the **“Course tiles”** front page item from the local plugin, not this block.

## Documentation

| Document | Description |
|----------|-------------|
| [docs/en/INSTALLATION_ADMIN.md](docs/en/INSTALLATION_ADMIN.md) | Installation, relationships, troubleshooting |
| [docs/en/MOODLE_ORG.md](docs/en/MOODLE_ORG.md) | moodle.org listing text |
| [docs/de/INSTALLATION_ADMIN.md](docs/de/INSTALLATION_ADMIN.md) | German admin guide |
| [docs/de/MOODLE_ORG.md](docs/de/MOODLE_ORG.md) | German moodle.org text |
| [local/zsk_local_tiles/docs/en/INSTALLATION_ADMIN.md](../../local/zsk_local_tiles/docs/en/INSTALLATION_ADMIN.md) | Core plugin installation |

## Capabilities

Uses standard block permissions. No additional capabilities beyond Moodle’s block system.

## Uninstallation

Removing the block does not affect courses or the local tiles plugin. Users can also hide or delete the block instance from the dashboard without uninstalling.

## License

GNU GPL v3 or later.

## Author

Silvio Kuhn – [die-schulungsexperten.de](https://die-schulungsexperten.de)

## Changelog / release notes

### 1.1.0 (2025061307)

- Stable release for moodle.org submission
- Declared dependency on `local_zsk_local_tiles` (minimum 2025061300)
- Dashboard block instance ensured on upgrade via `dashboard_block::ensure_default_instance()`
- Documentation (DE/EN) under `docs/`

### 1.0.x (2025060302)

- Initial block: course tiles in dashboard centre column
- Delegates rendering to `local_zsk_local_tiles` (`tile_grid`, `category_tiles`)
- Auto-create default block instance on install
- Migration support from legacy `local_tiles2` dashboard block helper

Full history: [CHANGELOG.md](CHANGELOG.md).
