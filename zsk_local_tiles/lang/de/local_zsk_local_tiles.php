<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'ZSK Kachelansicht';

$string['tile_coursecount'] = '{$a} Kurs|{$a} Kurse';
$string['tile_completion_percent'] = 'Kurs zu {$a} % bearbeitet.';
$string['tile_completion_disabled'] = 'Keine Abschlussverfolgung für diesen Kurs';
$string['tile_not_enrolled'] = 'noch nicht eingeschrieben';
$string['tilesettings_heading'] = 'Einstellungen Kachelansicht';
$string['tilesettings_intro'] = 'Kursbereiche und „Meine Kurse“: automatische Kachelansicht. Startseite: Element in den Startseiten-Einstellungen. Dashboard: Block in der Mitte (content-Region).';
$string['tilesettings_block_heading'] = 'Startseite & Dashboard';
$string['tilesettings_block_desc'] = 'Startseite: unter Website-Administration → Startseite → „Startseite nach Anmeldung“ das Element „Kurs-Kacheln“ wählen. Dashboard: Block „Kurs-Kacheln“ in der Mitte (wird bei Aktivierung automatisch angelegt, falls noch nicht vorhanden).';
$string['frontpagecoursetiles'] = 'Kurs-Kacheln';
$string['frontpagecoursetiles_heading'] = 'Kursliste';
$string['tilesettings_injection_heading'] = 'Kursbereiche & Meine Kurse (automatisch)';
$string['tilesettings_injection_desc'] = 'Ersetzt die Standardliste nur auf Kursbereichsseiten und optional auf „Meine Kurse“ – nicht auf Startseite/Dashboard.';
$string['tiles_showunenrolled'] = 'Anzeige Kurse ohne Einschreibung';
$string['tiles_showunenrolled_desc'] = 'Gilt für Kursbereiche, „Meine Kurse“, sowie den Block auf Startseite/Dashboard. Im Kachel-Footer erscheint dann „noch nicht eingeschrieben“. Auf „Meine Kurse“ nie ohne Einschreibung.';
$string['tiles_category'] = 'Kacheln anzeigen auf Kursbereichsebene';
$string['tiles_category_desc'] = 'Ersetzt die Standardliste auf Kursbereichsseiten und in der Kurssuche (/course/search.php) durch die Kachelansicht. Die Tiefe auf Kursbereichsseiten kann separat begrenzt werden.';
$string['tiles_category_maxdepth'] = 'Kachelansicht bis Ebene';
$string['tiles_category_maxdepth_desc'] = 'Gilt nur für Kursbereichsseiten (/course/index.php?categoryid=…), nicht für Startseite, Dashboard oder „Meine Kurse“. Die oberste Kursbereichsebene zählt als 1. Ebene. Bei „3. Ebene“ werden die Ebenen 1–3 (oberster Bereich inkl. zwei Unterebenen) als Kacheln dargestellt; ab der nächsten Ebene erscheint die Moodle-Standardansicht. „Alle Ebenen“ lässt die bisherige Darstellung auf allen Kursbereichsebenen zu.';
$string['tiles_category_maxdepth_unlimited'] = 'Alle Ebenen (unbegrenzt)';
$string['tiles_category_maxdepth_level'] = '{$a}. Ebene';
$string['tiles_dashboard'] = 'Kurs-Kacheln auf dem Dashboard (Mitte) erlauben';
$string['tiles_dashboard_desc'] = 'Zeigt Kurse als Kacheln im Block „Kurs-Kacheln“ in der Mitte von /my/ (Region content). Beim ersten Aktivieren wird der Block automatisch angelegt.';
$string['tiles_frontpage'] = 'Kurs-Kacheln auf der Startseite (Mitte) erlauben';
$string['tiles_frontpage_desc'] = 'Wenn in den Startseiten-Einstellungen „Kurs-Kacheln“ gewählt ist, werden Kurse als Kacheln in der Mitte angezeigt (nicht in der Seitenleiste).';
$string['tiles_mycourses'] = 'Kacheln anzeigen auf „Meine Kurse“';
$string['tiles_mycourses_desc'] = 'Zeigt die Kachelansicht auf /my/courses.php. Es werden ausschließlich eingeschriebene Kurse angezeigt.';
$string['tiles_placeholderimage'] = 'Platzhalter-Bild für Kacheln ohne eigenes Bild';
$string['tiles_placeholderimage_desc'] = 'Wird in der Kachelansicht angezeigt, wenn Kurs oder Kursbereich kein eigenes Kachelbild hat. Empfohlen: Querformat, mindestens 400×200 Pixel.';

