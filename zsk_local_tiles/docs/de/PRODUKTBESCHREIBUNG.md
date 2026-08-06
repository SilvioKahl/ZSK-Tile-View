# ZSK Kachelansicht – Produktbeschreibung für Moodle-Betreiber

**ZSK Kachelansicht** (`local_zsk_local_tiles`) ersetzt Moodles **Standard-Kurslisten** durch eine moderne **Kachelansicht** mit Kursbild, Titel, Kurzbeschreibung und optionalen Zusatzinfos im Kachel-Footer – auf Dashboard, „Meine Kurse“, Startseite, in Kursbereichen und in der Kurssuche.

| | |
|---|---|
| **Plugin** | `local_zsk_local_tiles` |
| **Moodle** | 4.1 oder neuer (4.4+ empfohlen) |
| **Optionales Add-on** | Block **Kurs-Kacheln** (`block_coursetiles`) für Dashboard-Mitte – separater moodle.org-Eintrag |
| **Lizenz** | Freemium: Dashboard + Meine Kurse kostenlos; Premium per Schlüssel `ZSK-KA-` |
| **Sprachen** | Deutsch und Englisch (Oberfläche und Dokumentation) |

---

## Welche Probleme löst das Plugin?

Moodle zeigt Kurse standardmäßig als **Textlisten** – funktional, aber wenig visuell und auf mobilen Geräten oft unübersichtlich. Betreiber und Nutzer stoßen deshalb häufig auf dieselben Hürden:

### Unattraktive, schwer scannbare Kursübersicht

Lange Listen mit nur Titel und Link erschweren die Orientierung, besonders bei vielen parallelen Kursen oder Kursbereichen mit Unterkategorien.

### Fehlende Kursbilder und Kontext in der Übersicht

Die Standardliste nutzt Kursbilder und Beschreibungen kaum. Nutzer erkennen Kurse nicht auf einen Blick wieder.

### Kein Fortschritt und kein Einschreibungsstatus auf einen Blick

Lernende müssen in jeden Kurs wechseln, um Abschluss oder Einschreibungsstatus zu sehen – die Übersicht liefert diese Information nicht.

### Startseite und Dashboard wirken veraltet

Institutionen wollen eine **moderne Startseite** mit Kurs-Kacheln in der Mitte – nicht nur die eingebaute Listenansicht oder einen Block in der Seitenleiste ohne einheitliches Design.

### Kurskatalog und Kurssuche bleiben listenbasiert

Auf `/course/index.php` und in der Kurssuche dominieren Tabellen und Listen; Kursbereiche mit Beschreibung und Kursanzahl lassen sich visuell schlecht darstellen.

### Theme-Anpassungen sind teuer und wartungsintensiv

Ohne Plugin werden Kachel-Layouts oft per **Custom CSS**, Child-Theme oder externe Entwicklung umgesetzt – bei jedem Moodle-Upgrade erneut prüfen.

### Uneinheitliche Lösungen pro Seite

Dashboard, Meine Kurse und Kurskatalog nutzen unterschiedliche Darstellungen. Ein zentrales Plugin schafft **ein einheitliches Erscheinungsbild**.

---

## Vorteile des Plugins

### Visuelle Kurs-Kacheln statt Listen

Jede Kachel zeigt **Kursbild** (oder Platzhalter), **Titel** und **Kurzbeschreibung** in einem Raster – responsiv und auf Boost-Themes abgestimmt.

### Zwei Wege für Bild und Text

| Quelle | Beschreibung |
|--------|--------------|
| **Kurseinstellungen** | Klassisch: Kursbild und Kursbeschreibung im Kursformular (bzw. Kategoriebeschreibung). |
| **Separat hochladen** | Eigene Pflege-Oberfläche mit Allowlist; Nav „Kachelinhalte pflegen“ nur für Freigeschaltete – unabhängig vom Kursformular. |

Die Weiche liegt in den Plugin-Einstellungen (`tiles_content_source`). Details: [EINSTELLUNGEN.md](EINSTELLUNGEN.md).

### Mehrere Einsatzorte aus einer Quelle

