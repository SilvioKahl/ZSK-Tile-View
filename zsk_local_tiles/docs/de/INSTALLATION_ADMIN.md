# Installationsanleitung für Administratoren

**Plugin:** `local_zsk_local_tiles` (ZSK Kachelansicht)  
**Stand:** Version 1.1.5

---

## Was das Plugin leistet

Ersetzt oder ergänzt Moodle-Standardlisten durch eine **Kachelansicht** (Bild, Titel, Beschreibung, optional Footer mit Fortschritt).

| Kontext | Seite | Benötigtes Plugin |
|---------|-------|-------------------|
| Dashboard (Mitte) | `/my/` | **dieses Plugin +** [`block_coursetiles`](../../../../blocks/coursetiles/docs/de/INSTALLATION_ADMIN.md) |
| Meine Kurse | `/my/courses.php` | nur dieses Plugin |
| Startseite | nach Anmeldung (Premium) | nur dieses Plugin |
| Kursbereiche / Kurssuche | `/course/index.php`, `/course/search.php` (Premium) | nur dieses Plugin |

**Wichtig:** Für Kacheln in der **Dashboard-Mitte** reicht dieses Local-Plugin allein **nicht** aus. Sie benötigen zusätzlich das separate Block-Plugin **`block_coursetiles`** (eigener moodle.org-Eintrag, Ordner `blocks/coursetiles/`).

---

## Voraussetzungen

- Moodle **4.1+** (empfohlen 4.4+)
- Ordnerziel: `moodle/local/zsk_local_tiles/`
- **Für Dashboard-Kacheln:** zusätzlich `block_coursetiles` nach `blocks/coursetiles/` (siehe [Block-Installationsanleitung](../../../../blocks/coursetiles/docs/de/INSTALLATION_ADMIN.md))
- Optional: `local_zsk_frontpage_elements` für Startseiten-Dropdown
- **Nicht parallel** mit Legacy-Plugin `local_tiles2` betreiben

---

## Installation

### Schritt 1: Local-Plugin (dieses Plugin)

1. ZIP entpacken → Ordner `zsk_local_tiles` nach `local/zsk_local_tiles/`
2. **Website-Administration → Mitteilungen**
3. **Caches leeren**

### Schritt 2: Block-Plugin (für Dashboard-Mitte)

Wenn Sie Kacheln auf dem **Dashboard** (`/my/`) anzeigen möchten:

1. ZIP mit `coursetiles` nach `blocks/coursetiles/` entpacken
2. **Website-Administration → Mitteilungen** erneut ausführen
3. Ausführlich: [Installationsanleitung block_coursetiles](../../../../blocks/coursetiles/docs/de/INSTALLATION_ADMIN.md)

Auf moodle.org sind Local- und Block-Plugin **zwei getrennte Einträge** – beide müssen einzeln installiert werden.

---

## Grundeinrichtung (kostenlos)

**Website-Administration → ZSK Kacheldarstellung → Kachelansicht**

| Einstellung | Empfehlung |
|-------------|------------|
| Kurs-Kacheln auf dem Dashboard erlauben | Ja |
| Kacheln auf „Meine Kurse“ | Ja |

Sobald **`block_coursetiles` installiert** ist, wird die Block-Instanz bei Bedarf automatisch in der Dashboard-Mitte angelegt. Ohne das Block-Plugin bleibt das Dashboard ohne Kacheln – **Meine Kurse** funktioniert trotzdem.

Details: [EINSTELLUNGEN.md](EINSTELLUNGEN.md)

---

## Bilder und Texte für Kacheln (zwei Wege)

**Website-Administration → ZSK Kacheldarstellung → Kachelansicht**

Einstellung **„Übernahme der Bilder und Texte für die Kacheln aus“**:

| Option | Für wen geeignet |
|--------|------------------|
| **Kurseinstellungen** (Standard) | Dozenten pflegen Kursbild und Beschreibung im Kursformular. |
| **Separat hochladen** | Dedizierte Pflege-Seiten; Allowlist unter **Berechtigte für Kachelinhalte**; Nav **„Kachelinhalte pflegen“** nur für Freigeschaltete. |

Ausführlich: [EINSTELLUNGEN.md](EINSTELLUNGEN.md) · Dozenten: [KLICKANLEITUNG_DOZENTEN.md](KLICKANLEITUNG_DOZENTEN.md)

---

## Premium-Lizenz (optional)

**Website-Administration → ZSK Kacheldarstellung → Lizenz**

1. **URL des Lizenzservers** speichern (z. B. `…/api/v1/verify.php`)
2. Schlüssel mit Präfix **`ZSK-KA-`** eintragen
3. Status prüfen (Free / Premium / Karenz)

Premium schaltet u. a. Startseite, Kursbereiche, Layout-Optionen und Footer-Infos frei.

---

## Startseite (Premium)

1. **Kurs-Kacheln auf der Startseite erlauben** aktivieren
2. **Website-Administration → Startseite → Startseite-Einstellungen**
3. Bei **Startseite nach Anmeldung** Element **Kurs-Kacheln** wählen und positionieren

---

## Checkliste

- [ ] Local-Plugin installiert, Upgrade ohne Fehler
- [ ] `block_coursetiles` installiert (wenn Dashboard-Kacheln gewünscht)
- [ ] Dashboard und Meine Kurse zeigen Kacheln
- [ ] Optional: Weiche Kachelinhalte (Kurseinstellungen vs. Separat hochladen) entschieden
- [ ] Optional: Allowlist für „Kachelinhalte pflegen“ gepflegt
- [ ] Optional: Premium-Lizenz aktiv
- [ ] Optional: Startseite konfiguriert

---

## Weitere Hilfe

- [Block Kurs-Kacheln – Installationsanleitung](../../../../blocks/coursetiles/docs/de/INSTALLATION_ADMIN.md)
- [EINSTELLUNGEN.md](EINSTELLUNGEN.md)
- [MIGRATION.md](../MIGRATION.md)
- [wartung-kachelansicht.md](wartung-kachelansicht.md)

*Stand: ZSK Kachelansicht 1.0.10*
