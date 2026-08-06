# Wartungshandbuch: Automatische Kachelansicht

Dieses Dokument ist bewusst praxisnah geschrieben, damit ein Nachfolger die Funktion ohne Vorwissen warten kann.

---

## 1) Zweck

Auf Kursbereich-Seiten (`/course/index.php?categoryid=...`) erzeugt das Plugin automatisch Kacheln für:

- direkte Unter-Kursbereiche
- direkte Kurse im aktuellen Kursbereich

Die Anzeige ersetzt die Standardlistenansicht im Hauptbereich.

---

## 2) Technischer Ablauf (kurz)

1. `local_zsk_local_tiles_extend_navigation()` wird bei Seitenaufbau aufgerufen.
2. `local_zsk_local_tiles_try_inject_category_tiles()` prüft, ob es eine Kursbereich-Seite ist.
3. `\local_zsk_local_tiles\category_tiles::build_payload()` holt die Daten aus der DB.
4. Ein Inline-JavaScript rendert die Kacheln im `#region-main`.
5. Die Standard-Elemente (`.course_category_tree`, `.subcategories`, `.courses`) werden ausgeblendet.

---

## 3) Relevante Dateien

| Datei | Aufgabe |
|------|---------|
| `local/zsk_local_tiles/lib.php` | Trigger/Injection auf Kursbereich-Seiten |
| `local/zsk_local_tiles/classes/category_tiles.php` | Datenaufbereitung für Kacheln |
| `local/zsk_local_tiles/styles.css` | Kachel-Layout |
| `local/zsk_local_tiles/lang/de/local_zsk_local_tiles.php` | Sprachstring `tile_coursecount` |
| `local/zsk_local_tiles/version.php` + `db/upgrade.php` | Plugin-Upgrade-Version |

---

## 4) Datenquellen

Die Quelle steuert die Einstellung **`tiles_content_source`**:

| Wert | Bedeutung |
|------|-----------|
| `course` (Standard) | Moodle-Kurseinstellungen / Kategoriebeschreibung |
| `custom` | Separat gepflegte Inhalte (Tabellen + Fileareas des Plugins) |

### Kurs-Kachel (`course`)

- Titel: `course.fullname`
- Text: `course.summary` (bereinigt, max. 300 Zeichen in der Anzeige)
- Bild: erste Datei aus `course/overviewfiles`
- Link: `/course/view.php?id={courseid}`

### Kurs-Kachel (`custom`)

- Text: Tabelle `local_zsk_tiles_course` (sonst Fallback auf Summary)
- Bild: Filearea `local_zsk_local_tiles/coursetile` im Kurskontext (sonst Fallback)
- Pflege: `/local/zsk_local_tiles/manage_courses.php` (Allowlist)

### Kursbereich-Kachel (`course`)

- Titel: `course_categories.name`
- Text: `course_categories.description` (bereinigt) + „x Kurse“
- Bild:
  1. erstes `<img src="...">` aus der Bereichsbeschreibung
  2. sonst erste Datei aus `coursecat/description`
- Link: `/course/index.php?categoryid={categoryid}`

### Kursbereich-Kachel (`custom`)

- Text: Tabelle `local_zsk_tiles_category` (sonst Fallback)
- Bild: Filearea `local_zsk_local_tiles/cattile` (sonst Fallback)
- Pflege: `/local/zsk_local_tiles/manage_categories.php` (Allowlist)

---

## 5) Typische Wartungsaufgaben

### A) Nur Styling ändern

Datei: `local/zsk_local_tiles/styles.css`

Bereiche:
- `.local-zsk-tiles-category-grid`
- `.local-zsk-tiles-category-card`
- `.local-zsk-tiles-category-image`

Danach Caches leeren.

### B) Andere Textlogik für Bereichskacheln

Datei: `local/zsk_local_tiles/classes/category_tiles.php`  
Methode: `build_category_tile()`

### C) Andere Reihenfolge

Datei: `local/zsk_local_tiles/classes/category_tiles.php`

- Unterbereiche: Sortierung in `get_records('course_categories', ..., 'sortorder ASC')`
- Kurse: Sortierung in `get_records_select('course', ..., 'sortorder ASC, fullname ASC')`

### D) Standardansicht NICHT ausblenden

Datei: `local/zsk_local_tiles/lib.php`  
Im JS-Block den Teil entfernen/anpassen:

- `main.querySelectorAll('.course_category_tree, .subcategories, .courses')...`

---

## 6) Upgrade-Checkliste nach Moodle-Update

1. Als Admin aufrufen:
   - `/course/index.php?categoryid=1`
2. Prüfen:
   - Kacheln erscheinen
   - Bilder werden geladen
   - Links öffnen korrekt
3. Mit normalem Nutzer prüfen:
   - Sichtbare Inhalte entsprechen Berechtigungen
4. Browser-Konsole auf JS-Fehler prüfen
5. Caches leeren:
   - Website-Administration → Entwicklung → Alle Caches leeren

---

## 7) Häufige Fehler und schnelle Lösung

### Problem: Kacheln auf falschen Seiten (Profil, Nachrichten, Blog, …)

Ursache war oft eine zu weite Erkennung der **Startseite** (`path === ''`) oder fehlende Pfad-Ausschlüsse.

Erlaubt sind nur explizit:

- `/course/index.php?categoryid=…` (Kursbereich)
- `/my/index.php` (Dashboard, wenn aktiviert)
- `/my/courses.php` (Meine Kurse, wenn aktiviert)
- `/index.php` bzw. Startseiten-Kurs `/course/view.php?id=…` (wenn aktiviert)

Ausgeschlossen u. a.: `/user/`, `/message/`, `/blog/`, `/mod/`, Admin, sowie fast alle anderen `/course/*`-Skripte.

Logik: `local_zsk_local_tiles_is_excluded_tile_page()` und `local_zsk_local_tiles_is_site_frontpage()` in `lib.php`.

### Problem: Es erscheinen keine Kacheln

- Seite ist nicht `/course/index.php?categoryid=...`
- Keine direkten Unterbereiche/Kurse vorhanden
- JS-Fehler in der Konsole
- Caches nicht geleert

### Problem: Bilder fehlen

- Kurs hat kein Kursbild (overviewfiles leer)
- Bereichsbeschreibung enthält kein Bild
- Datei-Rechte/Pluginfile-Zugriff prüfen

### Problem: Nach Update kaputt

- Zuerst Caches leeren
- Dann in `lib.php` prüfen, ob `#region-main` im Theme weiterhin existiert
- Falls Theme-Änderung: Zielcontainer im JS anpassen

---

## 8) Sicherheits- und Betriebs-Hinweis

Die Kachelansicht liest nur bestehende Moodle-Daten und schreibt nichts zurück.  
Änderungen sind damit im Regelfall risikominimiert.