$string['tilesettings_free_heading'] = 'Kostenlose Version (nach Trial)';
$string['tilesettings_free_desc'] = 'Nach Ablauf der 100-Tage-Testphase ohne Lizenz: Dashboard und „Meine Kurse“ mit Standard-Layout (2 Spalten, Standard-Farben, ohne Footer-Infos).';
$string['tilesettings_premium_heading'] = 'Premium-Funktionen';
$string['tilesettings_premium_desc'] = '100 Tage voll freigeschaltet. Danach mit Lizenz: Startseite, Kursbereiche, Kurssuche, Platzhalterbild, Kachel-Infos (Fortschritt, Einschreibung, Kursanzahl), Layout und Farben.';
$string['tilesettings_layout_heading'] = 'Kachel-Layout (Premium)';
$string['tilesettings_layout_desc'] = 'Bildhöhe, Spaltenanzahl und Beschreibungszeilen. In der kostenlosen Version gelten die Standardwerte.';
$string['tilesettings_colors_heading'] = 'Footer-Farben (Premium)';
$string['tilesettings_colors_desc'] = 'Hintergrund- und Schriftfarbe der Info-Leiste am unteren Kachelrand. Leer lassen für Standardfarben.';
$string['tiles_grid_columns'] = 'Spalten pro Zeile';
$string['tiles_grid_columns_desc'] = 'Anzahl gleich breiter Kacheln nebeneinander (ab 520 px Breite).';
$string['tiles_image_height'] = 'Bildhöhe (Pixel)';
$string['tiles_image_height_desc'] = 'Höhe des Kursbildes in der Kachel.';
$string['tiles_desc_lines'] = 'Beschreibungszeilen';
$string['tiles_desc_lines_desc'] = 'Maximale Zeilen der Kursbeschreibung in der Kachel.';
$string['footer_color_bg'] = 'Hintergrundfarbe';
$string['footer_color_bg_desc'] = 'CSS-Farbwert, z. B. #dff5e3';
$string['footer_color_fg'] = 'Schriftfarbe';
$string['footer_color_fg_desc'] = 'CSS-Farbwert, z. B. #1f5c2e';
$string['footer_color_complete'] = 'Kurs abgeschlossen';
$string['footer_color_progress'] = 'Kurs in Bearbeitung';
$string['footer_color_notstarted'] = 'Kurs nicht begonnen';
$string['footer_color_disabled'] = 'Keine Abschlussverfolgung';
$string['footer_color_notenrolled'] = 'Nicht eingeschrieben';
$string['footer_color_categorycount'] = 'Kursanzahl (Kursbereich)';
$string['branding_footer'] = 'ZSK Kachelansicht – kostenlose Version';
$string['license_premium_only_hint'] = '(Premium)';
$string['license_error_premium_required'] = 'Diese Einstellung erfordert eine gültige Premium-Lizenz oder eine aktive Testphase.';
$string['admin_category'] = 'ZSK Kacheldarstellung';
$string['license_settings_title'] = 'ZSK Kacheldarstellung – Lizenz';
$string['settings_moved_title'] = 'Einstellungen verschoben';
$string['settings_moved_tiles'] = 'Die Einstellungen wurden aufgeteilt: {$a->license} · {$a->design}';
$string['license_heading'] = 'Premium-Lizenz';
$string['license_heading_desc'] = 'Alle Funktionen sind 100 Tage kostenlos testbar. Danach schaltet ein gültiger Lizenzschlüssel Premium frei; ohne Schlüssel bleiben Dashboard und „Meine Kurse“.';
$string['license_key'] = 'Premium-Lizenzschlüssel';
$string['license_key_desc'] = 'Nur Schlüssel für ZSK Kachelansicht (Präfix ZSK-KA-). Auf dem Lizenzserver anlegen mit: php cli/create_license.php --plugin=local_zsk_local_tiles. Zum Entfernen oder Ersetzen: „Schlüssel anzeigen“ wählen, Feld bearbeiten und speichern.';
$string['license_status'] = 'Lizenzstatus';
$string['license_status_trial'] = 'Testphase aktiv – noch {$a} Tage voller Funktionsumfang';
$string['license_status_free'] = 'Kostenlose Version (Dashboard + Meine Kurse, Standard-Layout)';
$string['license_status_premium'] = 'Premium (alle Funktionen aktiv)';
$string['license_status_premium_slots'] = 'Premium ({$a->used}/{$a->max} Umgebungen gebunden)';
$string['license_status_grace'] = 'Premium (Offline-Toleranz: {$a} Tage)';
$string['license_status_key_unverified'] = 'Lizenzschlüssel gespeichert, Verifizierung ausstehend.';
$string['license_status_key_no_server'] = 'Lizenzschlüssel gespeichert, aber keine Lizenzserver-URL hinterlegt.';
$string['license_server_url'] = 'URL des Lizenzservers';
$string['license_server_url_desc'] = 'Volle URL zum Verify-Endpunkt (z. B. http://204.168.247.140/zsk-license/api/v1/verify.php). Zuerst speichern, danach den Lizenzschlüssel eintragen.';
$string['license_grace_days'] = 'Offline-Toleranz (Tage)';
$string['license_grace_days_desc'] = 'Bei nicht erreichbarem Lizenzserver bleibt Premium für diese Anzahl Tage aktiv.';
$string['license_error_no_server'] = 'Keine Lizenzserver-URL konfiguriert.';
$string['license_error_network'] = 'Lizenzserver nicht erreichbar. Einstellungen wurden gespeichert; die Verifizierung wird später erneut versucht (täglicher Cron). Prüfen Sie URL, Firewall und ob der Server von Moodle aus erreichbar ist.';
$string['license_error_bad_response'] = 'Unerwartete Antwort vom Lizenzserver (HTTP {$a->httpcode}). Prüfen Sie die URL – sie muss auf …/api/v1/verify.php zeigen. Aktuell: {$a->url}';
$string['license_error_bad_response_short'] = 'Unerwartete Antwort vom Lizenzserver – URL prüfen (…/api/v1/verify.php).';
$string['license_warning_network_deferred'] = 'Lizenzserver derzeit nicht erreichbar. Schlüssel und URL wurden gespeichert; Verifizierung folgt automatisch, sobald der Server erreichbar ist.';
$string['license_diag_heading'] = 'Diagnose (Verify)';
$string['license_diag_wwwroot'] = 'site_url für Verify: {$a}';
$string['license_diag_server_url'] = 'Lizenzserver-URL: {$a}';
$string['license_diag_key_prefix'] = 'Schlüssel-Präfix: {$a}';
$string['license_diag_http_code'] = 'Letzter HTTP-Status: {$a}';
$string['license_diag_curl_error'] = 'cURL-Fehler: {$a}';
$string['license_diag_response'] = 'Server-Antwort (Auszug): {$a}';
$string['license_diag_cli_hint'] = 'CLI-Test auf dem Moodle-Server: php local/zsk_local_tiles/cli/test_license.php';
$string['license_error_expired'] = 'Die Lizenz ist abgelaufen.';
$string['license_error_invalid'] = 'Der Lizenzschlüssel ist ungültig.';
$string['license_error_site_mismatch'] = 'Dieser Lizenzschlüssel ist an eine andere Moodle-Instanz gebunden.';
$string['license_error_site_limit'] = 'Alle {$a} Umgebungs-Slots sind bereits belegt.';
$string['license_error_inactive'] = 'Dieser Lizenzschlüssel wurde deaktiviert.';
$string['license_error_plugin_mismatch'] = 'Dieser Lizenzschlüssel gilt nicht für dieses Plugin.';
$string['task_verify_license'] = 'ZSK Kachelansicht: Lizenz prüfen';

