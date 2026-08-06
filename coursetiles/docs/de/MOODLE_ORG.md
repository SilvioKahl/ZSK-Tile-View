# moodle.org – Plugin-Eintrag (Deutsch)

Textvorlage für [moodle.org/plugins](https://moodle.org/plugins/).  
Plugin: `block_coursetiles` · Release 1.1.0 · Moodle 4.1+

**Hinweis:** Dies ist ein **Add-on** zu **ZSK Kachelansicht** (`local_zsk_local_tiles`). Beide Plugins benötigen **getrennte** moodle.org-Einträge.

---

## Titel (Plugin-Name)

**ZSK Kurs-Kacheln (Dashboard-Block)**

*(Alternativ kürzer: „Kurs-Kacheln (ZSK Dashboard-Block)“ – der ZSK-Bezug sollte im Titel erkennbar sein.)*

---

## Kurzbeschreibung (Summary)

Dashboard-Block für die Kurs-Kachelansicht in der **mittleren Spalte** des persönlichen Dashboards (`/my/`). **Benötigt das Local-Plugin ZSK Kachelansicht** (`local_zsk_local_tiles`) – bitte zuerst installieren.

---

## Beschreibung (Description)

Der Block **ZSK Kurs-Kacheln (Dashboard-Block)** zeigt die Kurs-Kachelansicht von **ZSK Kachelansicht** als normalen Moodle-Block in der **Mitte des Dashboards** (Region *content* auf `/my/`).

### Wichtig: Zwei Plugins, eine Lösung

| Plugin | Aufgabe |
|--------|---------|
| **ZSK Kachelansicht** (`local_zsk_local_tiles`) | Kern: Kachel-Logik, Darstellung, Lizenz, Meine Kurse, Startseite, Kursbereiche |
| **Dieser Block** (`block_coursetiles`) | Nur Dashboard-Mitte – ohne Local-Plugin nicht nutzbar |

**Installationsreihenfolge:** Zuerst `local_zsk_local_tiles`, dann diesen Block.

### Funktionen

- Kurse als **Kacheln** in der Dashboard-Mitte (Bild, Titel, Kurzbeschreibung)
- Nutzt Layout, Styles und Premium-Funktionen von **ZSK Kachelansicht**
- Wird bei Installation/Upgrade **automatisch** im Dashboard angelegt, wenn aktiviert und noch nicht vorhanden
- Block kann wie gewohnt verschoben oder entfernt werden

### Nicht enthalten (liegt im Local-Plugin)

- **Meine Kurse** (`/my/courses.php`)
- **Startseite** (Element „Kurs-Kacheln“ in den Startseiten-Einstellungen)
- **Kursbereiche** und **Kurssuche**
- Eigene Einstellungen oder Lizenzschlüssel

### Abhängigkeiten

| Plugin | Pflicht | Mindestversion |
|--------|---------|----------------|
| ZSK Kachelansicht (`local_zsk_local_tiles`) | **Ja** | 1.0.0 (2025061300) |
| Moodle | Ja | 4.1+ |

### Installation (Kurzfassung)

1. **ZSK Kachelansicht** installieren → Mitteilungen ausführen
2. Diesen Block nach `blocks/coursetiles/` installieren → Mitteilungen erneut ausführen
3. In **ZSK Kacheldarstellung → Kachelansicht:** „Kurs-Kacheln auf dem Dashboard erlauben“ aktivieren
4. Dashboard prüfen

Ausführliche Anleitung: `blocks/coursetiles/docs/de/INSTALLATION_ADMIN.md`

### Related plugins (Textfeld auf moodle.org)

Benötigt das Local-Plugin **ZSK Kachelansicht** (`local_zsk_local_tiles`). Bitte **zuerst** installieren. Für Meine Kurse, Startseite und Kursbereiche reicht das Local-Plugin allein; für Dashboard-Kacheln in der Mitte ist dieser Block erforderlich.

### Tags (Vorschlag)

`block`, `course tiles`, `dashboard`, `kurs-kacheln`, `kachelansicht`, `zsk`
