# Changelog

All notable changes to `block_coursetiles` are documented here.

## 1.1.3 – 2026-07-31

- Added root `LICENSE` (GPL v3) – moodle.org blocker
- Standard Moodle file headers (`@package`, `@copyright`, `@license`) on PHP and AMD sources
- Documented recommended Git repo name `moodle-block_coursetiles`

## 1.1.0 – 2025-06-13

- moodle.org submission: root `README.md` and release notes
- Stable maturity; version `2025061307`
- Plugin dependency on `local_zsk_local_tiles` (minimum version 2025061300)
- Upgrade ensures default dashboard block instance (`\local_zsk_local_tiles\dashboard_block::ensure_default_instance()`)
- Administrator documentation in `docs/de/` and `docs/en/`

## 1.0.x – 2025-06-03

- Initial release of the **Course tiles** dashboard block
- Renders course tiles in the dashboard **content** region (`/my/`)
- Uses `local_zsk_local_tiles` for data, styles, and feature flags
- Fires `local-zsk-tiles-tiles-rendered` event after render for JS refresh
- Install hook creates default block instance when appropriate
- Upgrade path from legacy `local_tiles2` dashboard block helper (if present)
