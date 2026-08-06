# Installationsanleitung für Administratoren

**Plugin:** `block_coursetiles` (Kurs-Kacheln – Dashboard-Block)  
**Zielgruppe:** Systemadministratoren und Moodle-Manager  
**Stand:** Plugin-Version 1.1.0

**Zugehöriges Kern-Plugin:** [`local_zsk_local_tiles`](../../../../local/zsk_local_tiles/docs/de/INSTALLATION_ADMIN.md) (ZSK Kachelansicht)

---

## Was dieses Plugin ist – und was nicht

Der Block **Kurs-Kacheln** ist **kein eigenständiges Kachelprodukt**. Er ist das **Dashboard-Add-on** zur **ZSK Kachelansicht** (`local_zsk_local_tiles`).

| | `local_zsk_local_tiles` (Kern) | `block_coursetiles` (dieses Plugin) |
|---|---|---|
| **Rolle** | Kachel-Logik, Styles, Lizenz, Einstellungen | Moodle-Block für die **Dashboard-Mitte** |
| **Ordner** | `local/zsk_local_tiles/` | `blocks/coursetiles/` |
| **moodle.org** | Separater Eintrag (Kernprodukt) | Separater Eintrag (Add-on) |
| **Einstellungen** | Ja (Darstellung, Lizenz, Kontexte) | Nein (keine eigene Konfiguration) |
| **Lizenzschlüssel** | Ja (`ZSK-KA-…`) | Nein (nutzt den Kern) |

**Merksatz:** Alles, was Kacheln *aussehen*, *filtern* und *freischalten* lässt, steuert das **Local-Plugin**. Dieser Block zeigt die Kacheln nur als **normalen Moodle-Block in der Mitte des Dashboards** (`/my/`).

---

## Wo welches Plugin wirkt

| Seite / Kontext | URL (typisch) | Benötigtes Plugin |
|-----------------|---------------|-------------------|
| **Dashboard (Mitte)** | `/my/` | **`block_coursetiles`** + `local_zsk_local_tiles` |
| **Meine Kurse** | `/my/courses.php` | nur `local_zsk_local_tiles` |
| **Startseite** (Premium) | `/` nach Anmeldung | nur `local_zsk_local_tiles` (Element „Kurs-Kacheln“) |
| **Kursbereiche** (Premium) | `/course/index.php` | nur `local_zsk_local_tiles` |
| **Kurssuche** (Premium) | `/course/search.php` | nur `local_zsk_local_tiles` |

```text
ZSK Kachelansicht (local_zsk_local_tiles)
├── Meine Kurse, Startseite, Kursbereiche, Kurssuche  → direkt im Local-Plugin
└── Dashboard-Mitte (/my/)                            → über block_coursetiles
```

**Wichtig:** Die **Startseite** nutzt **keinen** Sidebar-Block. Dort wählen Sie in den Startseiten-Einstellungen das Element **„Kurs-Kacheln“** – das kommt aus dem Local-Plugin, nicht aus diesem Block.

---

## Voraussetzungen

| Anforderung | Details |
|-------------|---------|
| Moodle | **4.1+** (empfohlen 4.4+) |
| Kern-Plugin | **`local_zsk_local_tiles`** muss **zuerst** installiert sein (Mindestversion 1.0.0) |
| Installationsreihenfolge | 1. Local-Plugin → 2. Block |
| Ordnerziel | `moodle/blocks/coursetiles/` |

Ohne `local_zsk_local_tiles` lässt sich dieser Block **nicht installieren** (Abhängigkeit in `version.php`).

---

## Installation

### Schritt 1: ZSK Kachelansicht installieren

Falls noch nicht geschehen:

1. ZIP mit `zsk_local_tiles` nach `local/zsk_local_tiles/` entpacken
2. **Website-Administration → Mitteilungen** ausführen
3. Grundeinrichtung im Local-Plugin vornehmen

Ausführlich: [Installationsanleitung ZSK Kachelansicht](../../../../local/zsk_local_tiles/docs/de/INSTALLATION_ADMIN.md)

### Schritt 2: Block Kurs-Kacheln installieren

1. ZIP mit `coursetiles` nach `blocks/coursetiles/` entpacken
2. **Website-Administration → Mitteilungen** erneut ausführen
3. **Caches leeren** (empfohlen)

### Schritt 3: Dashboard-Kacheln aktivieren

**Website-Administration → ZSK Kacheldarstellung → Kachelansicht**