| Kontext | Seite | Verfügbarkeit |
|---------|-------|----------------|
| **Dashboard** | `/my/` (Mitte) | Kostenlos |
| **Meine Kurse** | `/my/courses.php` | Kostenlos |
| **Startseite** | nach Anmeldung | Premium |
| **Kursbereiche** | `/course/index.php` | Premium |
| **Kurssuche** | `/course/search.php` | Premium |

### Freemium mit klarem Mehrwert

**Kostenlos:** Dashboard und Meine Kurse, Standard-Layout (2 Spalten, Standard-Farben, ohne Footer-Infos).

**Premium** (`ZSK-KA-`): alle Ansichten, anpassbares Layout (Spalten, Bildhöhe, Beschreibungszeilen), Footer-Farben, Platzhalterbild, Footer-Infos (Fortschritt, Einschreibung, Kursanzahl in Bereichen).

### Footer-Informationen (Premium)

Am unteren Kachelrand u. a.:

- Lernfortschritt / Abschlussstatus
- „Noch nicht eingeschrieben“ im Katalog
- Kursanzahl bei Kursbereichs-Kacheln

### Startseiten-Integration

Element **„Kurs-Kacheln“** in den Moodle-Startseiten-Einstellungen – sortierbar neben anderen ZSK-Elementen (Termine, eigene Startseiten-Blöcke).

### Automatische Einbindung ohne manuelles HTML

Auf Kursbereichsseiten und „Meine Kurse“ ersetzt das Plugin die Standardliste per **Hook/Injection** – kein Eingriff in Kurstemplates nötig.

### Lizenzserver mit Offline-Toleranz

Premium-Lizenz über zentralen ZSK-Lizenzserver; bei kurzzeitigem Ausfall bleibt Premium für konfigurierbare Tage aktiv (Karenz).

### Migration von Legacy-Plugins

Umstieg von `local_tiles2` / älteren Kachel-Lösungen dokumentiert ([MIGRATION.md](../MIGRATION.md)).

---

## Funktionsübersicht im Überblick

| Bereich | Was Sie erhalten |
|---------|------------------|
| **Dashboard** | Block „Kurs-Kacheln“ in der Mitte (auto-Anlage möglich) |
| **Meine Kurse** | Kachelraster nur eingeschriebener Kurse |
| **Startseite** | Kurs-Kacheln als Layout-Element (Premium) |
| **Kurskatalog** | Kacheln in Kursbereichen, Tiefe begrenzbar (Premium) |
| **Layout** | Spalten, Bildhöhe, Beschreibungszeilen (Premium) |
| **Design** | Footer-Farben pro Status, Platzhalterbild (Premium) |
| **Lizenz** | Free / Premium / Karenz, CLI-Test `test_license.php` |
| **Rechte** | Nutzt Moodle-Core-Rechte (Einschreibung, Katalogsicht) |

---

## Für wen ist das Plugin geeignet?

- **Hochschulen und Weiterbildung** mit vielen parallelen Kursen und Kursbereichen
- **Unternehmen** mit übersichtlichem Lernkatalog auf Dashboard und Startseite
- **Moodle-Partner**, die Kunden ein modernes Kursbild ohne Theme-Projekt anbieten möchten

---

## Abgrenzung

- **Kein** Ersatz für die Kursverwaltung oder Einschreibungen
- **Kein** Kursinhalt-Plugin – nur **Darstellung** der Kursübersicht
- Block `block_coursetiles` ist **optional** (empfohlen für Dashboard); Kernfunktion liegt im Local-Plugin
- Nicht parallel mit Legacy-Plugin `local_tiles2` betreiben

---

## Technische Voraussetzungen (kurz)

- Moodle **4.1+**
- Optional: `local_zsk_frontpage_elements` für erweiterte Startseiten-Dropdown-Integration
- Premium: erreichbarer ZSK-Lizenzserver, Schlüssel-Präfix `ZSK-KA-`

Ausführliche Einrichtung: [INSTALLATION_ADMIN.md](INSTALLATION_ADMIN.md) · Einstellungen: [EINSTELLUNGEN.md](EINSTELLUNGEN.md)

---

*Silvio Kuhn – die-schulungsexperten.de · Stand: Plugin 1.0.10*