$string['cli_license_help'] = 'Lizenzprüfung von diesem Moodle-Server testen. Verwendung: php local/zsk_local_tiles/cli/test_license.php [--url=... --key=...]. Standardmäßig Plugin-Konfiguration.';

$string['cli_license_heading'] = 'ZSK Kachelansicht – Lizenztest';
$string['cli_license_wwwroot'] = 'Moodle wwwroot:  {$a}';
$string['cli_license_verifyurl'] = 'Verify-URL:     {$a}';
$string['cli_license_plugin'] = 'Plugin:         local_zsk_local_tiles';
$string['cli_license_keyprefix'] = 'Schlüsselprefix: {$a}';
$string['cli_license_empty'] = '(leer)';
$string['cli_license_missing_config'] = 'URL und Schlüssel erforderlich (in Plugin-Einstellungen oder via --url / --key).';
$string['cli_license_httpstatus'] = 'HTTP-Status:    {$a}';
$string['cli_license_curlerror'] = 'cURL-Fehler:    [{$a->errno}] {$a->error}';
$string['cli_license_response'] = 'Antwort:';
$string['cli_license_no_json'] = 'Keine JSON-Antwort – URL/Apache-Alias/Rechte prüfen.';
$string['cli_license_ok'] = 'Ergebnis: OK – Premium gültig bis {$a}';
$string['cli_license_failed'] = 'Verify fehlgeschlagen: {$a}';
$string['tilesettings_content_heading'] = 'Kachelinhalte';
$string['tilesettings_content_desc'] = 'Steuert, woher Bilder und Vorschautexte für die Kacheln kommen. Der Vorschautext wird auf maximal 300 Zeichen gekürzt.';
$string['tiles_content_source'] = 'Übernahme der Bilder und Texte für die Kacheln aus';
$string['tiles_content_source_desc'] = 'Kurseinstellungen: Kursbild und Kursbeschreibung bzw. Kursbereichsbeschreibung. Separat hochladen: eigene Pflege-Seiten für berechtigte Personen.';
$string['tiles_content_source_course'] = 'Kurseinstellungen';
$string['tiles_content_source_custom'] = 'Separat hochladen';
$string['nav_manage_tiles'] = 'Kachelinhalte pflegen';
$string['manageaccess'] = 'Berechtigte für Kachelinhalte';
$string['manageaccess_desc'] = 'Nur die hier eingetragenen Personen sehen den Navigationspunkt „Kachelinhalte pflegen“ und dürfen Bilder/Texte separat pflegen. Site-Administratoren erhalten diesen Navigationspunkt nicht automatisch.';
$string['manageaccess_saved'] = 'Berechtigte wurden gespeichert.';
$string['allowedusers'] = 'Berechtigte Nutzer';
$string['allowedusers_help'] = 'Wählen Sie die Nutzer, die Kachelinhalte unabhängig von den Kurseinstellungen pflegen dürfen.';
$string['manage_content_intro'] = 'Pflegen Sie Bilder und Vorschautexte für Kurs- und Kursbereichs-Kacheln. Mehrsprachige Texte können mit dem Moodle-Mehrsprachen-Filter erfasst werden.';
$string['manage_content_source_course_notice'] = 'Hinweis: In den Kachel-Einstellungen ist derzeit „Kurseinstellungen“ aktiv. Separat hochgeladene Inhalte werden erst genutzt, wenn die Weiche auf „Separat hochladen“ steht.';
$string['manage_content_saved'] = 'Kachelinhalte wurden gespeichert.';
$string['manage_courses'] = 'Kurs-Kacheln pflegen';
$string['manage_courses_intro'] = 'Wählen Sie einen Kursbereich und pflegen Sie Bild sowie Vorschautext für mehrere Kurse.';
$string['manage_courses_choose_category'] = 'Bitte zuerst einen Kursbereich wählen.';
$string['manage_courses_empty'] = 'In diesem Kursbereich sind keine Kurse vorhanden.';
$string['manage_categories'] = 'Kursbereichs-Kacheln pflegen';
$string['manage_categories_intro'] = 'Pflegen Sie Bild und Vorschautext für Kursbereiche. Optional können Sie einen übergeordneten Bereich wählen, um nur dessen Unterbereiche zu bearbeiten.';
$string['manage_categories_parent'] = 'Übergeordneter Kursbereich';
$string['manage_categories_top'] = 'Oberste Ebene';
$string['manage_categories_empty'] = 'Keine Kursbereiche auf dieser Ebene.';
$string['content_summary'] = 'Vorschautext (max. 300 Zeichen in der Kachel)';
$string['content_image'] = 'Kachelbild';
$string['content_multilang_hint'] = 'Mehrsprachigkeit: Texte können mit dem Moodle-Mehrsprachenfilter erfasst werden (z. B. {mlang de}…{mlang}{mlang en}…{mlang}). In der Kachel erscheint die Sprache der aktuellen Nutzeroberfläche; Anzeige max. 300 Zeichen.';
$string['backtohub'] = 'Zurück zur Übersicht';
$string['privacy:metadata'] = 'Das Plugin speichert eine Allowlist für die Pflege von Kachelinhalten sowie optionale Kacheltexte und den Bearbeiter.';
$string['privacy:metadata:allow'] = 'Nutzer, die Kachelinhalte pflegen dürfen.';
$string['privacy:metadata:allow:userid'] = 'Nutzer-ID';
$string['privacy:metadata:allow:timecreated'] = 'Zeitpunkt der Freigabe';
$string['privacy:metadata:allow:usermodified'] = 'Wer die Freigabe gesetzt hat';
$string['privacy:metadata:coursecontent'] = 'Separat gepflegte Kurs-Kacheltexte';
$string['privacy:metadata:categorycontent'] = 'Separat gepflegte Kursbereichs-Kacheltexte';
$string['privacy:metadata:content:usermodified'] = 'Zuletzt bearbeitet von';
$string['privacy:metadata:content:timemodified'] = 'Zeitpunkt der letzten Bearbeitung';
$string['privacy:path:allow'] = 'Kachel-Inhaltsrechte';
