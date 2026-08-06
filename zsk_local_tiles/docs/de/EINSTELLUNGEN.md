# Einstellungen – ZSK Kachelansicht

**Menü:** Website-Administration → **ZSK Kacheldarstellung**

---

## Lizenz (`local_zsk_local_tiles_license`)

| Einstellung | Config-Key | Beschreibung |
|-------------|------------|--------------|
| URL des Lizenzservers | `license_server_url` | Verify-Endpunkt |
| Offline-Toleranz (Tage) | `license_grace_days` | Premium bei Serverausfall (Standard: 7) |
| Premium-Lizenzschlüssel | `license_key` | Präfix `ZSK-KA-` |
| Lizenzstatus | *(Anzeige)* | Free, Premium, Karenz, Fehler |

---

## Kachelinhalte: zwei Möglichkeiten

Unter **Kachelansicht** gibt es die Einstellung:

**„Übernahme der Bilder und Texte für die Kacheln aus“** (`tiles_content_source`)

| Option | Bedeutung |
|--------|-----------|
| **Kurseinstellungen** (Standard) | Bild und Text kommen aus den normalen Moodle-Kurseinstellungen (Kursbild / Kursbeschreibung) bzw. aus der Kursbereichsbeschreibung. |
| **Separat hochladen** | Bild und Vorschautext werden **unabhängig** vom Kursformular auf eigenen Pflege-Seiten hinterlegt. |

Der Vorschautext in der Kachel ist auf maximal **300 Zeichen** begrenzt (Anzeige).

### Wenn „Kurseinstellungen“ gewählt ist

- Kursbild: Datei unter Kurs → Einstellungen → Kursbild (`overviewfiles`)
- Text: Kursbeschreibung (Summary)
- Kursbereiche: Beschreibung / eingebettetes Bild der Kategorie

Lehrende pflegen das wie gewohnt im Kurs.

### Wenn „Separat hochladen“ gewählt ist

1. Unter **Website-Administration → ZSK Kacheldarstellung → Berechtigte für Kachelinhalte** Personen freischalten (Allowlist).
2. Nur diese Personen sehen in der Navigationsleiste **„Kachelinhalte pflegen“** (Site-Admins **nicht** automatisch).
3. Über die Pflege-Oberfläche:
   - **Kurs-Kacheln pflegen** – Kursbereich wählen, dann Bild und Text für mehrere Kurse
   - **Kursbereichs-Kacheln pflegen** – Bild und Text für Kategorien
4. Mehrsprachige Texte können mit dem Moodle-Mehrsprachenfilter erfasst werden (z. B. `{mlang de}…{mlang}{mlang en}…{mlang}`).

**Hinweis:** Solange die Weiche auf „Kurseinstellungen“ steht, werden separat hochgeladene Inhalte in den Kacheln **nicht** verwendet (die Pflege-Seiten können trotzdem vorbereitet werden).

---

## Kachelansicht (`local_zsk_local_tiles_config`)

### Kachelinhalte

| Einstellung | Config-Key | Beschreibung |
|-------------|------------|--------------|
| Übernahme der Bilder und Texte … | `tiles_content_source` | `course` = Kurseinstellungen, `custom` = Separat hochladen |

### Kostenlose Version

| Einstellung | Config-Key | Standard |
|-------------|------------|----------|
| Kurs-Kacheln auf dem Dashboard (Mitte) erlauben | `tiles_dashboard` | Ja |
| Kacheln anzeigen auf „Meine Kurse“ | `tiles_mycourses` | Ja |

### Premium

| Einstellung | Config-Key | Beschreibung |
|-------------|------------|--------------|
| Kurs-Kacheln auf der Startseite (Mitte) erlauben | `tiles_frontpage` | Mit Startseiten-Element „Kurs-Kacheln“ |
| Kacheln auf Kursbereichsebene | `tiles_category` | Kurskatalog + Kurssuche |
| Anzeige Kurse ohne Einschreibung | `tiles_showunenrolled` | Footer „noch nicht eingeschrieben“ |
| Kachelansicht bis Ebene | `tiles_category_maxdepth` | Tiefe im Kurskatalog (0 = unbegrenzt) |
| Platzhalter-Bild | `tiles_placeholderimage` | Für Kurse ohne Kursbild |
| Spalten pro Zeile | `tiles_grid_columns` | 1–3 |
| Bildhöhe (Pixel) | `tiles_image_height` | Standard 175 |
| Beschreibungszeilen | `tiles_desc_lines` | Standard 7 |
| Footer-Farben | `footer_color_*` | Hintergrund/Schrift für Status-Footer |

---

## Berechtigte für Kachelinhalte

**Website-Administration → ZSK Kacheldarstellung → Berechtigte für Kachelinhalte**

Nur hier eingetragene Nutzer dürfen die Seiten **Kachelinhalte pflegen** nutzen und sehen den Nav-Eintrag. Unabhängig vom Admin-Status.

---

## Startseiten-Layout (Moodle-Core)

**Website-Administration → Startseite → Startseite-Einstellungen**

Element **Kurs-Kacheln** (Slot `8`) in `frontpageloggedin` / `frontpage` eintragen.

---

## Cron

Tägliche Lizenzprüfung: Task `verify_license_task` (ca. 03:45).
