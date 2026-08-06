# Migration: `local_tiles2` → `local_zsk_local_tiles`

## Empfohlene Reihenfolge

1. `local/zsk_local_tiles/` hochladen (`local_tiles2` vorerst behalten)
2. **Website-Administration → Mitteilungen**
3. Kachelansicht auf Dashboard und Meine Kurse prüfen (Free-Tier)
4. Premium-Lizenz eintragen und Kursbereiche / Startseite testen
5. Block `block_coursetiles` aktualisieren (Version 1.1.0 – Abhängigkeit auf `local_zsk_local_tiles`)
6. `local_tiles2` deinstallieren, Ordner `local/tiles2/` löschen
7. Caches leeren

Beim Upgrade auf `2025061300` werden Konfiguration und Platzhalterbild von `local_tiles2`, `local_tiles` oder `local_statistics` übernommen.

## Lizenzserver

Schlüssel anlegen mit:

```bash
php cli/create_license.php --plugin=local_zsk_local_tiles
```

Gemeinsame Einstellungen (Server-URL, Offline-Toleranz) werden unter `local_zsk_plugins` gespeichert und von allen ZSK-Plugins genutzt.

## HTTP 500 bei paralleler Installation

Wenn `local_tiles2` und `local_zsk_local_tiles` gleichzeitig aktiv sind, kann es zu Konflikten kommen. Nach erfolgreicher Migration das Legacy-Plugin entfernen.