| Einstellung | Empfehlung |
|-------------|------------|
| Kurs-Kacheln auf dem Dashboard erlauben | **Ja** |

Bei Installation oder Upgrade legt das System den Block bei Bedarf **automatisch in der Dashboard-Mitte** (Region *content*) an – sofern noch kein Block dieser Art existiert.

### Schritt 4: Prüfen

1. Als Nutzer mit Kurseinschreibungen anmelden
2. **Dashboard** (`/my/`) öffnen
3. In der **mittleren Spalte** sollten Kurs-Kacheln erscheinen (nicht nur in der Seitenleiste)

---

## Zusammenspiel im Betrieb

### Was der Block macht

- Rendert die Kursliste des Dashboards als **Kacheln** (Bild, Titel, Beschreibung)
- Nutzt **Daten, Layout und Premium-Funktionen** aus `local_zsk_local_tiles`
- Ist nur auf **`my-index`** (persönliches Dashboard) erlaubt
- Erlaubt **nur eine Instanz** pro Dashboard

### Was das Local-Plugin für den Block erledigt

- Einstellung „Dashboard erlauben“ ein/aus
- Kachel-Design (Spalten, Farben, Footer-Infos – Premium)
- Lizenzprüfung (Free/Premium)
- Automatisches Anlegen der Block-Instanz (`dashboard_block.php`)

### Block manuell anpassen

Der Block verhält sich wie jeder andere Moodle-Block:

- Verschieben: **Dashboard anpassen** (Bearbeitungsmodus)
- Entfernen: Block löschen (Kacheln auf dem Dashboard verschwinden dann)
- Wieder hinzufügen: Block **„Kurs-Kacheln“** in die Mitte des Dashboards ziehen

Ohne aktivierte Einstellung im Local-Plugin bleibt der Block **leer**, auch wenn er sichtbar ist.

---

## Häufige Fragen

### Reicht nur das Local-Plugin?

**Für Meine Kurse, Startseite, Kursbereiche und Kurssuche: ja.**  
**Für Kacheln in der Dashboard-Mitte: nein** – dafür ist dieser Block erforderlich.

### Brauche ich für den Block eine eigene Lizenz?

Nein. Premium-Funktionen werden über den Lizenzschlüssel im **Local-Plugin** (`ZSK-KA-…`) freigeschaltet.

### Warum zwei moodle.org-Einträge?

moodle.org erlaubt pro Plugin-Eintrag genau **ein** ZIP mit **einem** Plugin-Ordner. Local- und Block-Plugin müssen deshalb getrennt veröffentlicht werden. Für die manuelle Installation können beide Ordner auch aus einem gemeinsamen Paket ins Moodle-Verzeichnis kopiert werden.

### Der Block ist da, aber leer

Prüfen Sie:

1. Ist **„Kurs-Kacheln auf dem Dashboard erlauben“** aktiviert?
2. Ist der Nutzer eingeloggt (nicht Gast)?
3. Hat der Nutzer sichtbare Kurse?
4. Ist `local_zsk_local_tiles` installiert und aktuell?

---

## Checkliste

- [ ] `local_zsk_local_tiles` installiert und Mitteilungen ausgeführt
- [ ] `block_coursetiles` installiert und Mitteilungen ausgeführt
- [ ] Dashboard-Kacheln in den Einstellungen aktiviert
- [ ] Dashboard zeigt Kacheln in der Mitte
- [ ] Optional: Premium-Lizenz im Local-Plugin hinterlegt

---

## Weitere Hilfe

| Thema | Dokument |
|-------|----------|
| Kern-Plugin Installation | [local/zsk_local_tiles/docs/de/INSTALLATION_ADMIN.md](../../../../local/zsk_local_tiles/docs/de/INSTALLATION_ADMIN.md) |
| Einstellungen (Darstellung, Lizenz) | [local/zsk_local_tiles/docs/de/EINSTELLUNGEN.md](../../../../local/zsk_local_tiles/docs/de/EINSTELLUNGEN.md) |
| moodle.org-Texte für diesen Block | [MOODLE_ORG.md](MOODLE_ORG.md) |
| Produktüberblick ZSK Kachelansicht | [local/zsk_local_tiles/docs/de/PRODUKTBESCHREIBUNG.md](../../../../local/zsk_local_tiles/docs/de/PRODUKTBESCHREIBUNG.md) |

*Stand: block_coursetiles 1.1.0 · local_zsk_local_tiles 1.0.10*
