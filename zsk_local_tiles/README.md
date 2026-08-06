# ZSK Kachelansicht (`local_zsk_local_tiles`)

Moodle-Local-Plugin für die Kurs-Kachelansicht (Freemium).

- **Komponente:** `local_zsk_local_tiles`
- **Ordner:** `local/zsk_local_tiles/`
- **Anzeigename:** ZSK Kachelansicht

## Freemium

| Kostenlos | Premium |
|-----------|---------|
| Dashboard als Kacheln | Alle Ansichten (Startseite, Dashboard, Meine Kurse, Kursbereiche, Kurssuche) |
| Meine Kurse als Kacheln | Kachelgröße (Spalten, Bildhöhe, Beschreibungszeilen) |
| Standard-Layout (2 Spalten, Standard-Farben) | Eigene Footer-Farben |
| Keine Footer-Infos | Platzhalterbild, Fortschritt, Einschreibungsstatus, Kursanzahl |

**Produktbeschreibung:** [docs/de/PRODUKTBESCHREIBUNG.md](docs/de/PRODUKTBESCHREIBUNG.md) · [docs/en/PRODUCT_DESCRIPTION.md](docs/en/PRODUCT_DESCRIPTION.md)

Lizenzschlüssel unter **Website-Administration → ZSK Kacheldarstellung → Lizenz**.

- **Lizenzserver-Component:** `local_zsk_local_tiles`
- **Schlüssel-Präfix:** `ZSK-KA-`
- **Key anlegen:** `php cli/create_license.php --plugin=local_zsk_local_tiles`

Spezifikation: `Course Content Health Dashboard/Beschreibung Lizenzsystem.txt`

moodle.org-Texte (DE/EN): [`docs/MOODLE_ORG_PLUGINS.md`](../../docs/MOODLE_ORG_PLUGINS.md)

## Installation

**Dokumentation:** [docs/de/README.md](docs/de/README.md) · Produkt: [docs/de/PRODUKTBESCHREIBUNG.md](docs/de/PRODUKTBESCHREIBUNG.md) · Admin: [docs/de/INSTALLATION_ADMIN.md](docs/de/INSTALLATION_ADMIN.md)

1. Ordner nach `[Moodle]/local/zsk_local_tiles/` kopieren
2. **Mitteilungen** ausführen
3. Migration von `local_tiles2`: siehe [docs/MIGRATION.md](docs/MIGRATION.md)
