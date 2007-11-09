-- phpMyAdmin SQL Dump
-- version 2.10.2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Erstellungszeit: 09. November 2007 um 19:56
-- Server Version: 5.0.45
-- PHP-Version: 5.2.3


-- 
-- Daten für Tabelle `auth_user_md5`
-- 

REPLACE INTO `auth_user_md5` (`user_id`, `username`, `password`, `perms`, `Vorname`, `Nachname`, `Email`, `auth_plugin`, `locked`, `lock_comment`, `locked_by`, `visible`) VALUES ('205f3efb7997a0fc9755da2b535038da', 'test_dozent', 'ae2b1fca515949e5d54fb22b8ed95575', 'dozent', 'Testaccount', 'Dozent', 'dozent@studip.de', NULL, 0, NULL, NULL, 'unknown');
REPLACE INTO `auth_user_md5` (`user_id`, `username`, `password`, `perms`, `Vorname`, `Nachname`, `Email`, `auth_plugin`, `locked`, `lock_comment`, `locked_by`, `visible`) VALUES ('6235c46eb9e962866ebdceece739ace5', 'test_admin', 'ae2b1fca515949e5d54fb22b8ed95575', 'admin', 'Testaccount', 'Admin', 'admin@studip.de', NULL, 0, NULL, NULL, 'unknown');
REPLACE INTO `auth_user_md5` (`user_id`, `username`, `password`, `perms`, `Vorname`, `Nachname`, `Email`, `auth_plugin`, `locked`, `lock_comment`, `locked_by`, `visible`) VALUES ('7e81ec247c151c02ffd479511e24cc03', 'test_tutor', 'ae2b1fca515949e5d54fb22b8ed95575', 'tutor', 'Testaccount', 'Tutor', 'tutor@studip.de', NULL, 0, NULL, NULL, 'unknown');
REPLACE INTO `auth_user_md5` (`user_id`, `username`, `password`, `perms`, `Vorname`, `Nachname`, `Email`, `auth_plugin`, `locked`, `lock_comment`, `locked_by`, `visible`) VALUES ('e7a0a84b161f3e8c09b4a0a2e8a58147', 'test_autor', 'ae2b1fca515949e5d54fb22b8ed95575', 'autor', 'Testaccount', 'Autor', 'autor@studip.de', NULL, 0, NULL, NULL, 'unknown');

-- 
-- Daten für Tabelle `aux_lock_rules`
-- 

REPLACE INTO `aux_lock_rules` (`lock_id`, `name`, `description`, `attributes`, `sorting`) VALUES ('d34f75dbb9936ba300086e096b718242', 'Standard', '', 'a:5:{s:10:"vasemester";s:1:"1";s:4:"vanr";s:1:"1";s:7:"vatitle";s:1:"0";s:8:"vadozent";s:1:"0";s:32:"ce73a10d07b3bb13c0132d363549efda";s:1:"1";}', 'a:5:{s:10:"vasemester";s:1:"0";s:4:"vanr";s:1:"0";s:7:"vatitle";s:1:"0";s:8:"vadozent";s:1:"0";s:32:"ce73a10d07b3bb13c0132d363549efda";s:1:"0";}');

-- 
-- Daten für Tabelle `banner_ads`
-- 


-- 
-- Daten für Tabelle `calendar_events`
-- 


-- 
-- Daten für Tabelle `chat_data`
-- 


-- 
-- Daten für Tabelle `comments`
-- 


-- 
-- Daten für Tabelle `config`
-- 

REPLACE INTO `config` (`config_id`, `parent_id`, `field`, `value`, `is_default`, `type`, `range`, `section`, `position`, `mkdate`, `chdate`, `description`, `comment`, `message_template`) VALUES ('7291d64d9cc4ea43ee9e8260f05a4111', '', 'MAIL_NOTIFICATION_ENABLE', '0', 1, 'boolean', 'global', '', 0, 1122996278, 1122996278, 'Informationen über neue Inhalte per email verschicken', '', '');
REPLACE INTO `config` (`config_id`, `parent_id`, `field`, `value`, `is_default`, `type`, `range`, `section`, `position`, `mkdate`, `chdate`, `description`, `comment`, `message_template`) VALUES ('9f6d7e248f58d1b211314dfb26c77d63', '', 'RESOURCES_ALLOW_DELETE_REQUESTS', '0', 1, 'boolean', 'global', '', 0, 1136826903, 1136826903, 'Erlaubt das Löschen von Raumanfragen für globale Ressourcenadmins', '', '');
REPLACE INTO `config` (`config_id`, `parent_id`, `field`, `value`, `is_default`, `type`, `range`, `section`, `position`, `mkdate`, `chdate`, `description`, `comment`, `message_template`) VALUES ('25bdaf939c88ee79bf3da54165d61a48', '', 'MAINTENANCE_MODE_ENABLE', '0', 1, 'boolean', 'global', '', 0, 1130840930, 1130840930, 'Schaltet das System in den Wartungsmodus, so dass nur noch Administratoren Zugriff haben', '', '');
REPLACE INTO `config` (`config_id`, `parent_id`, `field`, `value`, `is_default`, `type`, `range`, `section`, `position`, `mkdate`, `chdate`, `description`, `comment`, `message_template`) VALUES ('88c038ca4fb36764ff6486d72379e1ae', '', 'ZIP_UPLOAD_MAX_FILES', '100', 1, 'integer', 'global', '', 0, 1130840930, 1130840930, 'Die maximale Anzahl an Dateien, die bei einem Zipupload automatisch entpackt werden', '', '');
REPLACE INTO `config` (`config_id`, `parent_id`, `field`, `value`, `is_default`, `type`, `range`, `section`, `position`, `mkdate`, `chdate`, `description`, `comment`, `message_template`) VALUES ('c1f9ef95f501893c73e2654296c425f2', '', 'ZIP_UPLOAD_ENABLE', '1', 1, 'boolean', 'global', '', 0, 1130840930, 1130840930, 'Ermöglicht es, ein Zip Archiv hochzuladen, welches automatisch entpackt wird', '', '');
REPLACE INTO `config` (`config_id`, `parent_id`, `field`, `value`, `is_default`, `type`, `range`, `section`, `position`, `mkdate`, `chdate`, `description`, `comment`, `message_template`) VALUES ('d733eb0f9ef6db9fb3b461dd4df22376', '', 'ZIP_UPLOAD_MAX_DIRS', '10', 1, 'integer', 'global', '', 0, 1130840962, 1130840962, 'Die maximale Anzahl an Verzeichnissen, die bei einem Zipupload automatisch entpackt werden', '', '');
REPLACE INTO `config` (`config_id`, `parent_id`, `field`, `value`, `is_default`, `type`, `range`, `section`, `position`, `mkdate`, `chdate`, `description`, `comment`, `message_template`) VALUES ('1c07aa46c6fe6fea26d9b0cfd8fbcd19', '', 'SENDFILE_LINK_MODE', 'normal', 1, 'string', 'global', '', 0, 1141212096, 1141212096, 'Format der Downloadlinks: normal=sendfile.php?parameter=x, old=sendfile.php?/parameter=x, rewrite=download/parameter/file.txt', '', '');
REPLACE INTO `config` (`config_id`, `parent_id`, `field`, `value`, `is_default`, `type`, `range`, `section`, `position`, `mkdate`, `chdate`, `description`, `comment`, `message_template`) VALUES ('9d4956b4eac20f03b60b17d7ac30b40a', '', 'SEMESTER_TIME_SWITCH', '0', 1, 'integer', 'global', '', 0, 1140013696, 1140013696, 'Anzahl der Wochen vor Semesterende zu dem das vorgewählte Semester umspringt', '', '');
REPLACE INTO `config` (`config_id`, `parent_id`, `field`, `value`, `is_default`, `type`, `range`, `section`, `position`, `mkdate`, `chdate`, `description`, `comment`, `message_template`) VALUES ('06cdb765fb8f0853e3ebe08f51c3596e', '', 'RESOURCES_ENABLE', '0', 1, 'boolean', 'global', '', 0, 0, 0, 'Enable the Stud.IP resource management module', '', '');
REPLACE INTO `config` (`config_id`, `parent_id`, `field`, `value`, `is_default`, `type`, `range`, `section`, `position`, `mkdate`, `chdate`, `description`, `comment`, `message_template`) VALUES ('3d415eca6003321f09e59407e4a7994d', '', 'RESOURCES_LOCKING_ACTIVE', '', 1, 'boolean', 'global', 'resources', 0, 0, 1100709567, 'Schaltet in der Ressourcenverwaltung das Blockieren der Bearbeitung für einen Zeitraum aus (nur Admins dürfen in dieser Zeit auf die Belegung zugreifen)', '', '');
REPLACE INTO `config` (`config_id`, `parent_id`, `field`, `value`, `is_default`, `type`, `range`, `section`, `position`, `mkdate`, `chdate`, `description`, `comment`, `message_template`) VALUES ('b7a2817d142443245df2f5ac587fe218', '', 'RESOURCES_ALLOW_ROOM_REQUESTS', '', 1, 'boolean', 'global', '', 0, 0, 1100709567, 'Schaltet in der Ressourcenverwaltung das System zum Stellen und Bearbeiten von Raumanfragen ein oder aus', '', '');
REPLACE INTO `config` (`config_id`, `parent_id`, `field`, `value`, `is_default`, `type`, `range`, `section`, `position`, `mkdate`, `chdate`, `description`, `comment`, `message_template`) VALUES ('d821ffbff29ce636c6763ffe3fd8b427', '', 'RESOURCES_ALLOW_CREATE_ROOMS', '2', 1, 'integer', 'global', '', 0, 0, 1100709567, 'Welche Rechstufe darf  Räume anlegen? 1 = Nutzer ab Status tutor, 2 = Nutzer ab Status admin, 3 = nur Ressourcenadministratoren', '', '');
REPLACE INTO `config` (`config_id`, `parent_id`, `field`, `value`, `is_default`, `type`, `range`, `section`, `position`, `mkdate`, `chdate`, `description`, `comment`, `message_template`) VALUES ('5a6e2342b90530ed50ad8497054420c0', '', 'RESOURCES_ALLOW_ROOM_PROPERTY_REQUESTS', '1', 1, 'boolean', 'global', '', 0, 0, 1074780851, 'Schaltet in der Ressourcenverwaltung die Möglichkeit, im Rahmen einer Anfrage Raumeigenschaften zu wünschen, ein oder aus', '', '');
REPLACE INTO `config` (`config_id`, `parent_id`, `field`, `value`, `is_default`, `type`, `range`, `section`, `position`, `mkdate`, `chdate`, `description`, `comment`, `message_template`) VALUES ('e4123cf9158cd0b936144f0f4cf8dfa3', '', 'RESOURCES_INHERITANCE_PERMS_ROOMS', '1', 1, 'integer', 'global', '', 0, 0, 1100709567, 'Art der Rechtevererbung in der Ressourcenverwaltung für Räume: 1 = lokale Rechte der Einrichtung und Veranstaltung werden übertragen, 2 = nur Autorenrechte werden vergeben, 3 = es werden keine Rechte vergeben', '', '');
REPLACE INTO `config` (`config_id`, `parent_id`, `field`, `value`, `is_default`, `type`, `range`, `section`, `position`, `mkdate`, `chdate`, `description`, `comment`, `message_template`) VALUES ('45856b1e3407ce565d87ec9b8fd32d7d', '', 'RESOURCES_INHERITANCE_PERMS', '1', 1, 'integer', 'global', '', 0, 0, 1100709567, 'Art der Rechtevererbung in der Ressourcenverwaltung für Ressourcen (nicht Räume): 1 = lokale Rechte der Einrichtung und Veranstaltung werden übertragen, 2 = nur Autorenrechte werden vergeben, 3 = es werden keine Rechte vergeben', '', '');
REPLACE INTO `config` (`config_id`, `parent_id`, `field`, `value`, `is_default`, `type`, `range`, `section`, `position`, `mkdate`, `chdate`, `description`, `comment`, `message_template`) VALUES ('c353c73d8f37e3c301ae34898c837af4', '', 'RESOURCES_ENABLE_ORGA_CLASSIFY', '1', 1, 'boolean', 'global', '', 0, 0, 1100709567, 'Schaltet in der Ressourcenverwaltung das Einordnen von Ressourcen in Orga-Struktur (ohne Rechtevergabe) ein oder aus', '', '');
REPLACE INTO `config` (`config_id`, `parent_id`, `field`, `value`, `is_default`, `type`, `range`, `section`, `position`, `mkdate`, `chdate`, `description`, `comment`, `message_template`) VALUES ('0821671742242add144595b1112399fb', '', 'RESOURCES_ALLOW_SINGLE_ASSIGN_PERCENTAGE', '50', 1, 'integer', 'global', '', 0, 0, 1100709567, 'Wert (in Prozent), ab dem ein Raum mit Einzelbelegungen (statt Serienbelegungen) gefüllt wird, wenn dieser Anteil an möglichen Belegungen bereits durch andere Belegungen zu Überschneidungen führt', '', '');
REPLACE INTO `config` (`config_id`, `parent_id`, `field`, `value`, `is_default`, `type`, `range`, `section`, `position`, `mkdate`, `chdate`, `description`, `comment`, `message_template`) VALUES ('94d1643209a8f404dfe71228aad5345d', '', 'RESOURCES_ALLOW_SINGLE_DATE_GROUPING', '5', 1, 'integer', 'global', '', 0, 0, 1100709567, 'Anzahl an Einzeltermine, ab der diese als Gruppe zusammengefasst bearbeitet werden', '', '');
REPLACE INTO `config` (`config_id`, `parent_id`, `field`, `value`, `is_default`, `type`, `range`, `section`, `position`, `mkdate`, `chdate`, `description`, `comment`, `message_template`) VALUES ('074ccc86f0313dd695dc8e3ec3cebe73', '', 'HTML_HEAD_TITLE', 'Stud.IP', 1, 'string', 'global', '', 0, 0, 0, 'Angezeigter Titel in der Kopfzeile des Browsers', '', '');
REPLACE INTO `config` (`config_id`, `parent_id`, `field`, `value`, `is_default`, `type`, `range`, `section`, `position`, `mkdate`, `chdate`, `description`, `comment`, `message_template`) VALUES ('f2f8a47ea69ed9ccba5573e85a15662c', '', 'ACCESSKEY_ENABLE', '', 1, 'boolean', 'user', '', 0, 0, 0, 'Schaltet die Nutzung von Shortcuts für einen User ein oder aus, Systemdefault', '', '');
REPLACE INTO `config` (`config_id`, `parent_id`, `field`, `value`, `is_default`, `type`, `range`, `section`, `position`, `mkdate`, `chdate`, `description`, `comment`, `message_template`) VALUES ('0b00c75bc76abe0dd132570403b38e5c', '', 'NEWS_RSS_EXPORT_ENABLE', '1', 1, 'boolean', 'global', '', 0, 0, 0, 'Schaltet die Möglichkeit des rss-Export von privaten News global ein oder aus', '', '');
REPLACE INTO `config` (`config_id`, `parent_id`, `field`, `value`, `is_default`, `type`, `range`, `section`, `position`, `mkdate`, `chdate`, `description`, `comment`, `message_template`) VALUES ('42d237f9dfd852318cdc66319043536d', '', 'FOAF_SHOW_IDENTITY', '', 1, 'boolean', 'user', '', 0, 0, 0, 'Schaltet für einen User ein oder aus, ob dieser in FOAS-Ketten angezeigt wird, Systemdefault', '', '');
REPLACE INTO `config` (`config_id`, `parent_id`, `field`, `value`, `is_default`, `type`, `range`, `section`, `position`, `mkdate`, `chdate`, `description`, `comment`, `message_template`) VALUES ('6ae7aecf299930cbb8a5e89bbab4da55', '', 'FOAF_ENABLE', '1', 1, 'boolean', 'global', '', 0, 0, 0, 'FOAF Feature benutzen?', '', '');
REPLACE INTO `config` (`config_id`, `parent_id`, `field`, `value`, `is_default`, `type`, `range`, `section`, `position`, `mkdate`, `chdate`, `description`, `comment`, `message_template`) VALUES ('a52e3b62ac0bee819b782d8979960b7b', '', 'RESOURCES_ENABLE_GROUPING', '1', 1, 'boolean', 'global', '', 0, 0, 1121861801, 'Schaltet in der Ressourcenverwaltung die Funktion zur Verwaltung von Raumgruppen ein oder aus', '', '');
REPLACE INTO `config` (`config_id`, `parent_id`, `field`, `value`, `is_default`, `type`, `range`, `section`, `position`, `mkdate`, `chdate`, `description`, `comment`, `message_template`) VALUES ('76cac679fa57fdbb3f9d6cee20bf8c6f', '', 'RESOURCES_ENABLE_SEM_SCHEDULE', '1', 1, 'boolean', 'global', '', 0, 0, 0, 'Schaltet in der Ressourcenverwaltung ein, ob ein Semesterbelegungsplan erstellt werden kann', '', '');
REPLACE INTO `config` (`config_id`, `parent_id`, `field`, `value`, `is_default`, `type`, `range`, `section`, `position`, `mkdate`, `chdate`, `description`, `comment`, `message_template`) VALUES ('3af783748f92cdf99b066d4227f8dffc', '', 'RESOURCES_SEARCH_ONLY_REQUESTABLE_PROPERTY', '1', 1, 'boolean', 'global', '', 0, 0, 0, 'Schaltet in der Suche der Ressourcenverwaltun das Durchsuchen von nicht wünschbaren Eigenschaften ein oder aus', '', '');
REPLACE INTO `config` (`config_id`, `parent_id`, `field`, `value`, `is_default`, `type`, `range`, `section`, `position`, `mkdate`, `chdate`, `description`, `comment`, `message_template`) VALUES ('fe498bb91a4cbfdfd5078915e979153c', '', 'RESOURCES_ENABLE_VIRTUAL_ROOM_GROUPS', '1', 1, 'boolean', 'global', '', 0, 0, 0, 'Schaltet in der Ressourcenverwaltung automatische gebildete Raumgruppen neben per Konfigurationsdatei definierten Gruppen ein oder aus', '', '');
REPLACE INTO `config` (`config_id`, `parent_id`, `field`, `value`, `is_default`, `type`, `range`, `section`, `position`, `mkdate`, `chdate`, `description`, `comment`, `message_template`) VALUES ('68b127dde744085637d221e11d4e8cf2', '', 'RESOURCES_ALLOW_CREATE_TOP_LEVEL', '', 1, 'boolean', 'global', '', 0, 0, 0, 'Schaltet für die Ressourcenverwaltung ein, ob neue Hierachieebenen von anderen Nutzern als Admins angelegt werden können oder nicht', '', '');
REPLACE INTO `config` (`config_id`, `parent_id`, `field`, `value`, `is_default`, `type`, `range`, `section`, `position`, `mkdate`, `chdate`, `description`, `comment`, `message_template`) VALUES ('b16359d5514b13794689eab669124c69', '', 'ALLOW_DOZENT_VISIBILITY', '', 1, 'boolean', 'global', '', 0, 0, 0, 'Schaltet ein oder aus, ob ein Dozent eigene Veranstaltungen selbst verstecken darf oder nicht', '', '');
REPLACE INTO `config` (`config_id`, `parent_id`, `field`, `value`, `is_default`, `type`, `range`, `section`, `position`, `mkdate`, `chdate`, `description`, `comment`, `message_template`) VALUES ('e8cd96580149cde65ad69b6cf18d5c39', '', 'ALLOW_DOZENT_ARCHIV', '', 1, 'boolean', 'global', '', 0, 0, 1109946684, 'Schaltet ein oder aus, ob ein Dozent eigene Veranstaltungen selbst archivieren darf oder nicht', '', '');
REPLACE INTO `config` (`config_id`, `parent_id`, `field`, `value`, `is_default`, `type`, `range`, `section`, `position`, `mkdate`, `chdate`, `description`, `comment`, `message_template`) VALUES ('24ecbeb431826c61fd8b53b3aa41bfa6', '', 'SHOWSEM_ENABLE', '1', 1, 'boolean', 'user', '', 0, 1122461027, 1122461027, 'Einstellung für Nutzer, ob Semesterangaben in der Übersicht "Meine Veranstaltung" nach dem Titel der Veranstaltung gemacht werden; Systemdefault', '', '');
REPLACE INTO `config` (`config_id`, `parent_id`, `field`, `value`, `is_default`, `type`, `range`, `section`, `position`, `mkdate`, `chdate`, `description`, `comment`, `message_template`) VALUES ('91e6e53b3748a53c42440453e8045be3', '', 'RESOURCES_ALLOW_SEMASSI_SKIP_REQUEST', '1', 1, 'boolean', 'global', '', 0, 1122565305, 1122565305, 'Schaltet das Pflicht, eine Raumanfrage beim Anlegen einer Veranstaltung machen zu müssen, ein oder aus', '', '');
REPLACE INTO `config` (`config_id`, `parent_id`, `field`, `value`, `is_default`, `type`, `range`, `section`, `position`, `mkdate`, `chdate`, `description`, `comment`, `message_template`) VALUES ('f32367b1542a1d513ecee8a26e26d239', '', 'RESOURCES_SCHEDULE_EXPLAIN_USER_NAME', '1', 1, 'boolean', 'global', '', 0, 1123516671, 1123516671, 'Schaltet in der Ressourcenverwaltung die Anzeige der Namen des Belegers in der Ausgabe von Belegungsplänen ein oder aus', '', '');
REPLACE INTO `config` (`config_id`, `parent_id`, `field`, `value`, `is_default`, `type`, `range`, `section`, `position`, `mkdate`, `chdate`, `description`, `comment`, `message_template`) VALUES ('4c52bfa598daa03944a401b66c53d828', '', 'NEWS_DISABLE_GARBAGE_COLLECT', '0', 1, 'boolean', 'global', '', 0, 1123751948, 1123751948, 'Schaltet den Garbage-Collect für News ein oder aus', '', '');
REPLACE INTO `config` (`config_id`, `parent_id`, `field`, `value`, `is_default`, `type`, `range`, `section`, `position`, `mkdate`, `chdate`, `description`, `comment`, `message_template`) VALUES ('9e0579653e585a688665a6ea2e2d7c90', '', 'EVAL_AUSWERTUNG_CONFIG_ENABLE', '1', 1, 'boolean', 'global', '', 0, 1141225624, 1141225624, 'Ermöglicht es dem Nutzer, die grafische Darstellung der Evaluationsauswertung zu konfigurieren', '', '');
REPLACE INTO `config` (`config_id`, `parent_id`, `field`, `value`, `is_default`, `type`, `range`, `section`, `position`, `mkdate`, `chdate`, `description`, `comment`, `message_template`) VALUES ('0ad11a4cafa548d3c72a3dc1776568d8', '', 'EVAL_AUSWERTUNG_GRAPH_FORMAT', 'jpg', 1, 'string', 'global', '', 0, 1141225624, 1141225624, 'Das Format, in dem die Diagramme der grafischen Evaluationsauswertung erstellt werden (jpg, png, gif).', '', '');
REPLACE INTO `config` (`config_id`, `parent_id`, `field`, `value`, `is_default`, `type`, `range`, `section`, `position`, `mkdate`, `chdate`, `description`, `comment`, `message_template`) VALUES ('781e0998a1b5c998ebbc02a4f0d907ac', '', 'USER_VISIBILITY_UNKNOWN', '1', 1, 'boolean', 'global', '', 0, 1153815901, 1153815901, 'Sollen Nutzer mit Sichtbarkeit "unknown" wie sichtbare behandelt werden?', '', '');
REPLACE INTO `config` (`config_id`, `parent_id`, `field`, `value`, `is_default`, `type`, `range`, `section`, `position`, `mkdate`, `chdate`, `description`, `comment`, `message_template`) VALUES ('3ca9d678f11a73917420161180838205', '', 'CHAT_USE_AJAX_CLIENT', '0', 1, 'boolean', 'user', '', 0, 1153815830, 1153815830, 'Einstellung für Nutzer, ob der AJAX chatclient benutzt werden soll (experimental); Systemdefault', '', '');
REPLACE INTO `config` (`config_id`, `parent_id`, `field`, `value`, `is_default`, `type`, `range`, `section`, `position`, `mkdate`, `chdate`, `description`, `comment`, `message_template`) VALUES ('54ad03142e6704434976c9a0df8329c8', '', 'ONLINE_NAME_FORMAT', 'full_rev', 1, 'string', 'user', '', 0, 1153814980, 1153814980, 'Default-Wert für wer-ist-online Namensformatierung', '', '');
REPLACE INTO `config` (`config_id`, `parent_id`, `field`, `value`, `is_default`, `type`, `range`, `section`, `position`, `mkdate`, `chdate`, `description`, `comment`, `message_template`) VALUES ('8a147b2d487d7ae91264f03cab5d8c07', '', 'ADMISSION_PRELIM_COMMENT_ENABLE', '0', 1, 'boolean', 'global', '', 0, 1153814966, 1153814966, 'Schaltet ein oder aus, ob ein Nutzer im Modus "Vorläufiger Eintrag" eine Bemerkung hinterlegen kann', '', '');
REPLACE INTO `config` (`config_id`, `parent_id`, `field`, `value`, `is_default`, `type`, `range`, `section`, `position`, `mkdate`, `chdate`, `description`, `comment`, `message_template`) VALUES ('a93eb21bb08719b3a522b7e238bd8b7e', '', 'EXTERNAL_HELP', '1', 1, 'boolean', 'global', '', 0, 1155128579, 1155128579, 'Schaltet das externe Hilfesystem ein', '', '');
REPLACE INTO `config` (`config_id`, `parent_id`, `field`, `value`, `is_default`, `type`, `range`, `section`, `position`, `mkdate`, `chdate`, `description`, `comment`, `message_template`) VALUES ('10367c279370c7f78552d2747c2b169c', '', 'EXTERNAL_HELP_LOCATIONID', 'default', 1, 'string', 'global', '', 0, 1155128579, 1155128579, 'Eine eindeutige ID zur Identifikation der gewünschten Hilfeseiten, leer bedeutet Standardhilfe', '', '');
REPLACE INTO `config` (`config_id`, `parent_id`, `field`, `value`, `is_default`, `type`, `range`, `section`, `position`, `mkdate`, `chdate`, `description`, `comment`, `message_template`) VALUES ('6679a9cf02e56c0fce92e91b8f696005', '', 'EXTERNAL_HELP_URL', 'http://hilfe.studip.de/index.php/%s', 1, 'string', 'global', '', 0, 1155128579, 1155128579, 'URL Template für das externe Hilfesystem', '', '');
REPLACE INTO `config` (`config_id`, `parent_id`, `field`, `value`, `is_default`, `type`, `range`, `section`, `position`, `mkdate`, `chdate`, `description`, `comment`, `message_template`) VALUES ('4cd2cd3cc207ffc0ae92721c291cd906', '', 'RESOURCES_SHOW_ROOM_NOT_BOOKED_HINT', '0', 1, 'boolean', 'global', '', 0, 1168444600, 1168444600, 'Einstellung, ob bei aktivierter Raumverwaltung Raumangaben die nicht gebucht sind gekennzeichnet werden', '', '');
REPLACE INTO `config` (`config_id`, `parent_id`, `field`, `value`, `is_default`, `type`, `range`, `section`, `position`, `mkdate`, `chdate`, `description`, `comment`, `message_template`) VALUES ('3b6a1623b8e0913430d6a27bfda976fd', '', 'ADMISSION_ALLOW_DISABLE_WAITLIST', '1', 1, 'boolean', 'global', '', 0, 1170242650, 1170242650, 'Schaltet ein oder aus, ob die Warteliste in Zugangsbeschränkten Veranstaltungen deaktiviert werden kann', '', '');
REPLACE INTO `config` (`config_id`, `parent_id`, `field`, `value`, `is_default`, `type`, `range`, `section`, `position`, `mkdate`, `chdate`, `description`, `comment`, `message_template`) VALUES ('08f085d9ef2ee7d8b355dcc35282ab8c', '', 'ENABLE_SKYPE_INFO', '1', 1, 'boolean', 'global', '', 0, 1170242666, 1170242666, 'Ermöglicht die Eingabe / Anzeige eines Skype Namens ', '', '');
REPLACE INTO `config` (`config_id`, `parent_id`, `field`, `value`, `is_default`, `type`, `range`, `section`, `position`, `mkdate`, `chdate`, `description`, `comment`, `message_template`) VALUES ('615e92cdf78c1436c3fc1f60a8cd944e', '', 'SEM_VISIBILITY_PERM', 'root', 1, 'string', 'global', '', 0, 1170242706, 1170242706, 'Bestimmt den globalen Nutzerstatus, ab dem versteckte Veranstaltungen in der Suche gefunden werden (root,admin,dozent)', '', '');
REPLACE INTO `config` (`config_id`, `parent_id`, `field`, `value`, `is_default`, `type`, `range`, `section`, `position`, `mkdate`, `chdate`, `description`, `comment`, `message_template`) VALUES ('4158d433b57052b20fd66d84b71c7324', '', 'SEM_CREATE_PERM', 'dozent', 1, 'string', 'global', '', 0, 1170242930, 1170242930, 'Bestimmt den globalen Nutzerstatus, ab dem Veranstaltungen angelegt werden dürfen (root,admin,dozent)', '', '');
REPLACE INTO `config` (`config_id`, `parent_id`, `field`, `value`, `is_default`, `type`, `range`, `section`, `position`, `mkdate`, `chdate`, `description`, `comment`, `message_template`) VALUES ('93da66ca9e2d17df5bc61bd56406add7', '', 'RESOURCES_ROOM_REQUEST_DEFAULT_ACTION', 'NO_ROOM_INFO_ACTION', 1, 'string', 'global', '', 0, 0, 0, 'Designates the pre-selected action for the room request dialog', 'Valid values are: NO_ROOM_INFO_ACTION, ROOM_REQUEST_ACTION, BOOKING_OF_ROOM_ACTION, FREETEXT_ROOM_ACTION', '');
REPLACE INTO `config` (`config_id`, `parent_id`, `field`, `value`, `is_default`, `type`, `range`, `section`, `position`, `mkdate`, `chdate`, `description`, `comment`, `message_template`) VALUES ('0d3f84ed4dd6b7147b504ffb5b6fbc2c', '', 'RESOURCES_ENABLE_EXPERT_SCHEDULE_VIEW', '0', 1, 'boolean', 'global', '', 0, 12, 12, 'Enables the expert view of the course schedules', '', '');
REPLACE INTO `config` (`config_id`, `parent_id`, `field`, `value`, `is_default`, `type`, `range`, `section`, `position`, `mkdate`, `chdate`, `description`, `comment`, `message_template`) VALUES ('bc3004618b17b29dc65e10e89be9a7a0', '', 'RESOURCES_ENABLE_BOOKINGSTATUS_COLORING', '1', 1, 'boolean', 'global', '', 0, 0, 0, 'Enable the colored presentation of the room booking status of a date', '', '');
REPLACE INTO `config` (`config_id`, `parent_id`, `field`, `value`, `is_default`, `type`, `range`, `section`, `position`, `mkdate`, `chdate`, `description`, `comment`, `message_template`) VALUES ('0c81083086adc66714864b1abcff650a', '', 'EXTERNAL_IMAGE_EMBEDDING', 'deny', 1, 'string', 'global', '', 0, 0, 0, 'Sollen externe Bilder über [img] eingebunden werden? deny=nicht erlaubt, allow=erlaubt, proxy=image proxy benutzen', '', '');

-- 
-- Daten für Tabelle `contact`
-- 


-- 
-- Daten für Tabelle `contact_userinfo`
-- 


-- 
-- Daten für Tabelle `datafields`
-- 

REPLACE INTO `datafields` (`datafield_id`, `name`, `object_type`, `object_class`, `edit_perms`, `view_perms`, `priority`, `mkdate`, `chdate`, `type`, `typeparam`) VALUES ('ce73a10d07b3bb13c0132d363549efda', 'Nationalität', 'user', NULL, 'user', 'all', 0, NULL, NULL, 'textline', '');

-- 
-- Daten für Tabelle `datafields_entries`
-- 


-- 
-- Daten für Tabelle `dokumente`
-- 

REPLACE INTO `dokumente` (`dokument_id`, `range_id`, `user_id`, `seminar_id`, `name`, `description`, `filename`, `mkdate`, `chdate`, `filesize`, `autor_host`, `downloads`, `url`, `protected`) VALUES ('c51a12e44c667b370fe2c497fbfc3c21', '823b5c771f17d4103b1828251c29a7cb', '76ed43ef286fb55cf9e41beadb484a9f', '834499e2b8a2cd71637890e5de31cba3', 'Stud.IP-Produktbroschüre im PDF-Format', '', 'studip_broschuere.pdf', 1156516698, 1156516698, 295294, '217.94.188.5', 3, 'http://www.studip.de/download/studip_broschuere.pdf', 0);

-- 
-- Daten für Tabelle `eval`
-- 


-- 
-- Daten für Tabelle `evalanswer`
-- 

REPLACE INTO `evalanswer` (`evalanswer_id`, `parent_id`, `position`, `text`, `value`, `rows`, `counter`, `residual`) VALUES ('d67301d4f59aa35d1e3f12a9791b6885', 'ef227e91618878835d52cfad3e6d816b', 0, 'Sehr gut', 1, 0, 0, 0);
REPLACE INTO `evalanswer` (`evalanswer_id`, `parent_id`, `position`, `text`, `value`, `rows`, `counter`, `residual`) VALUES ('7052b76e616656e4b70f1c504c04ec81', 'ef227e91618878835d52cfad3e6d816b', 1, '', 2, 0, 0, 0);
REPLACE INTO `evalanswer` (`evalanswer_id`, `parent_id`, `position`, `text`, `value`, `rows`, `counter`, `residual`) VALUES ('64152ace8f2a74d0efb67c54eff64a2b', 'ef227e91618878835d52cfad3e6d816b', 2, '', 3, 0, 0, 0);
REPLACE INTO `evalanswer` (`evalanswer_id`, `parent_id`, `position`, `text`, `value`, `rows`, `counter`, `residual`) VALUES ('3a3ab5307f39ea039d41fb6f2683475e', 'ef227e91618878835d52cfad3e6d816b', 3, '', 4, 0, 0, 0);
REPLACE INTO `evalanswer` (`evalanswer_id`, `parent_id`, `position`, `text`, `value`, `rows`, `counter`, `residual`) VALUES ('6115b19f694ccd3d010a0047ff8f970a', 'ef227e91618878835d52cfad3e6d816b', 4, 'Sehr Schlecht', 5, 0, 0, 0);
REPLACE INTO `evalanswer` (`evalanswer_id`, `parent_id`, `position`, `text`, `value`, `rows`, `counter`, `residual`) VALUES ('be4c3e5fe0b2b735bb3b2712afa8c490', 'ef227e91618878835d52cfad3e6d816b', 5, 'Keine Meinung', 6, 0, 0, 1);
REPLACE INTO `evalanswer` (`evalanswer_id`, `parent_id`, `position`, `text`, `value`, `rows`, `counter`, `residual`) VALUES ('84be4c31449a9c1807bf2dea0dc869f1', '724244416b5d04a4d8f4eab8a86fdbf8', 0, 'Sehr gut', 1, 0, 0, 0);
REPLACE INTO `evalanswer` (`evalanswer_id`, `parent_id`, `position`, `text`, `value`, `rows`, `counter`, `residual`) VALUES ('c446970d2addd68e43c2a6cae6117bf7', '724244416b5d04a4d8f4eab8a86fdbf8', 1, 'Gut', 2, 0, 0, 0);
REPLACE INTO `evalanswer` (`evalanswer_id`, `parent_id`, `position`, `text`, `value`, `rows`, `counter`, `residual`) VALUES ('3d4dcedb714dfdcfbe65cd794b4d404b', '724244416b5d04a4d8f4eab8a86fdbf8', 2, 'Befriedigend', 3, 0, 0, 0);
REPLACE INTO `evalanswer` (`evalanswer_id`, `parent_id`, `position`, `text`, `value`, `rows`, `counter`, `residual`) VALUES ('fa2bf667ba73ae74794df35171c2ad2e', '724244416b5d04a4d8f4eab8a86fdbf8', 3, 'Ausreichend', 4, 0, 0, 0);
REPLACE INTO `evalanswer` (`evalanswer_id`, `parent_id`, `position`, `text`, `value`, `rows`, `counter`, `residual`) VALUES ('0be387b9379a05c5578afce64b0c688f', '724244416b5d04a4d8f4eab8a86fdbf8', 4, 'Mangelhaft', 5, 0, 0, 0);
REPLACE INTO `evalanswer` (`evalanswer_id`, `parent_id`, `position`, `text`, `value`, `rows`, `counter`, `residual`) VALUES ('aec07dd525f2610bdd10bf778aa1893b', '724244416b5d04a4d8f4eab8a86fdbf8', 5, 'Nicht erteilt', 6, 0, 0, 1);
REPLACE INTO `evalanswer` (`evalanswer_id`, `parent_id`, `position`, `text`, `value`, `rows`, `counter`, `residual`) VALUES ('7080335582e2787a54f315ec8cef631e', '95bbae27965d3404f7fa3af058850bd3', 0, 'trifft völlig zu', 1, 0, 0, 0);
REPLACE INTO `evalanswer` (`evalanswer_id`, `parent_id`, `position`, `text`, `value`, `rows`, `counter`, `residual`) VALUES ('d68a74dc2c1f0ce226366da918dd161d', '95bbae27965d3404f7fa3af058850bd3', 1, 'trifft ziemlich zu', 2, 0, 0, 0);
REPLACE INTO `evalanswer` (`evalanswer_id`, `parent_id`, `position`, `text`, `value`, `rows`, `counter`, `residual`) VALUES ('641686e7c61899b303cda106f20064e7', '95bbae27965d3404f7fa3af058850bd3', 2, 'teilsteils', 3, 0, 0, 0);
REPLACE INTO `evalanswer` (`evalanswer_id`, `parent_id`, `position`, `text`, `value`, `rows`, `counter`, `residual`) VALUES ('7c36d074f2cc38765c982c9dfb769afc', '95bbae27965d3404f7fa3af058850bd3', 3, 'trifft wenig zu', 4, 0, 0, 0);
REPLACE INTO `evalanswer` (`evalanswer_id`, `parent_id`, `position`, `text`, `value`, `rows`, `counter`, `residual`) VALUES ('5c4827f903168ed4483db5386a9ad5b8', '95bbae27965d3404f7fa3af058850bd3', 4, 'trifft gar nicht zu', 5, 0, 0, 0);
REPLACE INTO `evalanswer` (`evalanswer_id`, `parent_id`, `position`, `text`, `value`, `rows`, `counter`, `residual`) VALUES ('c10a3f4e97f8badc5230a9900afde0c7', '95bbae27965d3404f7fa3af058850bd3', 5, 'kann ich nicht beurteilen', 6, 0, 0, 1);
REPLACE INTO `evalanswer` (`evalanswer_id`, `parent_id`, `position`, `text`, `value`, `rows`, `counter`, `residual`) VALUES ('ced33706ca95aff2163c7d0381ef5717', '6fddac14c1f2ac490b93681b3da5fc66', 0, 'Montag', 1, 0, 0, 0);
REPLACE INTO `evalanswer` (`evalanswer_id`, `parent_id`, `position`, `text`, `value`, `rows`, `counter`, `residual`) VALUES ('087c734855c8a5b34d99c16ad09cd312', '6fddac14c1f2ac490b93681b3da5fc66', 1, 'Dienstag', 2, 0, 0, 0);
REPLACE INTO `evalanswer` (`evalanswer_id`, `parent_id`, `position`, `text`, `value`, `rows`, `counter`, `residual`) VALUES ('63f5011614f45329cc396b90d94a7096', '6fddac14c1f2ac490b93681b3da5fc66', 2, 'Mittwoch', 3, 0, 0, 0);
REPLACE INTO `evalanswer` (`evalanswer_id`, `parent_id`, `position`, `text`, `value`, `rows`, `counter`, `residual`) VALUES ('ccd1eaddccca993f6789659b36f40506', '6fddac14c1f2ac490b93681b3da5fc66', 3, 'Donnerstag', 4, 0, 0, 0);
REPLACE INTO `evalanswer` (`evalanswer_id`, `parent_id`, `position`, `text`, `value`, `rows`, `counter`, `residual`) VALUES ('48842cedeac739468741940982b5fe6d', '6fddac14c1f2ac490b93681b3da5fc66', 4, 'Freitag', 5, 0, 0, 0);
REPLACE INTO `evalanswer` (`evalanswer_id`, `parent_id`, `position`, `text`, `value`, `rows`, `counter`, `residual`) VALUES ('21b3f7cf2de5cbb098d800f344d399ee', '12e508079c4770fb13c9fce028f40cac', 0, 'Montag', 1, 0, 0, 0);
REPLACE INTO `evalanswer` (`evalanswer_id`, `parent_id`, `position`, `text`, `value`, `rows`, `counter`, `residual`) VALUES ('f0016e918b5bc5c4cf3cc62bf06fa2e9', '12e508079c4770fb13c9fce028f40cac', 1, 'Dienstag', 2, 0, 0, 0);
REPLACE INTO `evalanswer` (`evalanswer_id`, `parent_id`, `position`, `text`, `value`, `rows`, `counter`, `residual`) VALUES ('c88242b50ff0bb43df32c1e15bdaca22', '12e508079c4770fb13c9fce028f40cac', 2, 'Mittwoch', 3, 0, 0, 0);
REPLACE INTO `evalanswer` (`evalanswer_id`, `parent_id`, `position`, `text`, `value`, `rows`, `counter`, `residual`) VALUES ('b39860f6601899dcf87ba71944c57bc7', '12e508079c4770fb13c9fce028f40cac', 3, 'Donnerstag', 4, 0, 0, 0);
REPLACE INTO `evalanswer` (`evalanswer_id`, `parent_id`, `position`, `text`, `value`, `rows`, `counter`, `residual`) VALUES ('568d6fd620642cb7395c27d145a76734', '12e508079c4770fb13c9fce028f40cac', 4, 'Freitag', 5, 0, 0, 0);
REPLACE INTO `evalanswer` (`evalanswer_id`, `parent_id`, `position`, `text`, `value`, `rows`, `counter`, `residual`) VALUES ('39b98a5560d5dabaf67227e2895db8da', 'a68bd711902f23bd5c55a29f1ecaa095', 0, '', 1, 5, 0, 0);
REPLACE INTO `evalanswer` (`evalanswer_id`, `parent_id`, `position`, `text`, `value`, `rows`, `counter`, `residual`) VALUES ('61ae27ab33c402316a3f1eb74e1c46ab', '442e1e464e12498bd238a7767215a5a2', 0, '', 1, 1, 0, 0);

-- 
-- Daten für Tabelle `evalanswer_user`
-- 


-- 
-- Daten für Tabelle `evalgroup`
-- 


-- 
-- Daten für Tabelle `evalquestion`
-- 

REPLACE INTO `evalquestion` (`evalquestion_id`, `parent_id`, `type`, `position`, `text`, `multiplechoice`) VALUES ('ef227e91618878835d52cfad3e6d816b', '0', 'polskala', 0, 'Wertung 1-5', 0);
REPLACE INTO `evalquestion` (`evalquestion_id`, `parent_id`, `type`, `position`, `text`, `multiplechoice`) VALUES ('724244416b5d04a4d8f4eab8a86fdbf8', '0', 'likertskala', 0, 'Schulnoten', 0);
REPLACE INTO `evalquestion` (`evalquestion_id`, `parent_id`, `type`, `position`, `text`, `multiplechoice`) VALUES ('95bbae27965d3404f7fa3af058850bd3', '0', 'likertskala', 0, 'Wertung (trifft zu, ...)', 0);
REPLACE INTO `evalquestion` (`evalquestion_id`, `parent_id`, `type`, `position`, `text`, `multiplechoice`) VALUES ('6fddac14c1f2ac490b93681b3da5fc66', '0', 'multiplechoice', 0, 'Werktage', 0);
REPLACE INTO `evalquestion` (`evalquestion_id`, `parent_id`, `type`, `position`, `text`, `multiplechoice`) VALUES ('12e508079c4770fb13c9fce028f40cac', '0', 'multiplechoice', 0, 'Werktage-mehrfach', 1);
REPLACE INTO `evalquestion` (`evalquestion_id`, `parent_id`, `type`, `position`, `text`, `multiplechoice`) VALUES ('a68bd711902f23bd5c55a29f1ecaa095', '0', 'multiplechoice', 0, 'Freitext-Mehrzeilig', 0);
REPLACE INTO `evalquestion` (`evalquestion_id`, `parent_id`, `type`, `position`, `text`, `multiplechoice`) VALUES ('442e1e464e12498bd238a7767215a5a2', '0', 'multiplechoice', 0, 'Freitext-Einzeilig', 0);

-- 
-- Daten für Tabelle `eval_group_template`
-- 


-- 
-- Daten für Tabelle `eval_range`
-- 


-- 
-- Daten für Tabelle `eval_templates`
-- 


-- 
-- Daten für Tabelle `eval_templates_eval`
-- 


-- 
-- Daten für Tabelle `eval_templates_user`
-- 


-- 
-- Daten für Tabelle `eval_user`
-- 


-- 
-- Daten für Tabelle `extern_config`
-- 


-- 
-- Daten für Tabelle `ex_termine`
-- 

REPLACE INTO `ex_termine` (`termin_id`, `range_id`, `autor_id`, `content`, `description`, `date`, `end_time`, `mkdate`, `chdate`, `date_typ`, `topic_id`, `expire`, `repeat`, `color`, `priority`, `raum`, `metadate_id`, `resource_id`) VALUES ('e1a358164539a5f3023ce6c976fa7cb0', '834499e2b8a2cd71637890e5de31cba3', '205f3efb7997a0fc9755da2b535038da', '', NULL, 1198674000, 1198681200, 1194626456, 1194626456, 1, NULL, NULL, NULL, NULL, NULL, '', 'bedbec67efc647fd3123acf00433619f', '');

-- 
-- Daten für Tabelle `folder`
-- 

REPLACE INTO `folder` (`folder_id`, `range_id`, `user_id`, `name`, `description`, `permission`, `mkdate`, `chdate`) VALUES ('dad53cd0f0d9f36817c3c9c7c124bda3', 'ec2e364b28357106c0f8c282733dbe56', '', 'Allgemeiner Dateiordner', 'Ablage für allgemeine Ordner und Dokumente der Einrichtung', 7, 1156516698, 1156516698);
REPLACE INTO `folder` (`folder_id`, `range_id`, `user_id`, `name`, `description`, `permission`, `mkdate`, `chdate`) VALUES ('b58081c411c76814bc8f78425fb2ab81', '7a4f19a0a2c321ab2b8f7b798881af7c', '', 'Allgemeiner Dateiordner', 'Ablage für allgemeine Ordner und Dokumente der Einrichtung', 7, 1156516698, 1156516698);
REPLACE INTO `folder` (`folder_id`, `range_id`, `user_id`, `name`, `description`, `permission`, `mkdate`, `chdate`) VALUES ('694cdcef09c2b8e70a7313b028e36fb6', '110ce78ffefaf1e5f167cd7019b728bf', '', 'Allgemeiner Dateiordner', 'Ablage für allgemeine Ordner und Dokumente der Einrichtung', 7, 1156516698, 1156516698);
REPLACE INTO `folder` (`folder_id`, `range_id`, `user_id`, `name`, `description`, `permission`, `mkdate`, `chdate`) VALUES ('823b5c771f17d4103b1828251c29a7cb', '834499e2b8a2cd71637890e5de31cba3', '76ed43ef286fb55cf9e41beadb484a9f', 'Allgemeiner Dateiordner', 'Ablage für allgemeine Ordner und Dokumente der Veranstaltung', 7, 1156516698, 1156516698);
REPLACE INTO `folder` (`folder_id`, `range_id`, `user_id`, `name`, `description`, `permission`, `mkdate`, `chdate`) VALUES ('c996fa8545b9ed2ca0c6772cca784019', 'ab10d788d28787ea00ca39770a2516d9', '76ed43ef286fb55cf9e41beadb484a9f', 'nur lesbarer Ordner', '', 5, 1176474403, 1176474408);
REPLACE INTO `folder` (`folder_id`, `range_id`, `user_id`, `name`, `description`, `permission`, `mkdate`, `chdate`) VALUES ('963084e86963b5cd2d086eafd5f04eb5', 'ab10d788d28787ea00ca39770a2516d9', '76ed43ef286fb55cf9e41beadb484a9f', 'unsichtbarer Ordner', '', 0, 1176474417, 1176474422);
REPLACE INTO `folder` (`folder_id`, `range_id`, `user_id`, `name`, `description`, `permission`, `mkdate`, `chdate`) VALUES ('17b75cc079bdad8e54d2f7ce3ce29f2e', 'ab10d788d28787ea00ca39770a2516d9', '76ed43ef286fb55cf9e41beadb484a9f', 'Hausaufgabenordner', '', 3, 1176474443, 1176474449);
REPLACE INTO `folder` (`folder_id`, `range_id`, `user_id`, `name`, `description`, `permission`, `mkdate`, `chdate`) VALUES ('aa15759a75c167e38425d17539e7a7be', '41ad59c9b6cdafca50e42fe6bc68af4f', '205f3efb7997a0fc9755da2b535038da', 'Dateiordner der Gruppe: Thema 1', 'Ablage für Ordner und Dokumente dieser Gruppe', 15, 1194628738, 1194628738);
REPLACE INTO `folder` (`folder_id`, `range_id`, `user_id`, `name`, `description`, `permission`, `mkdate`, `chdate`) VALUES ('5b1b53b48c487a639ec493afbb270d4c', '151c33059a90b6138d280862f5d4b3c2', '205f3efb7997a0fc9755da2b535038da', 'Dateiordner der Gruppe: Thema 2', 'Ablage für Ordner und Dokumente dieser Gruppe', 15, 1194628768, 1194628768);
REPLACE INTO `folder` (`folder_id`, `range_id`, `user_id`, `name`, `description`, `permission`, `mkdate`, `chdate`) VALUES ('17534632a6a9145f21c9fc99b7557bf9', 'a5061826bf8db7487a774f92ce2a4d23', '205f3efb7997a0fc9755da2b535038da', 'Dateiordner der Gruppe: Thema 3', 'Ablage für Ordner und Dokumente dieser Gruppe', 15, 1194628789, 1194628789);

-- 
-- Daten für Tabelle `guestbook`
-- 


-- 
-- Daten für Tabelle `his_abschl`
-- 


-- 
-- Daten für Tabelle `his_abstgv`
-- 


-- 
-- Daten für Tabelle `his_pvers`
-- 


-- 
-- Daten für Tabelle `his_stg`
-- 


-- 
-- Daten für Tabelle `image_proxy_cache`
-- 


-- 
-- Daten für Tabelle `Institute`
-- 

REPLACE INTO `Institute` (`Institut_id`, `Name`, `fakultaets_id`, `Strasse`, `Plz`, `url`, `telefon`, `email`, `fax`, `type`, `modules`, `mkdate`, `chdate`, `lit_plugin_name`, `srienabled`) VALUES ('1535795b0d6ddecac6813f5f6ac47ef2', 'Test Fakultät', '1535795b0d6ddecac6813f5f6ac47ef2', 'Geismar Landstr. 17b', '37083 Göttingen', 'http://www.studip.de', '0551 / 381 985 0', 'testfakultaet@studip.de', '0551 / 381 985 3', 1, 16, 1156516698, 1156516698, 'Studip', 0);
REPLACE INTO `Institute` (`Institut_id`, `Name`, `fakultaets_id`, `Strasse`, `Plz`, `url`, `telefon`, `email`, `fax`, `type`, `modules`, `mkdate`, `chdate`, `lit_plugin_name`, `srienabled`) VALUES ('2560f7c7674942a7dce8eeb238e15d93', 'Test Einrichtung', '1535795b0d6ddecac6813f5f6ac47ef2', '', '', '', '', '', '', 1, 16, 1156516698, 1156516698, 'Studip', 0);
REPLACE INTO `Institute` (`Institut_id`, `Name`, `fakultaets_id`, `Strasse`, `Plz`, `url`, `telefon`, `email`, `fax`, `type`, `modules`, `mkdate`, `chdate`, `lit_plugin_name`, `srienabled`) VALUES ('536249daa596905f433e1f73578019db', 'Test Lehrstuhl', '1535795b0d6ddecac6813f5f6ac47ef2', '', '', '', '', '', '', 3, 16, 1156516698, 1156516698, 'Studip', 0);
REPLACE INTO `Institute` (`Institut_id`, `Name`, `fakultaets_id`, `Strasse`, `Plz`, `url`, `telefon`, `email`, `fax`, `type`, `modules`, `mkdate`, `chdate`, `lit_plugin_name`, `srienabled`) VALUES ('f02e2b17bc0e99fc885da6ac4c2532dc', 'Test Abteilung', '1535795b0d6ddecac6813f5f6ac47ef2', '', '', '', '', '', '', 4, 16, 1156516698, 1156516698, 'Studip', 0);
REPLACE INTO `Institute` (`Institut_id`, `Name`, `fakultaets_id`, `Strasse`, `Plz`, `url`, `telefon`, `email`, `fax`, `type`, `modules`, `mkdate`, `chdate`, `lit_plugin_name`, `srienabled`) VALUES ('ec2e364b28357106c0f8c282733dbe56', 'externe Bildungseinrichtungen', 'ec2e364b28357106c0f8c282733dbe56', '', '', '', '', '', '', 1, 16, 1156516698, 1156516698, 'Studip', 0);
REPLACE INTO `Institute` (`Institut_id`, `Name`, `fakultaets_id`, `Strasse`, `Plz`, `url`, `telefon`, `email`, `fax`, `type`, `modules`, `mkdate`, `chdate`, `lit_plugin_name`, `srienabled`) VALUES ('7a4f19a0a2c321ab2b8f7b798881af7c', 'externe Einrichtung A', 'ec2e364b28357106c0f8c282733dbe56', '', '', '', '', '', '', 1, 16, 1156516698, 1156516698, 'Studip', 0);
REPLACE INTO `Institute` (`Institut_id`, `Name`, `fakultaets_id`, `Strasse`, `Plz`, `url`, `telefon`, `email`, `fax`, `type`, `modules`, `mkdate`, `chdate`, `lit_plugin_name`, `srienabled`) VALUES ('110ce78ffefaf1e5f167cd7019b728bf', 'externe Einrichtung B', 'ec2e364b28357106c0f8c282733dbe56', '', '', '', '', '', '', 1, 16, 1156516698, 1156516698, 'Studip', 0);

-- 
-- Daten für Tabelle `kategorien`
-- 


-- 
-- Daten für Tabelle `lit_catalog`
-- 

REPLACE INTO `lit_catalog` (`catalog_id`, `user_id`, `mkdate`, `chdate`, `lit_plugin`, `accession_number`, `dc_title`, `dc_creator`, `dc_subject`, `dc_description`, `dc_publisher`, `dc_contributor`, `dc_date`, `dc_type`, `dc_format`, `dc_identifier`, `dc_source`, `dc_language`, `dc_relation`, `dc_coverage`, `dc_rights`) VALUES ('54181f281faa777941acc252aebaf26d', 'studip', 1156516698, 1156516698, 'Gvk', '387042768', 'Quickguide Strahlenschutz : [Aufgaben, Organisation, Schutzmaßnahmen].', 'Wolf, Heike', '', '', 'Kissing : WEKA Media', '', '2004-01-01', '', '74 S : Ill.', '', '', 'ger', '[Der Strahlenschutzbeauftragte in Medizin und Technik / Heike Wolf] Praxislösungen', '', '');
REPLACE INTO `lit_catalog` (`catalog_id`, `user_id`, `mkdate`, `chdate`, `lit_plugin`, `accession_number`, `dc_title`, `dc_creator`, `dc_subject`, `dc_description`, `dc_publisher`, `dc_contributor`, `dc_date`, `dc_type`, `dc_format`, `dc_identifier`, `dc_source`, `dc_language`, `dc_relation`, `dc_coverage`, `dc_rights`) VALUES ('d6623a3c2b8285fb472aa759150148ad', 'studip', 1156516698, 1156516698, 'Gvk', '387042253', 'Röntgenverordnung : (RÖV) ; Verordnung über den Schutz vor Schäden durch Röntgenstrahlen.', 'Wolf, Heike', '', '', 'Kissing : WEKA Media', '', '2004-01-01', '', '50 S.', '', '', 'ger', '[Der Strahlenschutzbeauftragte in Medizin und Technik / Heike Wolf] Praxislösungen', '', '');
REPLACE INTO `lit_catalog` (`catalog_id`, `user_id`, `mkdate`, `chdate`, `lit_plugin`, `accession_number`, `dc_title`, `dc_creator`, `dc_subject`, `dc_description`, `dc_publisher`, `dc_contributor`, `dc_date`, `dc_type`, `dc_format`, `dc_identifier`, `dc_source`, `dc_language`, `dc_relation`, `dc_coverage`, `dc_rights`) VALUES ('15074ad4f2bd2c57cbc9dfb343c1355b', 'studip', 1156516698, 1156516698, 'Gvk', '384065813', 'Der Kater mit Hut', 'Geisel, Theodor Seuss', '', '', 'München [u.a.] : Piper', '', '2004-01-01', '', '75 S : zahlr. Ill ; 19 cm.', 'ISBN: 349224078X (kart.)', '', 'ger', 'Serie Piper ;, 4078', '', '');
REPLACE INTO `lit_catalog` (`catalog_id`, `user_id`, `mkdate`, `chdate`, `lit_plugin`, `accession_number`, `dc_title`, `dc_creator`, `dc_subject`, `dc_description`, `dc_publisher`, `dc_contributor`, `dc_date`, `dc_type`, `dc_format`, `dc_identifier`, `dc_source`, `dc_language`, `dc_relation`, `dc_coverage`, `dc_rights`) VALUES ('ce704bbc9453994daa05d76d2d04aba0', 'studip', 1156516698, 1156516698, 'Gvk', '379252104', 'Die volkswirtschaftliche Perspektive', 'Heise, Michael', '', '', 'In: Zeitschrift für das gesamte Kreditwesen, Vol. 57, No. 4 (2004), p. 211-217, Frankfurt, M. : Knapp', 'Kater, Ulrich;', '2004-01-01', '', 'graph. Darst.', '', '', 'ger', '', '', '');
REPLACE INTO `lit_catalog` (`catalog_id`, `user_id`, `mkdate`, `chdate`, `lit_plugin`, `accession_number`, `dc_title`, `dc_creator`, `dc_subject`, `dc_description`, `dc_publisher`, `dc_contributor`, `dc_date`, `dc_type`, `dc_format`, `dc_identifier`, `dc_source`, `dc_language`, `dc_relation`, `dc_coverage`, `dc_rights`) VALUES ('b5d115a7f7cad02b4535fb3090bf18da', 'studip', 1156516698, 1156516698, 'Gvk', '386883831', 'E-Learning: Qualität und Nutzerakzeptanz sichern : Beiträge zur Planung, Umsetzung und Evaluation multimedialer und netzgestützter Anwendungen', 'Zinke, Gert', '', '', 'Bielefeld : Bertelsmann', 'Härtel, Michael; Bundesinstitut für Berufsbildung, ;', '2004-01-01', '', '159 S : graph. Darst ; 225 mm x 155 mm.', 'ISBN: 3763910204', '', 'ger', 'Berichte zur beruflichen Bildung ;, 265', '', '');

-- 
-- Daten für Tabelle `lit_list`
-- 

REPLACE INTO `lit_list` (`list_id`, `range_id`, `name`, `format`, `user_id`, `mkdate`, `chdate`, `priority`, `visibility`) VALUES ('3332f270b96fb23cdd2463cef8220b29', '834499e2b8a2cd71637890e5de31cba3', 'Basisliteratur der Veranstaltung', '**{dc_creator}** |({dc_contributor})||\r\n{dc_title}||\r\n{dc_identifier}||\r\n%%{published}%%||\r\n{note}||\r\n[{lit_plugin}]{external_link}|\r\n', '76ed43ef286fb55cf9e41beadb484a9f', 1156516698, 1156516698, 1, 1);

-- 
-- Daten für Tabelle `lit_list_content`
-- 

REPLACE INTO `lit_list_content` (`list_element_id`, `list_id`, `catalog_id`, `user_id`, `mkdate`, `chdate`, `note`, `priority`) VALUES ('1e6d6e6f179986f8c2be5b1c2ed37631', '3332f270b96fb23cdd2463cef8220b29', '15074ad4f2bd2c57cbc9dfb343c1355b', '76ed43ef286fb55cf9e41beadb484a9f', 1156516698, 1156516698, '', 1);
REPLACE INTO `lit_list_content` (`list_element_id`, `list_id`, `catalog_id`, `user_id`, `mkdate`, `chdate`, `note`, `priority`) VALUES ('4bd3001d8260001914e9ab8716a4fe70', '3332f270b96fb23cdd2463cef8220b29', 'ce704bbc9453994daa05d76d2d04aba0', '76ed43ef286fb55cf9e41beadb484a9f', 1156516698, 1156516698, '', 2);
REPLACE INTO `lit_list_content` (`list_element_id`, `list_id`, `catalog_id`, `user_id`, `mkdate`, `chdate`, `note`, `priority`) VALUES ('ce226125c3cf579cf28e5c96a8dea7a9', '3332f270b96fb23cdd2463cef8220b29', '54181f281faa777941acc252aebaf26d', '76ed43ef286fb55cf9e41beadb484a9f', 1156516698, 1156516698, '', 3);
REPLACE INTO `lit_list_content` (`list_element_id`, `list_id`, `catalog_id`, `user_id`, `mkdate`, `chdate`, `note`, `priority`) VALUES ('1d4ff2d55489dd9284f6a83dfc69149e', '3332f270b96fb23cdd2463cef8220b29', 'd6623a3c2b8285fb472aa759150148ad', '76ed43ef286fb55cf9e41beadb484a9f', 1156516698, 1156516698, '', 4);
REPLACE INTO `lit_list_content` (`list_element_id`, `list_id`, `catalog_id`, `user_id`, `mkdate`, `chdate`, `note`, `priority`) VALUES ('293e90c3c6511d2c8e1d4ba7b51daa98', '3332f270b96fb23cdd2463cef8220b29', 'b5d115a7f7cad02b4535fb3090bf18da', '76ed43ef286fb55cf9e41beadb484a9f', 1156516698, 1156516698, '', 5);

-- 
-- Daten für Tabelle `log_actions`
-- 

REPLACE INTO `log_actions` (`action_id`, `name`, `description`, `info_template`, `active`, `expires`) VALUES ('0ee290df95f0547caafa163c4d533991', 'SEM_VISIBLE', 'Veranstaltung sichtbar schalten', '%user schaltet %sem(%affected) sichtbar.', 1, NULL);
REPLACE INTO `log_actions` (`action_id`, `name`, `description`, `info_template`, `active`, `expires`) VALUES ('a94706b41493e32f8336194262418c01', 'SEM_INVISIBLE', 'Veranstaltung unsichtbar schalten', '%user versteckt %sem(%affected).', 1, NULL);
REPLACE INTO `log_actions` (`action_id`, `name`, `description`, `info_template`, `active`, `expires`) VALUES ('bd2103035a8021942390a78a431ba0c4', 'DUMMY', 'Dummy-Aktion', '%user tut etwas.', 1, NULL);
REPLACE INTO `log_actions` (`action_id`, `name`, `description`, `info_template`, `active`, `expires`) VALUES ('4490aa3d29644e716440fada68f54032', 'LOG_ERROR', 'Allgemeiner Log-Fehler', 'Allgemeiner Logging-Fehler, Details siehe Debug-Info.', 1, NULL);
REPLACE INTO `log_actions` (`action_id`, `name`, `description`, `info_template`, `active`, `expires`) VALUES ('f858b05c11f5faa2198a109a783087a8', 'SEM_CREATE', 'Veranstaltung anlegen', '%user legt %sem(%affected) an.', 1, NULL);
REPLACE INTO `log_actions` (`action_id`, `name`, `description`, `info_template`, `active`, `expires`) VALUES ('5b96f2fe994637253ba0fe4a94ad1b98', 'SEM_ARCHIVE', 'Veranstaltung archivieren', '%user archiviert %info (ID: %affected).', 1, NULL);
REPLACE INTO `log_actions` (`action_id`, `name`, `description`, `info_template`, `active`, `expires`) VALUES ('bf192518a9c3587129ed2fdb9ea56f73', 'SEM_DELETE_FROM_ARCHIVE', 'Veranstaltung aus Archiv löschen', '%user löscht %info aus dem Archiv (ID: %affected).', 1, NULL);
REPLACE INTO `log_actions` (`action_id`, `name`, `description`, `info_template`, `active`, `expires`) VALUES ('4869cd69f20d4d7ed4207e027d763a73', 'INST_USER_STATUS', 'Einrichtungsnutzerstatus ändern', '%user ändert Status für %user(%coaffected) in Einrichtung %inst(%affected): %info.', 1, NULL);
REPLACE INTO `log_actions` (`action_id`, `name`, `description`, `info_template`, `active`, `expires`) VALUES ('6be59dcd70197c59d7bf3bcd3fec616f', 'INST_USER_DEL', 'Benutzer aus Einrichtung löschen', '%user löscht %user(%coaffected) aus Einrichtung %inst(%affected).', 1, NULL);
REPLACE INTO `log_actions` (`action_id`, `name`, `description`, `info_template`, `active`, `expires`) VALUES ('cf8986a67e67ca273e15fd9230f6e872', 'USER_CHANGE_TITLE', 'Akademische Titel ändern', '%user ändert/setzt akademischen Titel für %user(%affected) - %info.', 1, NULL);
REPLACE INTO `log_actions` (`action_id`, `name`, `description`, `info_template`, `active`, `expires`) VALUES ('ca216ccdf753f59ba7fd621f7b22f7bd', 'USER_CHANGE_NAME', 'Personennamen ändern', '%user ändert/setzt Name für %user(%affected) - %info.', 1, NULL);
REPLACE INTO `log_actions` (`action_id`, `name`, `description`, `info_template`, `active`, `expires`) VALUES ('8aad296e52423452fc75cabaf2bee384', 'USER_CHANGE_USERNAME', 'Benutzernamen ändern', '%user ändert/setzt Benutzernamen für %user(%affected): %info.', 1, NULL);
REPLACE INTO `log_actions` (`action_id`, `name`, `description`, `info_template`, `active`, `expires`) VALUES ('59f3f38c905fded82bbfdf4f04c16729', 'INST_CREATE', 'Einrichtung anlegen', '%user legt Einrichtung %inst(%affected) an.', 1, NULL);
REPLACE INTO `log_actions` (`action_id`, `name`, `description`, `info_template`, `active`, `expires`) VALUES ('1a1e8c9c3125ea8d2c58c875a41226d6', 'INST_DEL', 'Einrichtung löschen', '%user löscht Einrichtung %info (%affected).', 1, NULL);
REPLACE INTO `log_actions` (`action_id`, `name`, `description`, `info_template`, `active`, `expires`) VALUES ('d18d750fb2c166e1c425976e8bca96e7', 'USER_CHANGE_EMAIL', 'E-Mail-Adresse ändern', '%user ändert/setzt E-Mail-Adresse für %user(%affected): %info.', 1, NULL);
REPLACE INTO `log_actions` (`action_id`, `name`, `description`, `info_template`, `active`, `expires`) VALUES ('a92afa63584cc2a62d2dd2996727b2c5', 'USER_CREATE', 'Nutzer anlegen', '%user legt Nutzer %user(%affected) an.', 1, NULL);
REPLACE INTO `log_actions` (`action_id`, `name`, `description`, `info_template`, `active`, `expires`) VALUES ('e406e407501c8418f752e977182cd782', 'USER_CHANGE_PERMS', 'Globalen Nutzerstatus ändern', '%user ändert/setzt globalen Status von %user(%affected): %info', 1, NULL);
REPLACE INTO `log_actions` (`action_id`, `name`, `description`, `info_template`, `active`, `expires`) VALUES ('63042706e5cd50924987b9515e1e6cae', 'INST_USER_ADD', 'Benutzer zu Einrichtung hinzufügen', '%user fügt %user(%coaffected) zu Einrichtung %inst(%affected) mit Status %info hinzu.', 1, NULL);
REPLACE INTO `log_actions` (`action_id`, `name`, `description`, `info_template`, `active`, `expires`) VALUES ('4dd6b4101f7bf3bd7fe8374042da95e9', 'USER_NEWPWD', 'Neues Passwort', '%user generiert neues Passwort für %user(%affected)', 1, NULL);
REPLACE INTO `log_actions` (`action_id`, `name`, `description`, `info_template`, `active`, `expires`) VALUES ('e8646729e5e04970954c8b9679af389b', 'USER_DEL', 'Benutzer löschen', '%user löscht %user(%affected) (%info)', 1, NULL);
REPLACE INTO `log_actions` (`action_id`, `name`, `description`, `info_template`, `active`, `expires`) VALUES ('2e816bfd792e4a99913f11c04ad49198', 'SEM_UNDELETE_SINGLEDATE', 'Einzeltermin wiederherstellen', '%user stellt Einzeltermin %singledate(%affected) in %sem(%coaffected) wieder her.', 1, NULL);
REPLACE INTO `log_actions` (`action_id`, `name`, `description`, `info_template`, `active`, `expires`) VALUES ('997cf01328d4d9f36b9f50ac9b6ace47', 'SEM_DELETE_SINGLEDATE', 'Einzeltermin löschen', '%user löscht Einzeltermin %singledate(%affected) in %sem(%coaffected).', 1, NULL);
REPLACE INTO `log_actions` (`action_id`, `name`, `description`, `info_template`, `active`, `expires`) VALUES ('b205bde204b5607e036c10557a6ce149', 'SEM_SET_STARTSEMESTER', 'Startsemester ändern', '%user hat in %sem(%affected) das Startsemester auf %semester(%coaffected) geändert.', 1, NULL);
REPLACE INTO `log_actions` (`action_id`, `name`, `description`, `info_template`, `active`, `expires`) VALUES ('9d13643a1833c061dc3d10b4fb227f12', 'SEM_SET_ENDSEMESTER', 'Semesterlaufzeit ändern', '%user hat in %sem(%affected) die Laufzeit auf %semester(%coaffected) geändert', 1, NULL);
REPLACE INTO `log_actions` (`action_id`, `name`, `description`, `info_template`, `active`, `expires`) VALUES ('5f8fda12a4c0bd6eadbb94861de83696', 'SEM_ADD_CYCLE', 'Regelmäßige Zeit hinzugefügt', '%user hat in %sem(%affected) die regelmäßige Zeit <em>%coaffected</em> hinzugefügt.', 1, NULL);
REPLACE INTO `log_actions` (`action_id`, `name`, `description`, `info_template`, `active`, `expires`) VALUES ('6f4bb66c1caf89879d89f3b1921a93dd', 'SEM_DELETE_CYCLE', 'Regelmäßige Zeit gelöscht', '%user hat in %sem(%affected) die regelmäßige Zeit <em>%coaffected</em> gelöscht.', 1, NULL);
REPLACE INTO `log_actions` (`action_id`, `name`, `description`, `info_template`, `active`, `expires`) VALUES ('3f7dcf6cc85d6fba1281d18c4d9aba6f', 'SEM_ADD_SINGLEDATE', 'Einzeltermin hinzufügen', '%user hat in %sem(%affected) den Einzeltermin <em>%coaffected</em> hinzugefügt', 1, NULL);
REPLACE INTO `log_actions` (`action_id`, `name`, `description`, `info_template`, `active`, `expires`) VALUES ('c36fa0f804cde78a6dcb1c30c2ee47ba', 'SEM_DELETE_REQUEST', 'Raumanfrage gelöscht', '%user hat in %sem(%affected) die Raumanfrage für die gesamte Veranstaltung gelöscht.', 1, NULL);
REPLACE INTO `log_actions` (`action_id`, `name`, `description`, `info_template`, `active`, `expires`) VALUES ('370db4eb0e38051dd3c5d7c52717215a', 'SEM_DELETE_SINGLEDATE_REQUEST', 'Einzeltermin, Raumanfrage gelöscht', '%user hat in %sem(%affected) die Raumanfrage für den Termin <em>%coaffected</em> gelöscht.', 1, NULL);
REPLACE INTO `log_actions` (`action_id`, `name`, `description`, `info_template`, `active`, `expires`) VALUES ('9d642dc93540580d42ba2ea502c3fbf6', 'SINGLEDATE_CHANGE_TIME', 'Einzeltermin bearbeiten', '%user hat in %sem(%affected) den Einzeltermin %coaffected geändert.', 1, NULL);

-- 
-- Daten für Tabelle `log_events`
-- 


-- 
-- Daten für Tabelle `message`
-- 


-- 
-- Daten für Tabelle `message_user`
-- 


-- 
-- Daten für Tabelle `news`
-- 

REPLACE INTO `news` (`news_id`, `topic`, `body`, `author`, `date`, `user_id`, `expire`, `allow_comments`, `chdate`, `chdate_uid`, `mkdate`) VALUES ('29f2932ce32be989022c6f43b866e744', 'Herzlich Willkommen!', 'Das Stud.IP-Team heisst sie herzlich willkommen. \r\nBitte schauen Sie sich ruhig um!\r\n\r\nWenn Sie das System selbst installiert haben und diese News sehen, haben Sie die Demonstrationsdaten in die Datenbank eingefügt. Wenn Sie produktiv mit dem System arbeiten wollen, sollten Sie diese Daten später wieder löschen, da die Passwörter der Accounts (vor allem des root-Accounts) öffentlich bekannt sind.', 'Root Studip', 1194625366, '76ed43ef286fb55cf9e41beadb484a9f', 14562502, 1, 1194625366, '', 1194625366);

-- 
-- Daten für Tabelle `news_range`
-- 

REPLACE INTO `news_range` (`news_id`, `range_id`) VALUES ('29f2932ce32be989022c6f43b866e744', '76ed43ef286fb55cf9e41beadb484a9f');
REPLACE INTO `news_range` (`news_id`, `range_id`) VALUES ('29f2932ce32be989022c6f43b866e744', 'studip');

-- 
-- Daten für Tabelle `news_rss_range`
-- 

REPLACE INTO `news_rss_range` (`range_id`, `rss_id`, `range_type`) VALUES ('studip', '70cefd1e80398bb20ff599636546cdff', 'global');

-- 
-- Daten für Tabelle `object_contentmodules`
-- 


-- 
-- Daten für Tabelle `object_rate`
-- 


-- 
-- Daten für Tabelle `object_user`
-- 


-- 
-- Daten für Tabelle `object_user_visits`
-- 

-- 
-- Daten für Tabelle `object_views`
-- 


-- 
-- Daten für Tabelle `plugins`
-- 

REPLACE INTO `plugins` (`pluginid`, `pluginclassname`, `pluginpath`, `pluginname`, `plugindesc`, `plugintype`, `enabled`, `navigationpos`, `dependentonid`) VALUES (1, 'PluginAdministrationPlugin', 'core', 'Plugin-Administration', 'Administrationsoberfläche für Plugins', 'Administration', 'yes', 0, NULL);
REPLACE INTO `plugins` (`pluginid`, `pluginclassname`, `pluginpath`, `pluginname`, `plugindesc`, `plugintype`, `enabled`, `navigationpos`, `dependentonid`) VALUES (2, 'de_studip_core_UserManagementPlugin', 'core', 'UserManagement', '', 'Core', 'yes', 1, 1);
REPLACE INTO `plugins` (`pluginid`, `pluginclassname`, `pluginpath`, `pluginname`, `plugindesc`, `plugintype`, `enabled`, `navigationpos`, `dependentonid`) VALUES (3, 'de_studip_core_RoleManagementPlugin', 'core', 'RollenManagement', 'Administration der Rollen', 'Administration', 'yes', 2, 1);

-- 
-- Daten für Tabelle `plugins_activated`
-- 

REPLACE INTO `plugins_activated` (`pluginid`, `poiid`, `state`) VALUES (1, 'admin', 'on');
REPLACE INTO `plugins_activated` (`pluginid`, `poiid`, `state`) VALUES (3, 'admin', 'on');

-- 
-- Daten für Tabelle `plugins_default_activations`
-- 


-- 
-- Daten für Tabelle `px_topics`
-- 

REPLACE INTO `px_topics` (`topic_id`, `parent_id`, `root_id`, `name`, `description`, `mkdate`, `chdate`, `author`, `author_host`, `Seminar_id`, `user_id`) VALUES ('5260172c3d6f9d56d21b06bf4c278b52', '0', '5260172c3d6f9d56d21b06bf4c278b52', 'Allgemeine Diskussionen', 'Hier ist Raum für allgemeine Diskussionen', 1084723039, 1084723039, '', '134.76.62.67', 'ec2e364b28357106c0f8c282733dbe56', '76ed43ef286fb55cf9e41beadb484a9f');
REPLACE INTO `px_topics` (`topic_id`, `parent_id`, `root_id`, `name`, `description`, `mkdate`, `chdate`, `author`, `author_host`, `Seminar_id`, `user_id`) VALUES ('b30ec732ee1c69a275b2d6adaae49cdc', '0', 'b30ec732ee1c69a275b2d6adaae49cdc', 'Allgemeine Diskussionen', 'Hier ist Raum für allgemeine Diskussionen', 1084723053, 1084723053, '', '134.76.62.67', '7a4f19a0a2c321ab2b8f7b798881af7c', '76ed43ef286fb55cf9e41beadb484a9f');
REPLACE INTO `px_topics` (`topic_id`, `parent_id`, `root_id`, `name`, `description`, `mkdate`, `chdate`, `author`, `author_host`, `Seminar_id`, `user_id`) VALUES ('9f394dffd08043f13cc65ffff65bfa05', '0', '9f394dffd08043f13cc65ffff65bfa05', 'Allgemeine Diskussionen', 'Hier ist Raum für allgemeine Diskussionen', 1084723061, 1084723061, '', '134.76.62.67', '110ce78ffefaf1e5f167cd7019b728bf', '76ed43ef286fb55cf9e41beadb484a9f');
REPLACE INTO `px_topics` (`topic_id`, `parent_id`, `root_id`, `name`, `description`, `mkdate`, `chdate`, `author`, `author_host`, `Seminar_id`, `user_id`) VALUES ('515b5485c3c72065df1c8980725e14ca', '0', '515b5485c3c72065df1c8980725e14ca', 'Allgemeine Diskussionen', '', 1176472544, 1176472551, 'Root Studip', '81.20.112.44', '834499e2b8a2cd71637890e5de31cba3', '76ed43ef286fb55cf9e41beadb484a9f');

-- 
-- Daten für Tabelle `range_tree`
-- 

REPLACE INTO `range_tree` (`item_id`, `parent_id`, `level`, `priority`, `name`, `studip_object`, `studip_object_id`) VALUES ('3f93863e3d37ba0df286a6e7e26974ef', 'root', 0, 0, 'Einrichtungen der Universität', '', '');
REPLACE INTO `range_tree` (`item_id`, `parent_id`, `level`, `priority`, `name`, `studip_object`, `studip_object_id`) VALUES ('1323254564871354786157481484621', '3f93863e3d37ba0df286a6e7e26974ef', 1, 0, '', 'inst', '1535795b0d6ddecac6813f5f6ac47ef2');
REPLACE INTO `range_tree` (`item_id`, `parent_id`, `level`, `priority`, `name`, `studip_object`, `studip_object_id`) VALUES ('ce6c87bbf759b4cfd6f92d0c5560da5c', '1323254564871354786157481484621', 0, 0, 'Test Einrichtung', 'inst', '2560f7c7674942a7dce8eeb238e15d93');
REPLACE INTO `range_tree` (`item_id`, `parent_id`, `level`, `priority`, `name`, `studip_object`, `studip_object_id`) VALUES ('2f4f90ac9d8d832cc8c8a95910fde4eb', '1323254564871354786157481484621', 0, 1, 'Test Lehrstuhl', 'inst', '536249daa596905f433e1f73578019db');
REPLACE INTO `range_tree` (`item_id`, `parent_id`, `level`, `priority`, `name`, `studip_object`, `studip_object_id`) VALUES ('5d032f70c255f3e57cf8aa85a429ad4e', '1323254564871354786157481484621', 0, 2, 'Test Abteilung', 'inst', 'f02e2b17bc0e99fc885da6ac4c2532dc');
REPLACE INTO `range_tree` (`item_id`, `parent_id`, `level`, `priority`, `name`, `studip_object`, `studip_object_id`) VALUES ('a3d977a66f0010fa8e15c27dd71aff63', 'root', 0, 1, 'externe Bildungseinrichtungen', 'fak', 'ec2e364b28357106c0f8c282733dbe56');
REPLACE INTO `range_tree` (`item_id`, `parent_id`, `level`, `priority`, `name`, `studip_object`, `studip_object_id`) VALUES ('e0ff0ead6a8c5191078ed787cd7c0c1f', 'a3d977a66f0010fa8e15c27dd71aff63', 0, 0, 'externe Einrichtung A', 'inst', '7a4f19a0a2c321ab2b8f7b798881af7c');
REPLACE INTO `range_tree` (`item_id`, `parent_id`, `level`, `priority`, `name`, `studip_object`, `studip_object_id`) VALUES ('105b70b72dc1908ce2925e057c4a8daa', 'a3d977a66f0010fa8e15c27dd71aff63', 0, 1, 'externe Einrichtung B', 'inst', '110ce78ffefaf1e5f167cd7019b728bf');

-- 
-- Daten für Tabelle `resources_assign`
-- 

REPLACE INTO `resources_assign` (`assign_id`, `resource_id`, `assign_user_id`, `user_free_name`, `begin`, `end`, `repeat_end`, `repeat_quantity`, `repeat_interval`, `repeat_month_of_year`, `repeat_day_of_month`, `repeat_week_of_month`, `repeat_day_of_week`, `mkdate`, `chdate`) VALUES ('03885a2447572a2eb68ff9f76afb203f', 'b17c4ea6e053f2fffba8a5517fc277b3', 'b5d9c1b456bcd354b1b8d7c35f52e760', '', 1139392800, 1139396400, 1139396400, 0, 0, 0, 0, 0, 0, 1128081212, 1128081212);
REPLACE INTO `resources_assign` (`assign_id`, `resource_id`, `assign_user_id`, `user_free_name`, `begin`, `end`, `repeat_end`, `repeat_quantity`, `repeat_interval`, `repeat_month_of_year`, `repeat_day_of_month`, `repeat_week_of_month`, `repeat_day_of_week`, `mkdate`, `chdate`) VALUES ('5ca981820adeb427cfa29c952daba39f', '728f1578de643fb08b32b4b8afb2db77', '4ff0b9a56115020b60f91bb16af6f9e4', '', 1139216400, 1139223600, 1139223600, 0, 0, 0, 0, 0, 0, 1128081212, 1128081212);
REPLACE INTO `resources_assign` (`assign_id`, `resource_id`, `assign_user_id`, `user_free_name`, `begin`, `end`, `repeat_end`, `repeat_quantity`, `repeat_interval`, `repeat_month_of_year`, `repeat_day_of_month`, `repeat_week_of_month`, `repeat_day_of_week`, `mkdate`, `chdate`) VALUES ('cb52bc6278618dd6b66701fb87f1eaca', 'b17c4ea6e053f2fffba8a5517fc277b3', 'eeef03d29a78cd6efc28f8e4a0e4d6ce', '', 1138788000, 1138791600, 1138791600, 0, 0, 0, 0, 0, 0, 1128081212, 1128081212);
REPLACE INTO `resources_assign` (`assign_id`, `resource_id`, `assign_user_id`, `user_free_name`, `begin`, `end`, `repeat_end`, `repeat_quantity`, `repeat_interval`, `repeat_month_of_year`, `repeat_day_of_month`, `repeat_week_of_month`, `repeat_day_of_week`, `mkdate`, `chdate`) VALUES ('6191fb7eb7901434d1d3cace141b3924', '728f1578de643fb08b32b4b8afb2db77', '82e98f48ead7fba4ea9fc2e0a5d40c5f', '', 1138611600, 1138618800, 1138618800, 0, 0, 0, 0, 0, 0, 1128081212, 1128081212);
REPLACE INTO `resources_assign` (`assign_id`, `resource_id`, `assign_user_id`, `user_free_name`, `begin`, `end`, `repeat_end`, `repeat_quantity`, `repeat_interval`, `repeat_month_of_year`, `repeat_day_of_month`, `repeat_week_of_month`, `repeat_day_of_week`, `mkdate`, `chdate`) VALUES ('5d4cfc8fe255b36ef22dbb9fb2c5960b', 'b17c4ea6e053f2fffba8a5517fc277b3', 'ec9e0cfb50726bfc08c91c360e82d367', '', 1138183200, 1138186800, 1138186800, 0, 0, 0, 0, 0, 0, 1128081212, 1128081212);
REPLACE INTO `resources_assign` (`assign_id`, `resource_id`, `assign_user_id`, `user_free_name`, `begin`, `end`, `repeat_end`, `repeat_quantity`, `repeat_interval`, `repeat_month_of_year`, `repeat_day_of_month`, `repeat_week_of_month`, `repeat_day_of_week`, `mkdate`, `chdate`) VALUES ('f0b9cb3ee949085d8e92484ff11b82b0', '728f1578de643fb08b32b4b8afb2db77', 'af6f6e306c8f756581d7805fe995682b', '', 1138006800, 1138014000, 1138014000, 0, 0, 0, 0, 0, 0, 1128081212, 1128081212);
REPLACE INTO `resources_assign` (`assign_id`, `resource_id`, `assign_user_id`, `user_free_name`, `begin`, `end`, `repeat_end`, `repeat_quantity`, `repeat_interval`, `repeat_month_of_year`, `repeat_day_of_month`, `repeat_week_of_month`, `repeat_day_of_week`, `mkdate`, `chdate`) VALUES ('24e3c621d4ff7cbe09d9e9cbee474df5', 'b17c4ea6e053f2fffba8a5517fc277b3', '017f5e12266d9a6387b2a7e0879b59a3', '', 1137578400, 1137582000, 1137582000, 0, 0, 0, 0, 0, 0, 1128081212, 1128081212);
REPLACE INTO `resources_assign` (`assign_id`, `resource_id`, `assign_user_id`, `user_free_name`, `begin`, `end`, `repeat_end`, `repeat_quantity`, `repeat_interval`, `repeat_month_of_year`, `repeat_day_of_month`, `repeat_week_of_month`, `repeat_day_of_week`, `mkdate`, `chdate`) VALUES ('f9ae4e9b4314ee4f04acd63882123b6c', '728f1578de643fb08b32b4b8afb2db77', 'de124fc97d8a51d5fbe2749f64e53767', '', 1137402000, 1137409200, 1137409200, 0, 0, 0, 0, 0, 0, 1128081212, 1128081212);
REPLACE INTO `resources_assign` (`assign_id`, `resource_id`, `assign_user_id`, `user_free_name`, `begin`, `end`, `repeat_end`, `repeat_quantity`, `repeat_interval`, `repeat_month_of_year`, `repeat_day_of_month`, `repeat_week_of_month`, `repeat_day_of_week`, `mkdate`, `chdate`) VALUES ('f3032ec9c4dc010325f208051acfcc95', 'b17c4ea6e053f2fffba8a5517fc277b3', '4d9a65d7759c01d4ba6ff63afb321f9e', '', 1136973600, 1136977200, 1136977200, 0, 0, 0, 0, 0, 0, 1128081212, 1128081212);
REPLACE INTO `resources_assign` (`assign_id`, `resource_id`, `assign_user_id`, `user_free_name`, `begin`, `end`, `repeat_end`, `repeat_quantity`, `repeat_interval`, `repeat_month_of_year`, `repeat_day_of_month`, `repeat_week_of_month`, `repeat_day_of_week`, `mkdate`, `chdate`) VALUES ('1f0e304386af89c6a6accbad66016f20', '728f1578de643fb08b32b4b8afb2db77', '12b8502dec68c3a0dad18a2c92a64b87', '', 1136797200, 1136804400, 1136804400, 0, 0, 0, 0, 0, 0, 1128081212, 1128081212);
REPLACE INTO `resources_assign` (`assign_id`, `resource_id`, `assign_user_id`, `user_free_name`, `begin`, `end`, `repeat_end`, `repeat_quantity`, `repeat_interval`, `repeat_month_of_year`, `repeat_day_of_month`, `repeat_week_of_month`, `repeat_day_of_week`, `mkdate`, `chdate`) VALUES ('1e8aee50ab99be670784561b9f2700a3', 'b17c4ea6e053f2fffba8a5517fc277b3', 'fd60ed17a755ff412238a58f6fa8db68', '', 1136368800, 1136372400, 1136372400, 0, 0, 0, 0, 0, 0, 1128081212, 1128081212);
REPLACE INTO `resources_assign` (`assign_id`, `resource_id`, `assign_user_id`, `user_free_name`, `begin`, `end`, `repeat_end`, `repeat_quantity`, `repeat_interval`, `repeat_month_of_year`, `repeat_day_of_month`, `repeat_week_of_month`, `repeat_day_of_week`, `mkdate`, `chdate`) VALUES ('7cf6ec227a0fc6047eae54631d2a79f6', '728f1578de643fb08b32b4b8afb2db77', 'f47e41a7851c0d6856b8590510c7804f', '', 1136192400, 1136199600, 1136199600, 0, 0, 0, 0, 0, 0, 1128081212, 1128081212);
REPLACE INTO `resources_assign` (`assign_id`, `resource_id`, `assign_user_id`, `user_free_name`, `begin`, `end`, `repeat_end`, `repeat_quantity`, `repeat_interval`, `repeat_month_of_year`, `repeat_day_of_month`, `repeat_week_of_month`, `repeat_day_of_week`, `mkdate`, `chdate`) VALUES ('63478fe7b1aca75e669dcc7fd2645fc9', 'b17c4ea6e053f2fffba8a5517fc277b3', '616dcc691f1567b79e875f550dd73212', '', 1135764000, 1135767600, 1135767600, 0, 0, 0, 0, 0, 0, 1128081212, 1128081212);
REPLACE INTO `resources_assign` (`assign_id`, `resource_id`, `assign_user_id`, `user_free_name`, `begin`, `end`, `repeat_end`, `repeat_quantity`, `repeat_interval`, `repeat_month_of_year`, `repeat_day_of_month`, `repeat_week_of_month`, `repeat_day_of_week`, `mkdate`, `chdate`) VALUES ('b6007e9133ca11dff711c72452b188da', 'b17c4ea6e053f2fffba8a5517fc277b3', 'd3184a0230f30144f3ad8a65a6b3a8e2', '', 1135159200, 1135162800, 1135162800, 0, 0, 0, 0, 0, 0, 1128081212, 1128081212);
REPLACE INTO `resources_assign` (`assign_id`, `resource_id`, `assign_user_id`, `user_free_name`, `begin`, `end`, `repeat_end`, `repeat_quantity`, `repeat_interval`, `repeat_month_of_year`, `repeat_day_of_month`, `repeat_week_of_month`, `repeat_day_of_week`, `mkdate`, `chdate`) VALUES ('8d8dee265c82da4c9744ee745264e09a', '728f1578de643fb08b32b4b8afb2db77', '942d81598f81b7cc4f3768fc1840fee4', '', 1134982800, 1134990000, 1134990000, 0, 0, 0, 0, 0, 0, 1128081212, 1128081212);
REPLACE INTO `resources_assign` (`assign_id`, `resource_id`, `assign_user_id`, `user_free_name`, `begin`, `end`, `repeat_end`, `repeat_quantity`, `repeat_interval`, `repeat_month_of_year`, `repeat_day_of_month`, `repeat_week_of_month`, `repeat_day_of_week`, `mkdate`, `chdate`) VALUES ('40bf6af0a9f2b295ef00ca23bf3bb993', 'b17c4ea6e053f2fffba8a5517fc277b3', '9646cf7d6073001e9477d4e27a032c56', '', 1134554400, 1134558000, 1134558000, 0, 0, 0, 0, 0, 0, 1128081212, 1128081212);
REPLACE INTO `resources_assign` (`assign_id`, `resource_id`, `assign_user_id`, `user_free_name`, `begin`, `end`, `repeat_end`, `repeat_quantity`, `repeat_interval`, `repeat_month_of_year`, `repeat_day_of_month`, `repeat_week_of_month`, `repeat_day_of_week`, `mkdate`, `chdate`) VALUES ('b7a3555538d79afb543f6e8675014951', '728f1578de643fb08b32b4b8afb2db77', '9fb21625fcc5b4455e390bb78cb42d37', '', 1134378000, 1134385200, 1134385200, 0, 0, 0, 0, 0, 0, 1128081212, 1128081212);
REPLACE INTO `resources_assign` (`assign_id`, `resource_id`, `assign_user_id`, `user_free_name`, `begin`, `end`, `repeat_end`, `repeat_quantity`, `repeat_interval`, `repeat_month_of_year`, `repeat_day_of_month`, `repeat_week_of_month`, `repeat_day_of_week`, `mkdate`, `chdate`) VALUES ('8f18bd9fcbdbc9f71396c2c8a8bc47d7', 'b17c4ea6e053f2fffba8a5517fc277b3', 'f1125209961d36c1ab18732cec2c750e', '', 1133949600, 1133953200, 1133953200, 0, 0, 0, 0, 0, 0, 1128081212, 1128081212);
REPLACE INTO `resources_assign` (`assign_id`, `resource_id`, `assign_user_id`, `user_free_name`, `begin`, `end`, `repeat_end`, `repeat_quantity`, `repeat_interval`, `repeat_month_of_year`, `repeat_day_of_month`, `repeat_week_of_month`, `repeat_day_of_week`, `mkdate`, `chdate`) VALUES ('5c41d85982b9f26f7724a1d7c6694a81', '728f1578de643fb08b32b4b8afb2db77', '7f41b56acd574a5e85d9e99a1da1f036', '', 1133773200, 1133780400, 1133780400, 0, 0, 0, 0, 0, 0, 1128081212, 1128081212);
REPLACE INTO `resources_assign` (`assign_id`, `resource_id`, `assign_user_id`, `user_free_name`, `begin`, `end`, `repeat_end`, `repeat_quantity`, `repeat_interval`, `repeat_month_of_year`, `repeat_day_of_month`, `repeat_week_of_month`, `repeat_day_of_week`, `mkdate`, `chdate`) VALUES ('faed01047b7f18e82dcb3924675b9dcf', 'b17c4ea6e053f2fffba8a5517fc277b3', '1239dcbdf93523e45fe3ddaa0b7f48f0', '', 1133344800, 1133348400, 1133348400, 0, 0, 0, 0, 0, 0, 1128081212, 1128081212);
REPLACE INTO `resources_assign` (`assign_id`, `resource_id`, `assign_user_id`, `user_free_name`, `begin`, `end`, `repeat_end`, `repeat_quantity`, `repeat_interval`, `repeat_month_of_year`, `repeat_day_of_month`, `repeat_week_of_month`, `repeat_day_of_week`, `mkdate`, `chdate`) VALUES ('f8b0edbecdf88f6462dd599a55be8554', '728f1578de643fb08b32b4b8afb2db77', 'fedc44bef14b1cbd8061163db829194a', '', 1133168400, 1133175600, 1133175600, 0, 0, 0, 0, 0, 0, 1128081212, 1128081212);
REPLACE INTO `resources_assign` (`assign_id`, `resource_id`, `assign_user_id`, `user_free_name`, `begin`, `end`, `repeat_end`, `repeat_quantity`, `repeat_interval`, `repeat_month_of_year`, `repeat_day_of_month`, `repeat_week_of_month`, `repeat_day_of_week`, `mkdate`, `chdate`) VALUES ('16b373d0d5194dfee62526c452c2d22f', 'b17c4ea6e053f2fffba8a5517fc277b3', '02d4abea4aa298768c6b8aa583f67781', '', 1132740000, 1132743600, 1132743600, 0, 0, 0, 0, 0, 0, 1128081212, 1128081212);
REPLACE INTO `resources_assign` (`assign_id`, `resource_id`, `assign_user_id`, `user_free_name`, `begin`, `end`, `repeat_end`, `repeat_quantity`, `repeat_interval`, `repeat_month_of_year`, `repeat_day_of_month`, `repeat_week_of_month`, `repeat_day_of_week`, `mkdate`, `chdate`) VALUES ('38aa1ffc00354cbbbdc0c990f3a5de8c', '728f1578de643fb08b32b4b8afb2db77', 'c660099547e465925d4a9640e00b5735', '', 1132563600, 1132570800, 1132570800, 0, 0, 0, 0, 0, 0, 1128081212, 1128081212);
REPLACE INTO `resources_assign` (`assign_id`, `resource_id`, `assign_user_id`, `user_free_name`, `begin`, `end`, `repeat_end`, `repeat_quantity`, `repeat_interval`, `repeat_month_of_year`, `repeat_day_of_month`, `repeat_week_of_month`, `repeat_day_of_week`, `mkdate`, `chdate`) VALUES ('00c6814288c9e329838fed90c697af6b', 'b17c4ea6e053f2fffba8a5517fc277b3', 'a3e926ca5151b1abbd29c3573b92a1eb', '', 1132135200, 1132138800, 1132138800, 0, 0, 0, 0, 0, 0, 1128081211, 1128081211);
REPLACE INTO `resources_assign` (`assign_id`, `resource_id`, `assign_user_id`, `user_free_name`, `begin`, `end`, `repeat_end`, `repeat_quantity`, `repeat_interval`, `repeat_month_of_year`, `repeat_day_of_month`, `repeat_week_of_month`, `repeat_day_of_week`, `mkdate`, `chdate`) VALUES ('0adf9b563c389e56a4d3cc2e845beb21', '728f1578de643fb08b32b4b8afb2db77', '2434ac4fe5da0f462761e2788454cafa', '', 1131958800, 1131966000, 1131966000, 0, 0, 0, 0, 0, 0, 1128081211, 1128081211);
REPLACE INTO `resources_assign` (`assign_id`, `resource_id`, `assign_user_id`, `user_free_name`, `begin`, `end`, `repeat_end`, `repeat_quantity`, `repeat_interval`, `repeat_month_of_year`, `repeat_day_of_month`, `repeat_week_of_month`, `repeat_day_of_week`, `mkdate`, `chdate`) VALUES ('11d3b39ce5d94da2101455729815774d', 'b17c4ea6e053f2fffba8a5517fc277b3', 'd1d23d1f29acc676cc89fb70681f9c02', '', 1131530400, 1131534000, 1131534000, 0, 0, 0, 0, 0, 0, 1128081211, 1128081211);
REPLACE INTO `resources_assign` (`assign_id`, `resource_id`, `assign_user_id`, `user_free_name`, `begin`, `end`, `repeat_end`, `repeat_quantity`, `repeat_interval`, `repeat_month_of_year`, `repeat_day_of_month`, `repeat_week_of_month`, `repeat_day_of_week`, `mkdate`, `chdate`) VALUES ('66633a027444df45883586f3f30d3106', '728f1578de643fb08b32b4b8afb2db77', 'd17bdb1804d688270e5f040a7b4958a9', '', 1131354000, 1131361200, 1131361200, 0, 0, 0, 0, 0, 0, 1128081211, 1128081211);
REPLACE INTO `resources_assign` (`assign_id`, `resource_id`, `assign_user_id`, `user_free_name`, `begin`, `end`, `repeat_end`, `repeat_quantity`, `repeat_interval`, `repeat_month_of_year`, `repeat_day_of_month`, `repeat_week_of_month`, `repeat_day_of_week`, `mkdate`, `chdate`) VALUES ('c0dc6e15b47fb787759f0c50fe0a9b30', 'b17c4ea6e053f2fffba8a5517fc277b3', '35da45704165df681f312a9c2f4e31e7', '', 1130925600, 1130929200, 1130929200, 0, 0, 0, 0, 0, 0, 1128081211, 1128081211);
REPLACE INTO `resources_assign` (`assign_id`, `resource_id`, `assign_user_id`, `user_free_name`, `begin`, `end`, `repeat_end`, `repeat_quantity`, `repeat_interval`, `repeat_month_of_year`, `repeat_day_of_month`, `repeat_week_of_month`, `repeat_day_of_week`, `mkdate`, `chdate`) VALUES ('f960ad06f235a9e115dd0d9e85369fb2', '728f1578de643fb08b32b4b8afb2db77', 'd3636374ec79df064946a4d10a670f91', '', 1130749200, 1130756400, 1130756400, 0, 0, 0, 0, 0, 0, 1128081211, 1128081211);
REPLACE INTO `resources_assign` (`assign_id`, `resource_id`, `assign_user_id`, `user_free_name`, `begin`, `end`, `repeat_end`, `repeat_quantity`, `repeat_interval`, `repeat_month_of_year`, `repeat_day_of_month`, `repeat_week_of_month`, `repeat_day_of_week`, `mkdate`, `chdate`) VALUES ('2995833dd73b1bfe8bdeeb49363fe27b', 'b17c4ea6e053f2fffba8a5517fc277b3', '9384c65263130b22accb1dab7558a137', '', 1130317200, 1130320800, 1130320800, 0, 0, 0, 0, 0, 0, 1128081211, 1128081211);
REPLACE INTO `resources_assign` (`assign_id`, `resource_id`, `assign_user_id`, `user_free_name`, `begin`, `end`, `repeat_end`, `repeat_quantity`, `repeat_interval`, `repeat_month_of_year`, `repeat_day_of_month`, `repeat_week_of_month`, `repeat_day_of_week`, `mkdate`, `chdate`) VALUES ('bf1c44d8edcd5bfdac08da59f25ab300', '728f1578de643fb08b32b4b8afb2db77', 'c2c175ce8e54e56c9a429bd6b4c5458a', '', 1130140800, 1130148000, 1130148000, 0, 0, 0, 0, 0, 0, 1128081211, 1128081211);
REPLACE INTO `resources_assign` (`assign_id`, `resource_id`, `assign_user_id`, `user_free_name`, `begin`, `end`, `repeat_end`, `repeat_quantity`, `repeat_interval`, `repeat_month_of_year`, `repeat_day_of_month`, `repeat_week_of_month`, `repeat_day_of_week`, `mkdate`, `chdate`) VALUES ('362a1cc74d9cae0fdaf010af5cc4548b', 'b17c4ea6e053f2fffba8a5517fc277b3', '085a7bcfc72977a77987e6cc993b5b74', '', 1129712400, 1129716000, 1129716000, 0, 0, 0, 0, 0, 0, 1128081211, 1128081211);
REPLACE INTO `resources_assign` (`assign_id`, `resource_id`, `assign_user_id`, `user_free_name`, `begin`, `end`, `repeat_end`, `repeat_quantity`, `repeat_interval`, `repeat_month_of_year`, `repeat_day_of_month`, `repeat_week_of_month`, `repeat_day_of_week`, `mkdate`, `chdate`) VALUES ('22b96c20451dec277365013a74ec82b3', '728f1578de643fb08b32b4b8afb2db77', '0862341e790ea020267ea86d5a5001bb', '', 1129536000, 1129543200, 1129543200, 0, 0, 0, 0, 0, 0, 1128081211, 1128081211);
REPLACE INTO `resources_assign` (`assign_id`, `resource_id`, `assign_user_id`, `user_free_name`, `begin`, `end`, `repeat_end`, `repeat_quantity`, `repeat_interval`, `repeat_month_of_year`, `repeat_day_of_month`, `repeat_week_of_month`, `repeat_day_of_week`, `mkdate`, `chdate`) VALUES ('f7ac88e782c9dec856fa154fced9427d', '728f1578de643fb08b32b4b8afb2db77', '6ea5bf280942504147e7ca077dc0deaa', '', 1128931200, 1128938400, 1128938400, 0, 0, 0, 0, 0, 0, 1128081211, 1128081211);
REPLACE INTO `resources_assign` (`assign_id`, `resource_id`, `assign_user_id`, `user_free_name`, `begin`, `end`, `repeat_end`, `repeat_quantity`, `repeat_interval`, `repeat_month_of_year`, `repeat_day_of_month`, `repeat_week_of_month`, `repeat_day_of_week`, `mkdate`, `chdate`) VALUES ('de0b02c7bc90000df474bd6fc7799c6b', 'b17c4ea6e053f2fffba8a5517fc277b3', 'd159d6ffb0398298b644f2385de55ad5', '', 1129107600, 1129111200, 1129111200, 0, 0, 0, 0, 0, 0, 1128081211, 1128081211);
REPLACE INTO `resources_assign` (`assign_id`, `resource_id`, `assign_user_id`, `user_free_name`, `begin`, `end`, `repeat_end`, `repeat_quantity`, `repeat_interval`, `repeat_month_of_year`, `repeat_day_of_month`, `repeat_week_of_month`, `repeat_day_of_week`, `mkdate`, `chdate`) VALUES ('1d72ef067767fd6558e771c2b91aaf47', '52b435cbdd021bcc5cd78835de2b4f57', '205f3efb7997a0fc9755da2b535038da', '', 1128520800, 1128528000, 0, 0, 0, 0, 0, 0, 0, 1128081137, 1128081137);
REPLACE INTO `resources_assign` (`assign_id`, `resource_id`, `assign_user_id`, `user_free_name`, `begin`, `end`, `repeat_end`, `repeat_quantity`, `repeat_interval`, `repeat_month_of_year`, `repeat_day_of_month`, `repeat_week_of_month`, `repeat_day_of_week`, `mkdate`, `chdate`) VALUES ('865565322f9e48d989759b70e4bc6b00', '51ad4b7100d3a8a1db61c7b099f052a6', '193704c65a442d3f71eaa778796aff12', '', 1128520800, 1128528000, 1128528000, 0, 0, 0, 0, 0, 0, 1128081379, 1128081379);

-- 
-- Daten für Tabelle `resources_categories`
-- 

REPLACE INTO `resources_categories` (`category_id`, `name`, `description`, `system`, `is_room`, `iconnr`) VALUES ('3cbcc99c39476b8e2c8eef5381687461', 'Gebäude', '', 0, 0, 1);
REPLACE INTO `resources_categories` (`category_id`, `name`, `description`, `system`, `is_room`, `iconnr`) VALUES ('85d62e2a8a87a2924db8fc4ed3fde09d', 'Hörsaal', '', 0, 1, 1);
REPLACE INTO `resources_categories` (`category_id`, `name`, `description`, `system`, `is_room`, `iconnr`) VALUES ('f3351baeca8776d4ffe4b672f568cbed', 'Gerät', '', 0, 0, 1);
REPLACE INTO `resources_categories` (`category_id`, `name`, `description`, `system`, `is_room`, `iconnr`) VALUES ('5a72dfe3f0c0295a8fe4e12c86d4c8f4', 'Übungsraum', '', 0, 1, 1);

-- 
-- Daten für Tabelle `resources_categories_properties`
-- 

REPLACE INTO `resources_categories_properties` (`category_id`, `property_id`, `requestable`, `system`) VALUES ('3cbcc99c39476b8e2c8eef5381687461', 'c4f13691419a6c12d38ad83daa926c7c', 0, 0);
REPLACE INTO `resources_categories_properties` (`category_id`, `property_id`, `requestable`, `system`) VALUES ('85d62e2a8a87a2924db8fc4ed3fde09d', 'afb8675e2257c03098aa34b2893ba686', 1, 0);
REPLACE INTO `resources_categories_properties` (`category_id`, `property_id`, `requestable`, `system`) VALUES ('85d62e2a8a87a2924db8fc4ed3fde09d', '7c1a8f6001cfdcb9e9c33eeee0ef343d', 1, 0);
REPLACE INTO `resources_categories_properties` (`category_id`, `property_id`, `requestable`, `system`) VALUES ('3cbcc99c39476b8e2c8eef5381687461', 'b79b77f40706ed598f5403f953c1f791', 0, 0);
REPLACE INTO `resources_categories_properties` (`category_id`, `property_id`, `requestable`, `system`) VALUES ('85d62e2a8a87a2924db8fc4ed3fde09d', '1f8cef2b614382e36eaa4a29f6027edf', 1, 0);
REPLACE INTO `resources_categories_properties` (`category_id`, `property_id`, `requestable`, `system`) VALUES ('85d62e2a8a87a2924db8fc4ed3fde09d', '44fd30e8811d0d962582fa1a9c452bdd', 1, 0);
REPLACE INTO `resources_categories_properties` (`category_id`, `property_id`, `requestable`, `system`) VALUES ('85d62e2a8a87a2924db8fc4ed3fde09d', '613cfdf6aa1072e21a1edfcfb0445c69', 1, 0);
REPLACE INTO `resources_categories_properties` (`category_id`, `property_id`, `requestable`, `system`) VALUES ('85d62e2a8a87a2924db8fc4ed3fde09d', '28addfe18e86cc3587205734c8bc2372', 1, 0);
REPLACE INTO `resources_categories_properties` (`category_id`, `property_id`, `requestable`, `system`) VALUES ('5a72dfe3f0c0295a8fe4e12c86d4c8f4', '7c1a8f6001cfdcb9e9c33eeee0ef343d', 1, 0);
REPLACE INTO `resources_categories_properties` (`category_id`, `property_id`, `requestable`, `system`) VALUES ('5a72dfe3f0c0295a8fe4e12c86d4c8f4', 'afb8675e2257c03098aa34b2893ba686', 1, 0);
REPLACE INTO `resources_categories_properties` (`category_id`, `property_id`, `requestable`, `system`) VALUES ('5a72dfe3f0c0295a8fe4e12c86d4c8f4', 'b79b77f40706ed598f5403f953c1f791', 1, 0);
REPLACE INTO `resources_categories_properties` (`category_id`, `property_id`, `requestable`, `system`) VALUES ('5a72dfe3f0c0295a8fe4e12c86d4c8f4', '1f8cef2b614382e36eaa4a29f6027edf', 1, 0);
REPLACE INTO `resources_categories_properties` (`category_id`, `property_id`, `requestable`, `system`) VALUES ('5a72dfe3f0c0295a8fe4e12c86d4c8f4', '44fd30e8811d0d962582fa1a9c452bdd', 1, 0);
REPLACE INTO `resources_categories_properties` (`category_id`, `property_id`, `requestable`, `system`) VALUES ('5a72dfe3f0c0295a8fe4e12c86d4c8f4', '613cfdf6aa1072e21a1edfcfb0445c69', 1, 0);
REPLACE INTO `resources_categories_properties` (`category_id`, `property_id`, `requestable`, `system`) VALUES ('5a72dfe3f0c0295a8fe4e12c86d4c8f4', '28addfe18e86cc3587205734c8bc2372', 1, 0);
REPLACE INTO `resources_categories_properties` (`category_id`, `property_id`, `requestable`, `system`) VALUES ('85d62e2a8a87a2924db8fc4ed3fde09d', 'b79b77f40706ed598f5403f953c1f791', 1, 0);
REPLACE INTO `resources_categories_properties` (`category_id`, `property_id`, `requestable`, `system`) VALUES ('f3351baeca8776d4ffe4b672f568cbed', 'cb8140efbc2af5362b1159c65deeec9e', 0, 0);
REPLACE INTO `resources_categories_properties` (`category_id`, `property_id`, `requestable`, `system`) VALUES ('f3351baeca8776d4ffe4b672f568cbed', 'c4352a580051a81830ef5980941c9e06', 0, 0);
REPLACE INTO `resources_categories_properties` (`category_id`, `property_id`, `requestable`, `system`) VALUES ('f3351baeca8776d4ffe4b672f568cbed', '39c73942e1c1650fa20c7259be96b3f3', 0, 0);

-- 
-- Daten für Tabelle `resources_locks`
-- 


-- 
-- Daten für Tabelle `resources_objects`
-- 

REPLACE INTO `resources_objects` (`resource_id`, `root_id`, `parent_id`, `category_id`, `owner_id`, `institut_id`, `level`, `name`, `description`, `lockable`, `multiple_assign`, `mkdate`, `chdate`) VALUES ('0ff09e4f5a729981e978d970f9d970cb', '0ff09e4f5a729981e978d970f9d970cb', '0', '', '76ed43ef286fb55cf9e41beadb484a9f', '', 0, 'Gebäude', '', 0, 0, 1084640001, 1084640009);
REPLACE INTO `resources_objects` (`resource_id`, `root_id`, `parent_id`, `category_id`, `owner_id`, `institut_id`, `level`, `name`, `description`, `lockable`, `multiple_assign`, `mkdate`, `chdate`) VALUES ('f37cbb74e9c9a8a0539bac1f3d6c1e96', 'f37cbb74e9c9a8a0539bac1f3d6c1e96', '0', '', '76ed43ef286fb55cf9e41beadb484a9f', '', 0, 'Geräte', 'Dieses Objekt kennzeichnet eine Hierachie und kann jederzeit in eine Ressource umgewandelt werden', 0, 0, 1084640011, 1084640036);
REPLACE INTO `resources_objects` (`resource_id`, `root_id`, `parent_id`, `category_id`, `owner_id`, `institut_id`, `level`, `name`, `description`, `lockable`, `multiple_assign`, `mkdate`, `chdate`) VALUES ('8a57860ca2be4cc3a77c06c1d346ea57', '0ff09e4f5a729981e978d970f9d970cb', '0ff09e4f5a729981e978d970f9d970cb', '3cbcc99c39476b8e2c8eef5381687461', '76ed43ef286fb55cf9e41beadb484a9f', '', 1, 'Hörsaalgebäude', 'Dieses Objekt wurde neu erstellt. Es wurden noch keine Eigenschaften zugewiesen.', 0, 1, 1084640042, 1084640452);
REPLACE INTO `resources_objects` (`resource_id`, `root_id`, `parent_id`, `category_id`, `owner_id`, `institut_id`, `level`, `name`, `description`, `lockable`, `multiple_assign`, `mkdate`, `chdate`) VALUES ('6350c6ae2ec6fd8bd852d505789d0666', '0ff09e4f5a729981e978d970f9d970cb', '0ff09e4f5a729981e978d970f9d970cb', '3cbcc99c39476b8e2c8eef5381687461', '76ed43ef286fb55cf9e41beadb484a9f', '', 1, 'Übungsgebäude', 'Dieses Objekt wurde neu erstellt. Es wurden noch keine Eigenschaften zugewiesen.', 0, 1, 1084640386, 1084640429);
REPLACE INTO `resources_objects` (`resource_id`, `root_id`, `parent_id`, `category_id`, `owner_id`, `institut_id`, `level`, `name`, `description`, `lockable`, `multiple_assign`, `mkdate`, `chdate`) VALUES ('728f1578de643fb08b32b4b8afb2db77', '0ff09e4f5a729981e978d970f9d970cb', '8a57860ca2be4cc3a77c06c1d346ea57', '85d62e2a8a87a2924db8fc4ed3fde09d', '76ed43ef286fb55cf9e41beadb484a9f', '', 2, 'Hörsaal 1', 'Dieses Objekt wurde neu erstellt. Es wurden noch keine Eigenschaften zugewiesen.', 0, 0, 1084640456, 1084640468);
REPLACE INTO `resources_objects` (`resource_id`, `root_id`, `parent_id`, `category_id`, `owner_id`, `institut_id`, `level`, `name`, `description`, `lockable`, `multiple_assign`, `mkdate`, `chdate`) VALUES ('b17c4ea6e053f2fffba8a5517fc277b3', '0ff09e4f5a729981e978d970f9d970cb', '8a57860ca2be4cc3a77c06c1d346ea57', '85d62e2a8a87a2924db8fc4ed3fde09d', '76ed43ef286fb55cf9e41beadb484a9f', '', 2, 'Hörsaal 2', 'Dieses Objekt wurde neu erstellt. Es wurden noch keine Eigenschaften zugewiesen.', 0, 0, 1084640520, 1084640528);
REPLACE INTO `resources_objects` (`resource_id`, `root_id`, `parent_id`, `category_id`, `owner_id`, `institut_id`, `level`, `name`, `description`, `lockable`, `multiple_assign`, `mkdate`, `chdate`) VALUES ('2f98bf64830043fd98a39fbbe2068678', '0ff09e4f5a729981e978d970f9d970cb', '8a57860ca2be4cc3a77c06c1d346ea57', '85d62e2a8a87a2924db8fc4ed3fde09d', '76ed43ef286fb55cf9e41beadb484a9f', '', 2, 'Hörsaal 3', 'Dieses Objekt wurde neu erstellt. Es wurden noch keine Eigenschaften zugewiesen.', 0, 0, 1084640542, 1084640555);
REPLACE INTO `resources_objects` (`resource_id`, `root_id`, `parent_id`, `category_id`, `owner_id`, `institut_id`, `level`, `name`, `description`, `lockable`, `multiple_assign`, `mkdate`, `chdate`) VALUES ('51ad4b7100d3a8a1db61c7b099f052a6', '0ff09e4f5a729981e978d970f9d970cb', '6350c6ae2ec6fd8bd852d505789d0666', '5a72dfe3f0c0295a8fe4e12c86d4c8f4', '76ed43ef286fb55cf9e41beadb484a9f', '', 2, 'Seminarraum 1', 'Dieses Objekt wurde neu erstellt. Es wurden noch keine Eigenschaften zugewiesen.', 0, 0, 1084640567, 1084640578);
REPLACE INTO `resources_objects` (`resource_id`, `root_id`, `parent_id`, `category_id`, `owner_id`, `institut_id`, `level`, `name`, `description`, `lockable`, `multiple_assign`, `mkdate`, `chdate`) VALUES ('a8c03520e8ad9dc90fb2d161ffca7d7b', '0ff09e4f5a729981e978d970f9d970cb', '6350c6ae2ec6fd8bd852d505789d0666', '5a72dfe3f0c0295a8fe4e12c86d4c8f4', '76ed43ef286fb55cf9e41beadb484a9f', '', 2, 'Seminarraum 2', 'Dieses Objekt wurde neu erstellt. Es wurden noch keine Eigenschaften zugewiesen.', 0, 0, 1084640590, 1084640599);
REPLACE INTO `resources_objects` (`resource_id`, `root_id`, `parent_id`, `category_id`, `owner_id`, `institut_id`, `level`, `name`, `description`, `lockable`, `multiple_assign`, `mkdate`, `chdate`) VALUES ('5ead77812be3b601e2f08ed5da4c5630', '0ff09e4f5a729981e978d970f9d970cb', '6350c6ae2ec6fd8bd852d505789d0666', '5a72dfe3f0c0295a8fe4e12c86d4c8f4', '76ed43ef286fb55cf9e41beadb484a9f', '', 2, 'Seminarraum 3', 'Dieses Objekt wurde neu erstellt. Es wurden noch keine Eigenschaften zugewiesen.', 0, 0, 1084640611, 1084723704);
REPLACE INTO `resources_objects` (`resource_id`, `root_id`, `parent_id`, `category_id`, `owner_id`, `institut_id`, `level`, `name`, `description`, `lockable`, `multiple_assign`, `mkdate`, `chdate`) VALUES ('52b435cbdd021bcc5cd78835de2b4f57', 'f37cbb74e9c9a8a0539bac1f3d6c1e96', 'f37cbb74e9c9a8a0539bac1f3d6c1e96', 'f3351baeca8776d4ffe4b672f568cbed', '76ed43ef286fb55cf9e41beadb484a9f', '', 1, 'Beamer', 'Dieses Objekt wurde neu erstellt. Es wurden noch keine Eigenschaften zugewiesen.', 0, 0, 1084640739, 1084640748);
REPLACE INTO `resources_objects` (`resource_id`, `root_id`, `parent_id`, `category_id`, `owner_id`, `institut_id`, `level`, `name`, `description`, `lockable`, `multiple_assign`, `mkdate`, `chdate`) VALUES ('ffacb5f74be406a524a380f1c135ee02', 'f37cbb74e9c9a8a0539bac1f3d6c1e96', 'f37cbb74e9c9a8a0539bac1f3d6c1e96', 'f3351baeca8776d4ffe4b672f568cbed', '76ed43ef286fb55cf9e41beadb484a9f', '', 1, 'Tageslichtprojektor', 'Dieses Objekt wurde neu erstellt. Es wurden noch keine Eigenschaften zugewiesen.', 0, 0, 1084640761, 1084640778);

-- 
-- Daten für Tabelle `resources_objects_properties`
-- 

REPLACE INTO `resources_objects_properties` (`resource_id`, `property_id`, `state`) VALUES ('6350c6ae2ec6fd8bd852d505789d0666', 'c4f13691419a6c12d38ad83daa926c7c', 'Liebigstr. 1');
REPLACE INTO `resources_objects_properties` (`resource_id`, `property_id`, `state`) VALUES ('6350c6ae2ec6fd8bd852d505789d0666', 'b79b77f40706ed598f5403f953c1f791', 'on');
REPLACE INTO `resources_objects_properties` (`resource_id`, `property_id`, `state`) VALUES ('8a57860ca2be4cc3a77c06c1d346ea57', 'c4f13691419a6c12d38ad83daa926c7c', 'Universitätsstr. 1');
REPLACE INTO `resources_objects_properties` (`resource_id`, `property_id`, `state`) VALUES ('8a57860ca2be4cc3a77c06c1d346ea57', 'b79b77f40706ed598f5403f953c1f791', 'on');
REPLACE INTO `resources_objects_properties` (`resource_id`, `property_id`, `state`) VALUES ('728f1578de643fb08b32b4b8afb2db77', '44fd30e8811d0d962582fa1a9c452bdd', '500');
REPLACE INTO `resources_objects_properties` (`resource_id`, `property_id`, `state`) VALUES ('728f1578de643fb08b32b4b8afb2db77', '7c1a8f6001cfdcb9e9c33eeee0ef343d', 'on');
REPLACE INTO `resources_objects_properties` (`resource_id`, `property_id`, `state`) VALUES ('728f1578de643fb08b32b4b8afb2db77', 'b79b77f40706ed598f5403f953c1f791', 'on');
REPLACE INTO `resources_objects_properties` (`resource_id`, `property_id`, `state`) VALUES ('728f1578de643fb08b32b4b8afb2db77', '613cfdf6aa1072e21a1edfcfb0445c69', 'on');
REPLACE INTO `resources_objects_properties` (`resource_id`, `property_id`, `state`) VALUES ('728f1578de643fb08b32b4b8afb2db77', 'afb8675e2257c03098aa34b2893ba686', 'on');
REPLACE INTO `resources_objects_properties` (`resource_id`, `property_id`, `state`) VALUES ('728f1578de643fb08b32b4b8afb2db77', '1f8cef2b614382e36eaa4a29f6027edf', 'on');
REPLACE INTO `resources_objects_properties` (`resource_id`, `property_id`, `state`) VALUES ('728f1578de643fb08b32b4b8afb2db77', '28addfe18e86cc3587205734c8bc2372', 'on');
REPLACE INTO `resources_objects_properties` (`resource_id`, `property_id`, `state`) VALUES ('b17c4ea6e053f2fffba8a5517fc277b3', '44fd30e8811d0d962582fa1a9c452bdd', '150');
REPLACE INTO `resources_objects_properties` (`resource_id`, `property_id`, `state`) VALUES ('b17c4ea6e053f2fffba8a5517fc277b3', '7c1a8f6001cfdcb9e9c33eeee0ef343d', 'on');
REPLACE INTO `resources_objects_properties` (`resource_id`, `property_id`, `state`) VALUES ('b17c4ea6e053f2fffba8a5517fc277b3', 'b79b77f40706ed598f5403f953c1f791', 'on');
REPLACE INTO `resources_objects_properties` (`resource_id`, `property_id`, `state`) VALUES ('b17c4ea6e053f2fffba8a5517fc277b3', '28addfe18e86cc3587205734c8bc2372', '');
REPLACE INTO `resources_objects_properties` (`resource_id`, `property_id`, `state`) VALUES ('2f98bf64830043fd98a39fbbe2068678', '44fd30e8811d0d962582fa1a9c452bdd', '25');
REPLACE INTO `resources_objects_properties` (`resource_id`, `property_id`, `state`) VALUES ('2f98bf64830043fd98a39fbbe2068678', 'b79b77f40706ed598f5403f953c1f791', 'on');
REPLACE INTO `resources_objects_properties` (`resource_id`, `property_id`, `state`) VALUES ('2f98bf64830043fd98a39fbbe2068678', '613cfdf6aa1072e21a1edfcfb0445c69', 'on');
REPLACE INTO `resources_objects_properties` (`resource_id`, `property_id`, `state`) VALUES ('2f98bf64830043fd98a39fbbe2068678', '28addfe18e86cc3587205734c8bc2372', 'on');
REPLACE INTO `resources_objects_properties` (`resource_id`, `property_id`, `state`) VALUES ('51ad4b7100d3a8a1db61c7b099f052a6', '44fd30e8811d0d962582fa1a9c452bdd', '25');
REPLACE INTO `resources_objects_properties` (`resource_id`, `property_id`, `state`) VALUES ('51ad4b7100d3a8a1db61c7b099f052a6', '613cfdf6aa1072e21a1edfcfb0445c69', 'on');
REPLACE INTO `resources_objects_properties` (`resource_id`, `property_id`, `state`) VALUES ('51ad4b7100d3a8a1db61c7b099f052a6', 'afb8675e2257c03098aa34b2893ba686', 'on');
REPLACE INTO `resources_objects_properties` (`resource_id`, `property_id`, `state`) VALUES ('51ad4b7100d3a8a1db61c7b099f052a6', '28addfe18e86cc3587205734c8bc2372', 'on');
REPLACE INTO `resources_objects_properties` (`resource_id`, `property_id`, `state`) VALUES ('a8c03520e8ad9dc90fb2d161ffca7d7b', '44fd30e8811d0d962582fa1a9c452bdd', '30');
REPLACE INTO `resources_objects_properties` (`resource_id`, `property_id`, `state`) VALUES ('a8c03520e8ad9dc90fb2d161ffca7d7b', '7c1a8f6001cfdcb9e9c33eeee0ef343d', 'on');
REPLACE INTO `resources_objects_properties` (`resource_id`, `property_id`, `state`) VALUES ('a8c03520e8ad9dc90fb2d161ffca7d7b', 'b79b77f40706ed598f5403f953c1f791', 'on');
REPLACE INTO `resources_objects_properties` (`resource_id`, `property_id`, `state`) VALUES ('a8c03520e8ad9dc90fb2d161ffca7d7b', '613cfdf6aa1072e21a1edfcfb0445c69', 'on');
REPLACE INTO `resources_objects_properties` (`resource_id`, `property_id`, `state`) VALUES ('a8c03520e8ad9dc90fb2d161ffca7d7b', 'afb8675e2257c03098aa34b2893ba686', 'on');
REPLACE INTO `resources_objects_properties` (`resource_id`, `property_id`, `state`) VALUES ('a8c03520e8ad9dc90fb2d161ffca7d7b', '28addfe18e86cc3587205734c8bc2372', 'on');
REPLACE INTO `resources_objects_properties` (`resource_id`, `property_id`, `state`) VALUES ('5ead77812be3b601e2f08ed5da4c5630', '44fd30e8811d0d962582fa1a9c452bdd', '15');
REPLACE INTO `resources_objects_properties` (`resource_id`, `property_id`, `state`) VALUES ('5ead77812be3b601e2f08ed5da4c5630', 'afb8675e2257c03098aa34b2893ba686', 'on');
REPLACE INTO `resources_objects_properties` (`resource_id`, `property_id`, `state`) VALUES ('5ead77812be3b601e2f08ed5da4c5630', '1f8cef2b614382e36eaa4a29f6027edf', 'on');
REPLACE INTO `resources_objects_properties` (`resource_id`, `property_id`, `state`) VALUES ('52b435cbdd021bcc5cd78835de2b4f57', 'c4352a580051a81830ef5980941c9e06', '123456789');
REPLACE INTO `resources_objects_properties` (`resource_id`, `property_id`, `state`) VALUES ('52b435cbdd021bcc5cd78835de2b4f57', 'cb8140efbc2af5362b1159c65deeec9e', 'Sony');
REPLACE INTO `resources_objects_properties` (`resource_id`, `property_id`, `state`) VALUES ('52b435cbdd021bcc5cd78835de2b4f57', '39c73942e1c1650fa20c7259be96b3f3', '123132dffd5');
REPLACE INTO `resources_objects_properties` (`resource_id`, `property_id`, `state`) VALUES ('ffacb5f74be406a524a380f1c135ee02', 'c4352a580051a81830ef5980941c9e06', '225566');
REPLACE INTO `resources_objects_properties` (`resource_id`, `property_id`, `state`) VALUES ('ffacb5f74be406a524a380f1c135ee02', 'cb8140efbc2af5362b1159c65deeec9e', 'Telefunken');
REPLACE INTO `resources_objects_properties` (`resource_id`, `property_id`, `state`) VALUES ('ffacb5f74be406a524a380f1c135ee02', '39c73942e1c1650fa20c7259be96b3f3', 'wwqw');
REPLACE INTO `resources_objects_properties` (`resource_id`, `property_id`, `state`) VALUES ('5ead77812be3b601e2f08ed5da4c5630', '28addfe18e86cc3587205734c8bc2372', '');

-- 
-- Daten für Tabelle `resources_properties`
-- 

REPLACE INTO `resources_properties` (`property_id`, `name`, `description`, `type`, `options`, `system`) VALUES ('44fd30e8811d0d962582fa1a9c452bdd', 'Sitzplätze', '', 'num', '', 2);
REPLACE INTO `resources_properties` (`property_id`, `name`, `description`, `type`, `options`, `system`) VALUES ('c4f13691419a6c12d38ad83daa926c7c', 'Adresse', '', 'text', '', 0);
REPLACE INTO `resources_properties` (`property_id`, `name`, `description`, `type`, `options`, `system`) VALUES ('7c1a8f6001cfdcb9e9c33eeee0ef343d', 'Beamer', '', 'bool', 'vorhanden', 0);
REPLACE INTO `resources_properties` (`property_id`, `name`, `description`, `type`, `options`, `system`) VALUES ('b79b77f40706ed598f5403f953c1f791', 'behindertengerecht', '', 'bool', 'vorhanden', 0);
REPLACE INTO `resources_properties` (`property_id`, `name`, `description`, `type`, `options`, `system`) VALUES ('613cfdf6aa1072e21a1edfcfb0445c69', 'Tageslichtprojektor', '', 'bool', 'vorhanden', 0);
REPLACE INTO `resources_properties` (`property_id`, `name`, `description`, `type`, `options`, `system`) VALUES ('afb8675e2257c03098aa34b2893ba686', 'Dozentenrechner', '', 'bool', 'vorhanden', 0);
REPLACE INTO `resources_properties` (`property_id`, `name`, `description`, `type`, `options`, `system`) VALUES ('1f8cef2b614382e36eaa4a29f6027edf', 'Audio-Anlage', '', 'bool', 'vorhanden', 0);
REPLACE INTO `resources_properties` (`property_id`, `name`, `description`, `type`, `options`, `system`) VALUES ('c4352a580051a81830ef5980941c9e06', 'Seriennummer', '', 'num', '', 0);
REPLACE INTO `resources_properties` (`property_id`, `name`, `description`, `type`, `options`, `system`) VALUES ('cb8140efbc2af5362b1159c65deeec9e', 'Hersteller', '', 'select', 'Sony;Philips;Technics;Telefunken;anderer', 0);
REPLACE INTO `resources_properties` (`property_id`, `name`, `description`, `type`, `options`, `system`) VALUES ('39c73942e1c1650fa20c7259be96b3f3', 'Inventarnummer', '', 'num', '', 0);
REPLACE INTO `resources_properties` (`property_id`, `name`, `description`, `type`, `options`, `system`) VALUES ('28addfe18e86cc3587205734c8bc2372', 'Verdunklung', '', 'bool', 'vorhanden', 0);

-- 
-- Daten für Tabelle `resources_requests`
-- 


-- 
-- Daten für Tabelle `resources_requests_properties`
-- 


-- 
-- Daten für Tabelle `resources_temporary_events`
-- 


-- 
-- Daten für Tabelle `resources_user_resources`
-- 


-- 
-- Daten für Tabelle `roles`
-- 

REPLACE INTO `roles` (`roleid`, `rolename`, `system`) VALUES (1, 'Root-Administrator(in)', 'y');
REPLACE INTO `roles` (`roleid`, `rolename`, `system`) VALUES (2, 'Administrator(in)', 'y');
REPLACE INTO `roles` (`roleid`, `rolename`, `system`) VALUES (3, 'Mitarbeiter(in)', 'y');
REPLACE INTO `roles` (`roleid`, `rolename`, `system`) VALUES (4, 'Lehrende(r)', 'y');
REPLACE INTO `roles` (`roleid`, `rolename`, `system`) VALUES (5, 'Studierende(r)', 'y');
REPLACE INTO `roles` (`roleid`, `rolename`, `system`) VALUES (6, 'Tutor(in)', 'y');
REPLACE INTO `roles` (`roleid`, `rolename`, `system`) VALUES (7, 'Nobody', 'y');

-- 
-- Daten für Tabelle `roles_plugins`
-- 

REPLACE INTO `roles_plugins` (`roleid`, `pluginid`) VALUES (1, 1);
REPLACE INTO `roles_plugins` (`roleid`, `pluginid`) VALUES (1, 2);
REPLACE INTO `roles_plugins` (`roleid`, `pluginid`) VALUES (1, 3);
REPLACE INTO `roles_plugins` (`roleid`, `pluginid`) VALUES (2, 1);
REPLACE INTO `roles_plugins` (`roleid`, `pluginid`) VALUES (2, 2);
REPLACE INTO `roles_plugins` (`roleid`, `pluginid`) VALUES (2, 3);
REPLACE INTO `roles_plugins` (`roleid`, `pluginid`) VALUES (3, 1);
REPLACE INTO `roles_plugins` (`roleid`, `pluginid`) VALUES (3, 2);
REPLACE INTO `roles_plugins` (`roleid`, `pluginid`) VALUES (3, 3);
REPLACE INTO `roles_plugins` (`roleid`, `pluginid`) VALUES (4, 1);
REPLACE INTO `roles_plugins` (`roleid`, `pluginid`) VALUES (4, 2);
REPLACE INTO `roles_plugins` (`roleid`, `pluginid`) VALUES (4, 3);
REPLACE INTO `roles_plugins` (`roleid`, `pluginid`) VALUES (5, 1);
REPLACE INTO `roles_plugins` (`roleid`, `pluginid`) VALUES (5, 2);
REPLACE INTO `roles_plugins` (`roleid`, `pluginid`) VALUES (5, 3);
REPLACE INTO `roles_plugins` (`roleid`, `pluginid`) VALUES (6, 1);
REPLACE INTO `roles_plugins` (`roleid`, `pluginid`) VALUES (6, 2);
REPLACE INTO `roles_plugins` (`roleid`, `pluginid`) VALUES (6, 3);

-- 
-- Daten für Tabelle `roles_studipperms`
-- 

REPLACE INTO `roles_studipperms` (`roleid`, `permname`) VALUES (1, 'root');
REPLACE INTO `roles_studipperms` (`roleid`, `permname`) VALUES (2, 'admin');
REPLACE INTO `roles_studipperms` (`roleid`, `permname`) VALUES (3, 'admin');
REPLACE INTO `roles_studipperms` (`roleid`, `permname`) VALUES (3, 'root');
REPLACE INTO `roles_studipperms` (`roleid`, `permname`) VALUES (4, 'dozent');
REPLACE INTO `roles_studipperms` (`roleid`, `permname`) VALUES (5, 'autor');
REPLACE INTO `roles_studipperms` (`roleid`, `permname`) VALUES (5, 'tutor');
REPLACE INTO `roles_studipperms` (`roleid`, `permname`) VALUES (6, 'tutor');

-- 
-- Daten für Tabelle `roles_user`
-- 

REPLACE INTO `roles_user` (`roleid`, `userid`) VALUES (7, 'nobody');

-- 
-- Daten für Tabelle `rss_feeds`
-- 

REPLACE INTO `rss_feeds` (`feed_id`, `user_id`, `name`, `url`, `mkdate`, `chdate`, `priority`, `hidden`, `fetch_title`) VALUES ('486d7fe04aa150a05c259b5ce95bcbbb', '76ed43ef286fb55cf9e41beadb484a9f', 'Stud.IP-Projekt (Stud.IP - Entwicklungsserver der Studip-Crew)', 'http://develop.studip.de/studip/rss.php?id=51fdeef0efc6e3dd72d29eeb0cac2a16', 1156518361, 1156518423, 0, 1, 1);
REPLACE INTO `rss_feeds` (`feed_id`, `user_id`, `name`, `url`, `mkdate`, `chdate`, `priority`, `hidden`, `fetch_title`) VALUES ('7fbdfba36eab17be85d35fbb21a2423f', '205f3efb7997a0fc9755da2b535038da', 'Stud.IP-Blog', 'http://blog.studip.de/feed', 1194629881, 1194629896, 0, 0, 1);

-- 
-- Daten für Tabelle `schema_version`
-- 

REPLACE INTO `schema_version` (`domain`, `version`) VALUES ('studip', 10);

-- 
-- Daten für Tabelle `scm`
-- 

REPLACE INTO `scm` (`scm_id`, `range_id`, `user_id`, `tab_name`, `content`, `mkdate`, `chdate`) VALUES ('63863907e672f85e804de69a04d947c1', '834499e2b8a2cd71637890e5de31cba3', '205f3efb7997a0fc9755da2b535038da', 'Informationen', 'Wenn sie sich für ein Referatsthema anmelden möchten, ordnen Sie sich bitte selbst einer Referatsgruppe zu.\r\n\r\nSie finden diese Gruppen unter\r\n\r\n%%TeilnehmerInnen%%\r\n\r\nund dann \r\n\r\n%%Funktionen / Gruppen%%', 1194628681, 1194628711);
REPLACE INTO `scm` (`scm_id`, `range_id`, `user_id`, `tab_name`, `content`, `mkdate`, `chdate`) VALUES ('1e6c94cdd7033ea745467df9fdfc5083', '834499e2b8a2cd71637890e5de31cba3', '205f3efb7997a0fc9755da2b535038da', 'Pfefferminz', 'Die Pfefferminze (Mentha x piperita) ist eine Heil- und beliebte Gewürzpflanze aus der Gattung der Minzen. Es ist eine Kreuzung zwischen M. aquatica x (und) M. spicata. 2004 wurde sie zur Arzneipflanze des Jahres gewählt.', 1194629051, 1194629136);

-- 
-- Daten für Tabelle `semester_data`
-- 

REPLACE INTO `semester_data` (`semester_id`, `name`, `description`, `semester_token`, `beginn`, `ende`, `vorles_beginn`, `vorles_ende`) VALUES ('f2b4fdf5ac59a9cb57dd73c4d3bbb651', 'WS 2007/08', '', '', 1191189600, 1207000799, 1192312800, 1203116399);

-- 
-- Daten für Tabelle `semester_holiday`
-- 


-- 
-- Daten für Tabelle `seminare`
-- 

REPLACE INTO `seminare` (`Seminar_id`, `VeranstaltungsNummer`, `Institut_id`, `Name`, `Untertitel`, `status`, `Beschreibung`, `Ort`, `Sonstiges`, `Passwort`, `Lesezugriff`, `Schreibzugriff`, `start_time`, `duration_time`, `art`, `teilnehmer`, `vorrausetzungen`, `lernorga`, `leistungsnachweis`, `metadata_dates`, `mkdate`, `chdate`, `ects`, `admission_endtime`, `admission_turnout`, `admission_binding`, `admission_type`, `admission_selection_take_place`, `admission_group`, `admission_prelim`, `admission_prelim_txt`, `admission_starttime`, `admission_endtime_sem`, `admission_disable_waitlist`, `visible`, `showscore`, `modules`, `aux_lock_rule`) VALUES ('834499e2b8a2cd71637890e5de31cba3', '1234', '2560f7c7674942a7dce8eeb238e15d93', 'Test Lehrveranstaltung', 'eine normale Lehrveranstaltung', 1, '', '', '', '', 1, 1, 1191189600, 0, '', 'für alle Studierenden', 'abgeschlossenes Grundstudium', 'Referate in Gruppenarbeit', 'Klausur', 'a:5:{s:3:"art";i:1;s:12:"start_termin";i:-1;s:11:"start_woche";s:1:"0";s:6:"turnus";i:0;s:11:"turnus_data";a:2:{i:0;a:8:{s:3:"idx";i:0;s:3:"day";i:1;s:12:"start_stunde";i:10;s:12:"start_minute";s:2:"00";s:10:"end_stunde";i:12;s:10:"end_minute";s:2:"00";s:4:"desc";s:9:"Vorlesung";s:11:"metadate_id";s:32:"810ae77be611067fcf69f5114f9a0319";}i:1;a:8:{s:3:"idx";i:0;s:3:"day";i:3;s:12:"start_stunde";i:14;s:12:"start_minute";s:2:"00";s:10:"end_stunde";i:16;s:10:"end_minute";s:2:"00";s:4:"desc";s:5:"Übung";s:11:"metadate_id";s:32:"bedbec67efc647fd3123acf00433619f";}}}', 1176472888, 1194626248, '4', -1, 0, 0, 0, 0, '', 0, '', -1, -1, 0, 1, 0, 20911, 'd34f75dbb9936ba300086e096b718242');

-- 
-- Daten für Tabelle `seminar_inst`
-- 

REPLACE INTO `seminar_inst` (`seminar_id`, `institut_id`) VALUES ('834499e2b8a2cd71637890e5de31cba3', '2560f7c7674942a7dce8eeb238e15d93');

-- 
-- Daten für Tabelle `seminar_lernmodul`
-- 


-- 
-- Daten für Tabelle `seminar_sem_tree`
-- 

REPLACE INTO `seminar_sem_tree` (`seminar_id`, `sem_tree_id`) VALUES ('834499e2b8a2cd71637890e5de31cba3', '3d39528c1d560441fd4a8cb0b7717285');
REPLACE INTO `seminar_sem_tree` (`seminar_id`, `sem_tree_id`) VALUES ('834499e2b8a2cd71637890e5de31cba3', '5c41d2b4a5a8338e069dda987a624b74');
REPLACE INTO `seminar_sem_tree` (`seminar_id`, `sem_tree_id`) VALUES ('834499e2b8a2cd71637890e5de31cba3', 'dd7fff9151e85e7130cdb684edf0c370');

-- 
-- Daten für Tabelle `seminar_user`
-- 

REPLACE INTO `seminar_user` (`Seminar_id`, `user_id`, `status`, `position`, `gruppe`, `admission_studiengang_id`, `notification`, `mkdate`, `comment`, `visible`) VALUES ('834499e2b8a2cd71637890e5de31cba3', '205f3efb7997a0fc9755da2b535038da', 'dozent', 0, 2, '', 0, 1156516698, '', 'yes');
REPLACE INTO `seminar_user` (`Seminar_id`, `user_id`, `status`, `position`, `gruppe`, `admission_studiengang_id`, `notification`, `mkdate`, `comment`, `visible`) VALUES ('834499e2b8a2cd71637890e5de31cba3', '7e81ec247c151c02ffd479511e24cc03', 'tutor', 0, 2, '', 0, 1156516698, '', 'yes');
REPLACE INTO `seminar_user` (`Seminar_id`, `user_id`, `status`, `position`, `gruppe`, `admission_studiengang_id`, `notification`, `mkdate`, `comment`, `visible`) VALUES ('834499e2b8a2cd71637890e5de31cba3', 'e7a0a84b161f3e8c09b4a0a2e8a58147', 'autor', 0, 2, '', 0, 1156516698, '', 'yes');

-- 
-- Daten für Tabelle `seminar_user_schedule`
-- 


-- 
-- Daten für Tabelle `sem_tree`
-- 

REPLACE INTO `sem_tree` (`sem_tree_id`, `parent_id`, `priority`, `info`, `name`, `studip_object_id`) VALUES ('5b73e28644a3e259a6e0bc1e1499773c', 'root', 1, '', '', '1535795b0d6ddecac6813f5f6ac47ef2');
REPLACE INTO `sem_tree` (`sem_tree_id`, `parent_id`, `priority`, `info`, `name`, `studip_object_id`) VALUES ('439618ae57d8c10dcaabcf7e21bcc1d9', '5b73e28644a3e259a6e0bc1e1499773c', 0, '', 'Test Studienbereich A', NULL);
REPLACE INTO `sem_tree` (`sem_tree_id`, `parent_id`, `priority`, `info`, `name`, `studip_object_id`) VALUES ('5c41d2b4a5a8338e069dda987a624b74', '5b73e28644a3e259a6e0bc1e1499773c', 1, '', 'Test Studienbereich B', NULL);
REPLACE INTO `sem_tree` (`sem_tree_id`, `parent_id`, `priority`, `info`, `name`, `studip_object_id`) VALUES ('3d39528c1d560441fd4a8cb0b7717285', '439618ae57d8c10dcaabcf7e21bcc1d9', 0, '', 'Test Studienbereich A-1', NULL);
REPLACE INTO `sem_tree` (`sem_tree_id`, `parent_id`, `priority`, `info`, `name`, `studip_object_id`) VALUES ('dd7fff9151e85e7130cdb684edf0c370', '439618ae57d8c10dcaabcf7e21bcc1d9', 1, '', 'Test Studienbereich A-2', NULL);
REPLACE INTO `sem_tree` (`sem_tree_id`, `parent_id`, `priority`, `info`, `name`, `studip_object_id`) VALUES ('01c8b1d188be40c5ac64b54a01aae294', '5b73e28644a3e259a6e0bc1e1499773c', 2, '', 'Test Studienbereich C', NULL);

-- 
-- Daten für Tabelle `session_data`
-- 

REPLACE INTO `session_data` (`sid`, `val`, `changed`) VALUES ('ec159b28c3e5f20076a3234dbc9b6c4b', 'auth|O:12:"Seminar_Auth":2:{s:4:"auth";a:9:{s:3:"uid";s:32:"76ed43ef286fb55cf9e41beadb484a9f";s:4:"perm";s:4:"root";s:3:"exp";i:1194637966;s:7:"refresh";i:1194632081;s:5:"uname";s:11:"root@studip";s:7:"jscript";b:1;s:11:"auth_plugin";N;s:4:"xres";s:4:"1280";s:4:"yres";s:3:"800";}s:9:"classname";s:12:"Seminar_Auth";}SessionStart|i:1194632071;SessionSeminar|s:32:"834499e2b8a2cd71637890e5de31cba3";SessSemName|a:10:{i:0;s:22:"Test Lehrveranstaltung";i:1;s:32:"834499e2b8a2cd71637890e5de31cba3";i:3;s:30:"eine normale Lehrveranstaltung";i:4;s:10:"1191189600";i:5;s:32:"2560f7c7674942a7dce8eeb238e15d93";s:11:"art_generic";s:13:"Veranstaltung";s:5:"class";s:3:"sem";s:7:"art_num";s:1:"1";s:3:"art";s:9:"Vorlesung";s:11:"header_line";s:33:"Vorlesung: Test Lehrveranstaltung";}messenger_started|N;object_cache|a:0:{}contact|N;_language|s:5:"de_DE";index_data|a:5:{s:7:"comopen";s:0:"";s:6:"comnew";s:0:"";s:9:"comsubmit";s:0:"";s:6:"comdel";s:0:"";s:10:"comdelnews";s:0:"";}last_ticket|s:32:"b44c718cf86e82a4c0e01c21fc774049";_default_sem|s:32:"f2b4fdf5ac59a9cb57dd73c4d3bbb651";PLUGIN_SESSION_SPACE|a:1:{s:35:"de_studip_core_rolemanagementplugin";a:1:{s:5:"roles";s:616:"a:7:{i:2;O:14:"de_studip_Role":2:{s:6:"roleid";s:1:"2";s:8:"rolename";s:17:"Administrator(in)";}i:4;O:14:"de_studip_Role":2:{s:6:"roleid";s:1:"4";s:8:"rolename";s:11:"Lehrende(r)";}i:3;O:14:"de_studip_Role":2:{s:6:"roleid";s:1:"3";s:8:"rolename";s:15:"Mitarbeiter(in)";}i:7;O:14:"de_studip_Role":2:{s:6:"roleid";s:1:"7";s:8:"rolename";s:6:"Nobody";}i:1;O:14:"de_studip_Role":2:{s:6:"roleid";s:1:"1";s:8:"rolename";s:22:"Root-Administrator(in)";}i:5;O:14:"de_studip_Role":2:{s:6:"roleid";s:1:"5";s:8:"rolename";s:14:"Studierende(r)";}i:6;O:14:"de_studip_Role":2:{s:6:"roleid";s:1:"6";s:8:"rolename";s:9:"Tutor(in)";}}";}}vote_HTTP_REFERER_1|s:46:"http://localhost/studip_1.6.0/seminar_main.php";vote_HTTP_REFERER_2|s:87:"http://localhost/studip_1.6.0/seminar_main.php?auswahl=834499e2b8a2cd71637890e5de31cba3";links_admin_data|a:4:{s:10:"select_old";b:1;s:8:"srch_sem";R:37;s:6:"sortby";s:4:"Name";s:6:"topkat";s:3:"sem";}sem_create_data|s:0:"";admin_dates_data|s:0:"";smain_data|a:5:{s:7:"comopen";s:0:"";s:6:"comnew";s:0:"";s:9:"comsubmit";s:0:"";s:6:"comdel";s:0:"";s:10:"comdelnews";s:0:"";}open_users|a:1:{i:0;s:32:"205f3efb7997a0fc9755da2b535038da";}', '2007-11-09 19:52:46');

-- 
-- Daten für Tabelle `smiley`
-- 


-- 
-- Daten für Tabelle `statusgruppen`
-- 

REPLACE INTO `statusgruppen` (`statusgruppe_id`, `name`, `range_id`, `position`, `size`, `selfassign`, `mkdate`, `chdate`) VALUES ('86498c641ccf4f4d4e02f4961ccc3829', 'Lehrbeauftragte', '2560f7c7674942a7dce8eeb238e15d93', 3, 0, 0, 1156516698, 1156516698);
REPLACE INTO `statusgruppen` (`statusgruppe_id`, `name`, `range_id`, `position`, `size`, `selfassign`, `mkdate`, `chdate`) VALUES ('600403561c21a50ae8b4d41655bd2191', 'HochschullehrerIn', '2560f7c7674942a7dce8eeb238e15d93', 4, 0, 0, 1156516698, 1156516698);
REPLACE INTO `statusgruppen` (`statusgruppe_id`, `name`, `range_id`, `position`, `size`, `selfassign`, `mkdate`, `chdate`) VALUES ('efb56e092f33cb78a8766676042dc1c5', 'wiss. MitarbeiterIn', '2560f7c7674942a7dce8eeb238e15d93', 2, 0, 0, 1156516698, 1156516698);
REPLACE INTO `statusgruppen` (`statusgruppe_id`, `name`, `range_id`, `position`, `size`, `selfassign`, `mkdate`, `chdate`) VALUES ('5d40b1fc0434e6589d7341a3ee742baf', 'DirektorIn', '2560f7c7674942a7dce8eeb238e15d93', 1, 0, 0, 1156516698, 1156516698);
REPLACE INTO `statusgruppen` (`statusgruppe_id`, `name`, `range_id`, `position`, `size`, `selfassign`, `mkdate`, `chdate`) VALUES ('41ad59c9b6cdafca50e42fe6bc68af4f', 'Thema 1', '834499e2b8a2cd71637890e5de31cba3', 2, 3, 2, 1194628738, 1194629392);
REPLACE INTO `statusgruppen` (`statusgruppe_id`, `name`, `range_id`, `position`, `size`, `selfassign`, `mkdate`, `chdate`) VALUES ('151c33059a90b6138d280862f5d4b3c2', 'Thema 2', '834499e2b8a2cd71637890e5de31cba3', 3, 3, 2, 1194628768, 1194628768);
REPLACE INTO `statusgruppen` (`statusgruppe_id`, `name`, `range_id`, `position`, `size`, `selfassign`, `mkdate`, `chdate`) VALUES ('a5061826bf8db7487a774f92ce2a4d23', 'Thema 3', '834499e2b8a2cd71637890e5de31cba3', 4, 3, 2, 1194628789, 1194628789);
REPLACE INTO `statusgruppen` (`statusgruppe_id`, `name`, `range_id`, `position`, `size`, `selfassign`, `mkdate`, `chdate`) VALUES ('ee5764d68c795815c9dd8b2448313fb6', 'DozentInnen', '834499e2b8a2cd71637890e5de31cba3', 1, 0, 0, 1194628816, 1194628816);

-- 
-- Daten für Tabelle `statusgruppe_user`
-- 

REPLACE INTO `statusgruppe_user` (`statusgruppe_id`, `user_id`, `position`, `visible`, `inherit`) VALUES ('efb56e092f33cb78a8766676042dc1c5', '7e81ec247c151c02ffd479511e24cc03', 1, 1, 1);
REPLACE INTO `statusgruppe_user` (`statusgruppe_id`, `user_id`, `position`, `visible`, `inherit`) VALUES ('5d40b1fc0434e6589d7341a3ee742baf', '205f3efb7997a0fc9755da2b535038da', 1, 1, 1);
REPLACE INTO `statusgruppe_user` (`statusgruppe_id`, `user_id`, `position`, `visible`, `inherit`) VALUES ('ee5764d68c795815c9dd8b2448313fb6', '205f3efb7997a0fc9755da2b535038da', 1, 1, 1);

-- 
-- Daten für Tabelle `stm_abstract`
-- 


-- 
-- Daten für Tabelle `stm_abstract_assign`
-- 


-- 
-- Daten für Tabelle `stm_abstract_elements`
-- 


-- 
-- Daten für Tabelle `stm_abstract_text`
-- 


-- 
-- Daten für Tabelle `stm_abstract_types`
-- 


-- 
-- Daten für Tabelle `stm_element_types`
-- 


-- 
-- Daten für Tabelle `stm_instances`
-- 


-- 
-- Daten für Tabelle `stm_instances_elements`
-- 


-- 
-- Daten für Tabelle `stm_instances_text`
-- 


-- 
-- Daten für Tabelle `studiengaenge`
-- 

REPLACE INTO `studiengaenge` (`studiengang_id`, `name`, `beschreibung`, `mkdate`, `chdate`) VALUES ('63b13b29db6adcf0e2814a6388d4583c', 'Test Studiengang 1', '', 1156516698, 1156516698);
REPLACE INTO `studiengaenge` (`studiengang_id`, `name`, `beschreibung`, `mkdate`, `chdate`) VALUES ('4a55e9df07a18e76ebb84e27ae212b30', 'Test Studiengang 2', '', 1156516698, 1156516698);

-- 
-- Daten für Tabelle `studip_ilias`
-- 


-- 
-- Daten für Tabelle `teilnehmer_view`
-- 


-- 
-- Daten für Tabelle `termine`
-- 

REPLACE INTO `termine` (`termin_id`, `range_id`, `autor_id`, `content`, `description`, `date`, `end_time`, `mkdate`, `chdate`, `date_typ`, `topic_id`, `raum`, `metadate_id`) VALUES ('d9ad9a46c90f89ebab6a09e093e70a31', '834499e2b8a2cd71637890e5de31cba3', '205f3efb7997a0fc9755da2b535038da', '', NULL, 1192435200, 1192442400, 1194626456, 1194626456, 1, NULL, '', '810ae77be611067fcf69f5114f9a0319');
REPLACE INTO `termine` (`termin_id`, `range_id`, `autor_id`, `content`, `description`, `date`, `end_time`, `mkdate`, `chdate`, `date_typ`, `topic_id`, `raum`, `metadate_id`) VALUES ('cf4ba9031e3cee95f450a080cb96fc90', '834499e2b8a2cd71637890e5de31cba3', '205f3efb7997a0fc9755da2b535038da', '', NULL, 1193040000, 1193047200, 1194626456, 1194626456, 1, NULL, '', '810ae77be611067fcf69f5114f9a0319');
REPLACE INTO `termine` (`termin_id`, `range_id`, `autor_id`, `content`, `description`, `date`, `end_time`, `mkdate`, `chdate`, `date_typ`, `topic_id`, `raum`, `metadate_id`) VALUES ('a7e1fa522e8f7a11960bcd84aac09992', '834499e2b8a2cd71637890e5de31cba3', '205f3efb7997a0fc9755da2b535038da', '', NULL, 1193648400, 1193655600, 1194626456, 1194626456, 1, NULL, '', '810ae77be611067fcf69f5114f9a0319');
REPLACE INTO `termine` (`termin_id`, `range_id`, `autor_id`, `content`, `description`, `date`, `end_time`, `mkdate`, `chdate`, `date_typ`, `topic_id`, `raum`, `metadate_id`) VALUES ('186ad08900137157cefbb524f65675f7', '834499e2b8a2cd71637890e5de31cba3', '205f3efb7997a0fc9755da2b535038da', '', NULL, 1194253200, 1194260400, 1194626456, 1194626456, 1, NULL, '', '810ae77be611067fcf69f5114f9a0319');
REPLACE INTO `termine` (`termin_id`, `range_id`, `autor_id`, `content`, `description`, `date`, `end_time`, `mkdate`, `chdate`, `date_typ`, `topic_id`, `raum`, `metadate_id`) VALUES ('c78e12c9ba44a939f03eccb2a1bd9fa1', '834499e2b8a2cd71637890e5de31cba3', '205f3efb7997a0fc9755da2b535038da', '', NULL, 1194858000, 1194865200, 1194626456, 1194626456, 1, NULL, '', '810ae77be611067fcf69f5114f9a0319');
REPLACE INTO `termine` (`termin_id`, `range_id`, `autor_id`, `content`, `description`, `date`, `end_time`, `mkdate`, `chdate`, `date_typ`, `topic_id`, `raum`, `metadate_id`) VALUES ('becc9db6b7debf53b839d57f937d6bb0', '834499e2b8a2cd71637890e5de31cba3', '205f3efb7997a0fc9755da2b535038da', '', NULL, 1195462800, 1195470000, 1194626456, 1194626456, 1, NULL, '', '810ae77be611067fcf69f5114f9a0319');
REPLACE INTO `termine` (`termin_id`, `range_id`, `autor_id`, `content`, `description`, `date`, `end_time`, `mkdate`, `chdate`, `date_typ`, `topic_id`, `raum`, `metadate_id`) VALUES ('866b35e3930e147ba0d3fd1946a24025', '834499e2b8a2cd71637890e5de31cba3', '205f3efb7997a0fc9755da2b535038da', '', NULL, 1196067600, 1196074800, 1194626456, 1194626456, 1, NULL, '', '810ae77be611067fcf69f5114f9a0319');
REPLACE INTO `termine` (`termin_id`, `range_id`, `autor_id`, `content`, `description`, `date`, `end_time`, `mkdate`, `chdate`, `date_typ`, `topic_id`, `raum`, `metadate_id`) VALUES ('8576f11d2fd87c850ca229bc9437cb98', '834499e2b8a2cd71637890e5de31cba3', '205f3efb7997a0fc9755da2b535038da', '', NULL, 1196672400, 1196679600, 1194626456, 1194626456, 1, NULL, '', '810ae77be611067fcf69f5114f9a0319');
REPLACE INTO `termine` (`termin_id`, `range_id`, `autor_id`, `content`, `description`, `date`, `end_time`, `mkdate`, `chdate`, `date_typ`, `topic_id`, `raum`, `metadate_id`) VALUES ('09a296c2391530a51433168bd937a963', '834499e2b8a2cd71637890e5de31cba3', '205f3efb7997a0fc9755da2b535038da', '', NULL, 1197277200, 1197284400, 1194626456, 1194626456, 1, NULL, '', '810ae77be611067fcf69f5114f9a0319');
REPLACE INTO `termine` (`termin_id`, `range_id`, `autor_id`, `content`, `description`, `date`, `end_time`, `mkdate`, `chdate`, `date_typ`, `topic_id`, `raum`, `metadate_id`) VALUES ('7c29e5226b93c6be8fc849cd6cc505ec', '834499e2b8a2cd71637890e5de31cba3', '205f3efb7997a0fc9755da2b535038da', '', NULL, 1197882000, 1197889200, 1194626456, 1194626456, 1, NULL, '', '810ae77be611067fcf69f5114f9a0319');
REPLACE INTO `termine` (`termin_id`, `range_id`, `autor_id`, `content`, `description`, `date`, `end_time`, `mkdate`, `chdate`, `date_typ`, `topic_id`, `raum`, `metadate_id`) VALUES ('621ff3e470e1627a801d8825307ca35b', '834499e2b8a2cd71637890e5de31cba3', '205f3efb7997a0fc9755da2b535038da', '', NULL, 1198486800, 1198494000, 1194626456, 1194626456, 1, NULL, '', '810ae77be611067fcf69f5114f9a0319');
REPLACE INTO `termine` (`termin_id`, `range_id`, `autor_id`, `content`, `description`, `date`, `end_time`, `mkdate`, `chdate`, `date_typ`, `topic_id`, `raum`, `metadate_id`) VALUES ('e6bff77284de9dc6535d67918c3f93e7', '834499e2b8a2cd71637890e5de31cba3', '205f3efb7997a0fc9755da2b535038da', '', NULL, 1199091600, 1199098800, 1194626456, 1194626456, 1, NULL, '', '810ae77be611067fcf69f5114f9a0319');
REPLACE INTO `termine` (`termin_id`, `range_id`, `autor_id`, `content`, `description`, `date`, `end_time`, `mkdate`, `chdate`, `date_typ`, `topic_id`, `raum`, `metadate_id`) VALUES ('78784a6f7b7969edd5192b78f0dfefb8', '834499e2b8a2cd71637890e5de31cba3', '205f3efb7997a0fc9755da2b535038da', '', NULL, 1199696400, 1199703600, 1194626456, 1194626456, 1, NULL, '', '810ae77be611067fcf69f5114f9a0319');
REPLACE INTO `termine` (`termin_id`, `range_id`, `autor_id`, `content`, `description`, `date`, `end_time`, `mkdate`, `chdate`, `date_typ`, `topic_id`, `raum`, `metadate_id`) VALUES ('00a08faecfd3fd9ef6928cf855c25959', '834499e2b8a2cd71637890e5de31cba3', '205f3efb7997a0fc9755da2b535038da', '', NULL, 1200301200, 1200308400, 1194626456, 1194626456, 1, NULL, '', '810ae77be611067fcf69f5114f9a0319');
REPLACE INTO `termine` (`termin_id`, `range_id`, `autor_id`, `content`, `description`, `date`, `end_time`, `mkdate`, `chdate`, `date_typ`, `topic_id`, `raum`, `metadate_id`) VALUES ('0f000151977c2a1293669fdb9e89cd90', '834499e2b8a2cd71637890e5de31cba3', '205f3efb7997a0fc9755da2b535038da', '', NULL, 1200906000, 1200913200, 1194626456, 1194626456, 1, NULL, '', '810ae77be611067fcf69f5114f9a0319');
REPLACE INTO `termine` (`termin_id`, `range_id`, `autor_id`, `content`, `description`, `date`, `end_time`, `mkdate`, `chdate`, `date_typ`, `topic_id`, `raum`, `metadate_id`) VALUES ('d3942eda4baa7f68d19259f1b61cd8fb', '834499e2b8a2cd71637890e5de31cba3', '205f3efb7997a0fc9755da2b535038da', '', NULL, 1201510800, 1201518000, 1194626456, 1194626456, 1, NULL, '', '810ae77be611067fcf69f5114f9a0319');
REPLACE INTO `termine` (`termin_id`, `range_id`, `autor_id`, `content`, `description`, `date`, `end_time`, `mkdate`, `chdate`, `date_typ`, `topic_id`, `raum`, `metadate_id`) VALUES ('a5676b0e5e1e240b140f4689f450d3d5', '834499e2b8a2cd71637890e5de31cba3', '205f3efb7997a0fc9755da2b535038da', '', NULL, 1202115600, 1202122800, 1194626456, 1194626456, 1, NULL, '', '810ae77be611067fcf69f5114f9a0319');
REPLACE INTO `termine` (`termin_id`, `range_id`, `autor_id`, `content`, `description`, `date`, `end_time`, `mkdate`, `chdate`, `date_typ`, `topic_id`, `raum`, `metadate_id`) VALUES ('fd58ad5cc329cd37aacd4799264406e6', '834499e2b8a2cd71637890e5de31cba3', '205f3efb7997a0fc9755da2b535038da', '', NULL, 1202720400, 1202727600, 1194626456, 1194626456, 1, NULL, '', '810ae77be611067fcf69f5114f9a0319');
REPLACE INTO `termine` (`termin_id`, `range_id`, `autor_id`, `content`, `description`, `date`, `end_time`, `mkdate`, `chdate`, `date_typ`, `topic_id`, `raum`, `metadate_id`) VALUES ('e8b6d0af741fbc4350ee7004825be589', '834499e2b8a2cd71637890e5de31cba3', '205f3efb7997a0fc9755da2b535038da', '', NULL, 1192622400, 1192629600, 1194626456, 1194626456, 1, NULL, '', 'bedbec67efc647fd3123acf00433619f');
REPLACE INTO `termine` (`termin_id`, `range_id`, `autor_id`, `content`, `description`, `date`, `end_time`, `mkdate`, `chdate`, `date_typ`, `topic_id`, `raum`, `metadate_id`) VALUES ('66b522e49fd876383e87ae9ebd759faa', '834499e2b8a2cd71637890e5de31cba3', '205f3efb7997a0fc9755da2b535038da', '', NULL, 1193227200, 1193234400, 1194626456, 1194626456, 1, NULL, '', 'bedbec67efc647fd3123acf00433619f');
REPLACE INTO `termine` (`termin_id`, `range_id`, `autor_id`, `content`, `description`, `date`, `end_time`, `mkdate`, `chdate`, `date_typ`, `topic_id`, `raum`, `metadate_id`) VALUES ('907f9903383bff246539b02d1a919285', '834499e2b8a2cd71637890e5de31cba3', '205f3efb7997a0fc9755da2b535038da', '', NULL, 1193835600, 1193842800, 1194626456, 1194626456, 1, NULL, '', 'bedbec67efc647fd3123acf00433619f');
REPLACE INTO `termine` (`termin_id`, `range_id`, `autor_id`, `content`, `description`, `date`, `end_time`, `mkdate`, `chdate`, `date_typ`, `topic_id`, `raum`, `metadate_id`) VALUES ('12ed4570e1a35de4856c274cc8987e45', '834499e2b8a2cd71637890e5de31cba3', '205f3efb7997a0fc9755da2b535038da', '', NULL, 1194440400, 1194447600, 1194626456, 1194626456, 1, NULL, '', 'bedbec67efc647fd3123acf00433619f');
REPLACE INTO `termine` (`termin_id`, `range_id`, `autor_id`, `content`, `description`, `date`, `end_time`, `mkdate`, `chdate`, `date_typ`, `topic_id`, `raum`, `metadate_id`) VALUES ('fe2bfcb3cb650807dbab4b57dd55b5f1', '834499e2b8a2cd71637890e5de31cba3', '205f3efb7997a0fc9755da2b535038da', '', NULL, 1195045200, 1195052400, 1194626456, 1194626456, 1, NULL, '', 'bedbec67efc647fd3123acf00433619f');
REPLACE INTO `termine` (`termin_id`, `range_id`, `autor_id`, `content`, `description`, `date`, `end_time`, `mkdate`, `chdate`, `date_typ`, `topic_id`, `raum`, `metadate_id`) VALUES ('95fe449be6e60237ab5f5276b2357927', '834499e2b8a2cd71637890e5de31cba3', '205f3efb7997a0fc9755da2b535038da', '', NULL, 1195650000, 1195657200, 1194626456, 1194626456, 1, NULL, '', 'bedbec67efc647fd3123acf00433619f');
REPLACE INTO `termine` (`termin_id`, `range_id`, `autor_id`, `content`, `description`, `date`, `end_time`, `mkdate`, `chdate`, `date_typ`, `topic_id`, `raum`, `metadate_id`) VALUES ('3d3f8ea9dece864468812de78a61a0d9', '834499e2b8a2cd71637890e5de31cba3', '205f3efb7997a0fc9755da2b535038da', '', NULL, 1196254800, 1196262000, 1194626456, 1194626456, 1, NULL, '', 'bedbec67efc647fd3123acf00433619f');
REPLACE INTO `termine` (`termin_id`, `range_id`, `autor_id`, `content`, `description`, `date`, `end_time`, `mkdate`, `chdate`, `date_typ`, `topic_id`, `raum`, `metadate_id`) VALUES ('2972469f5eac63e15c6352f93e3aa1f8', '834499e2b8a2cd71637890e5de31cba3', '205f3efb7997a0fc9755da2b535038da', '', NULL, 1196859600, 1196866800, 1194626456, 1194626456, 1, NULL, '', 'bedbec67efc647fd3123acf00433619f');
REPLACE INTO `termine` (`termin_id`, `range_id`, `autor_id`, `content`, `description`, `date`, `end_time`, `mkdate`, `chdate`, `date_typ`, `topic_id`, `raum`, `metadate_id`) VALUES ('bd8c73de50779ba14af6bbb0fb47ee99', '834499e2b8a2cd71637890e5de31cba3', '205f3efb7997a0fc9755da2b535038da', '', NULL, 1197464400, 1197471600, 1194626456, 1194626456, 1, NULL, '', 'bedbec67efc647fd3123acf00433619f');
REPLACE INTO `termine` (`termin_id`, `range_id`, `autor_id`, `content`, `description`, `date`, `end_time`, `mkdate`, `chdate`, `date_typ`, `topic_id`, `raum`, `metadate_id`) VALUES ('4dcef923d0a7fe6a954c011ab173d121', '834499e2b8a2cd71637890e5de31cba3', '205f3efb7997a0fc9755da2b535038da', '', NULL, 1198069200, 1198076400, 1194626456, 1194626456, 1, NULL, '', 'bedbec67efc647fd3123acf00433619f');
REPLACE INTO `termine` (`termin_id`, `range_id`, `autor_id`, `content`, `description`, `date`, `end_time`, `mkdate`, `chdate`, `date_typ`, `topic_id`, `raum`, `metadate_id`) VALUES ('11e7c8a55a2fe91ca9a2f325a917019e', '834499e2b8a2cd71637890e5de31cba3', '205f3efb7997a0fc9755da2b535038da', '', NULL, 1199278800, 1199286000, 1194626456, 1194626456, 1, NULL, '', 'bedbec67efc647fd3123acf00433619f');
REPLACE INTO `termine` (`termin_id`, `range_id`, `autor_id`, `content`, `description`, `date`, `end_time`, `mkdate`, `chdate`, `date_typ`, `topic_id`, `raum`, `metadate_id`) VALUES ('a6b4491f6c250408d3683022c958905c', '834499e2b8a2cd71637890e5de31cba3', '205f3efb7997a0fc9755da2b535038da', '', NULL, 1199883600, 1199890800, 1194626456, 1194626456, 1, NULL, '', 'bedbec67efc647fd3123acf00433619f');
REPLACE INTO `termine` (`termin_id`, `range_id`, `autor_id`, `content`, `description`, `date`, `end_time`, `mkdate`, `chdate`, `date_typ`, `topic_id`, `raum`, `metadate_id`) VALUES ('08cb4b7c110185d3be99c0a032e0547f', '834499e2b8a2cd71637890e5de31cba3', '205f3efb7997a0fc9755da2b535038da', '', NULL, 1200488400, 1200495600, 1194626456, 1194626456, 1, NULL, '', 'bedbec67efc647fd3123acf00433619f');
REPLACE INTO `termine` (`termin_id`, `range_id`, `autor_id`, `content`, `description`, `date`, `end_time`, `mkdate`, `chdate`, `date_typ`, `topic_id`, `raum`, `metadate_id`) VALUES ('32dfad1e7d401507c55b5490685d68ee', '834499e2b8a2cd71637890e5de31cba3', '205f3efb7997a0fc9755da2b535038da', '', NULL, 1201093200, 1201100400, 1194626456, 1194626456, 1, NULL, '', 'bedbec67efc647fd3123acf00433619f');
REPLACE INTO `termine` (`termin_id`, `range_id`, `autor_id`, `content`, `description`, `date`, `end_time`, `mkdate`, `chdate`, `date_typ`, `topic_id`, `raum`, `metadate_id`) VALUES ('19bec6e7a8ea3519c3c5c7e26ec12ccf', '834499e2b8a2cd71637890e5de31cba3', '205f3efb7997a0fc9755da2b535038da', '', NULL, 1201698000, 1201705200, 1194626456, 1194626456, 1, NULL, '', 'bedbec67efc647fd3123acf00433619f');
REPLACE INTO `termine` (`termin_id`, `range_id`, `autor_id`, `content`, `description`, `date`, `end_time`, `mkdate`, `chdate`, `date_typ`, `topic_id`, `raum`, `metadate_id`) VALUES ('9bdeb7102d800bff802279371f53427f', '834499e2b8a2cd71637890e5de31cba3', '205f3efb7997a0fc9755da2b535038da', '', NULL, 1202302800, 1202310000, 1194626456, 1194626456, 1, NULL, '', 'bedbec67efc647fd3123acf00433619f');
REPLACE INTO `termine` (`termin_id`, `range_id`, `autor_id`, `content`, `description`, `date`, `end_time`, `mkdate`, `chdate`, `date_typ`, `topic_id`, `raum`, `metadate_id`) VALUES ('75c6781674206570a148ec44afcbc420', '834499e2b8a2cd71637890e5de31cba3', '205f3efb7997a0fc9755da2b535038da', '', NULL, 1202907600, 1202914800, 1194626456, 1194626456, 1, NULL, '', 'bedbec67efc647fd3123acf00433619f');

-- 
-- Daten für Tabelle `themen`
-- 


-- 
-- Daten für Tabelle `themen_termine`
-- 


-- 
-- Daten für Tabelle `user_config`
-- 


-- 
-- Daten für Tabelle `user_data`
-- 

REPLACE INTO `user_data` (`sid`, `val`, `changed`) VALUES ('76ed43ef286fb55cf9e41beadb484a9f', 'a:10:{s:12:"CurrentLogin";i:1194632071;s:9:"LastLogin";i:1194625662;s:5:"forum";N;s:9:"writemode";N;s:21:"my_messaging_settings";a:18:{s:16:"show_only_buddys";b:0;s:28:"delete_messages_after_logout";b:0;s:26:"start_messenger_at_startup";b:0;s:11:"active_time";i:5;s:14:"default_setted";i:1194625834;s:10:"last_login";b:0;s:10:"timefilter";s:3:"30d";s:7:"opennew";i:1;s:17:"logout_markreaded";b:0;s:7:"openall";b:0;s:12:"addsignature";b:0;s:8:"save_snd";i:1;s:7:"sms_sig";b:0;s:9:"send_view";b:0;s:14:"last_box_visit";i:1;s:6:"folder";a:2:{s:2:"in";a:1:{i:0;s:5:"dummy";}s:3:"out";a:1:{i:0;s:5:"dummy";}}s:15:"confirm_reading";i:3;s:15:"show_sndpicture";b:0;}s:20:"my_schedule_settings";a:4:{s:14:"glb_start_time";i:8;s:12:"glb_end_time";i:19;s:8:"glb_days";a:7:{s:2:"mo";s:4:"TRUE";s:2:"di";s:4:"TRUE";s:2:"mi";s:4:"TRUE";s:2:"do";s:4:"TRUE";s:2:"fr";s:4:"TRUE";s:2:"sa";s:0:"";s:2:"so";s:0:"";}s:14:"default_setted";i:1194625834;}s:16:"my_personal_sems";N;s:18:"my_studip_settings";N;s:18:"homepage_cache_own";N;s:26:"calendar_user_control_data";a:12:{s:4:"view";s:8:"showweek";s:5:"start";i:9;s:3:"end";i:20;s:8:"step_day";i:900;s:9:"step_week";i:3600;s:9:"type_week";s:4:"LONG";s:8:"holidays";b:1;s:8:"sem_data";b:1;s:9:"link_edit";b:1;s:13:"bind_seminare";s:0:"";s:16:"ts_bind_seminare";i:0;s:6:"delete";i:0;}}', '2007-11-09 19:52:46');
REPLACE INTO `user_data` (`sid`, `val`, `changed`) VALUES ('205f3efb7997a0fc9755da2b535038da', 'a:12:{s:12:"CurrentLogin";i:1194625924;s:9:"LastLogin";N;s:5:"forum";a:10:{s:9:"themeview";s:4:"tree";s:10:"presetview";s:4:"tree";s:10:"sortthemes";s:3:"asc";s:4:"view";s:4:"tree";s:4:"sort";s:3:"age";s:9:"indikator";s:3:"age";s:6:"anchor";N;s:6:"update";N;s:5:"zitat";N;s:8:"openlist";s:2:";;";}s:9:"writemode";N;s:21:"my_messaging_settings";a:18:{s:16:"show_only_buddys";b:0;s:28:"delete_messages_after_logout";b:0;s:26:"start_messenger_at_startup";b:0;s:11:"active_time";i:5;s:14:"default_setted";i:1194625939;s:10:"last_login";b:0;s:10:"timefilter";s:3:"30d";s:7:"opennew";i:1;s:17:"logout_markreaded";b:0;s:7:"openall";s:1:"2";s:12:"addsignature";b:0;s:8:"save_snd";i:1;s:7:"sms_sig";b:0;s:9:"send_view";b:0;s:14:"last_box_visit";i:1194629822;s:6:"folder";a:2:{s:2:"in";a:1:{i:0;s:5:"dummy";}s:3:"out";a:1:{i:0;s:5:"dummy";}}s:15:"confirm_reading";i:3;s:15:"show_sndpicture";b:0;}s:20:"my_schedule_settings";a:4:{s:14:"glb_start_time";i:8;s:12:"glb_end_time";i:19;s:8:"glb_days";a:7:{s:2:"mo";s:4:"TRUE";s:2:"di";s:4:"TRUE";s:2:"mi";s:4:"TRUE";s:2:"do";s:4:"TRUE";s:2:"fr";s:4:"TRUE";s:2:"sa";s:0:"";s:2:"so";s:0:"";}s:14:"default_setted";i:1194625939;}s:16:"my_personal_sems";N;s:18:"my_studip_settings";N;s:18:"homepage_cache_own";i:1194629874;s:26:"calendar_user_control_data";a:12:{s:4:"view";s:8:"showweek";s:5:"start";i:9;s:3:"end";i:20;s:8:"step_day";i:900;s:9:"step_week";i:3600;s:9:"type_week";s:4:"LONG";s:8:"holidays";b:1;s:8:"sem_data";b:1;s:9:"link_edit";b:1;s:13:"bind_seminare";s:0:"";s:16:"ts_bind_seminare";i:0;s:6:"delete";i:0;}s:12:"_my_sem_open";a:1:{s:11:"not_grouped";b:1;}s:19:"_my_sem_group_field";s:11:"not_grouped";}', '2007-11-09 18:59:31');

-- 
-- Daten für Tabelle `user_info`
-- 

REPLACE INTO `user_info` (`user_id`, `hobby`, `lebenslauf`, `publi`, `schwerp`, `Home`, `privatnr`, `privatcell`, `privadr`, `score`, `geschlecht`, `mkdate`, `chdate`, `title_front`, `title_rear`, `preferred_language`, `smsforward_copy`, `smsforward_rec`, `guestbook`, `email_forward`, `smiley_favorite`, `smiley_favorite_publish`, `motto`) VALUES ('76ed43ef286fb55cf9e41beadb484a9f', '', NULL, '', '', '', '', '', '', 0, 0, 0, 0, '', '', NULL, 1, '', 0, 0, '', 0, '');
REPLACE INTO `user_info` (`user_id`, `hobby`, `lebenslauf`, `publi`, `schwerp`, `Home`, `privatnr`, `privatcell`, `privadr`, `score`, `geschlecht`, `mkdate`, `chdate`, `title_front`, `title_rear`, `preferred_language`, `smsforward_copy`, `smsforward_rec`, `guestbook`, `email_forward`, `smiley_favorite`, `smiley_favorite_publish`, `motto`) VALUES ('e7a0a84b161f3e8c09b4a0a2e8a58147', '', NULL, '', '', '', '', '', '', 0, 0, 0, 0, '', '', NULL, 1, '', 0, 0, '', 0, '');
REPLACE INTO `user_info` (`user_id`, `hobby`, `lebenslauf`, `publi`, `schwerp`, `Home`, `privatnr`, `privatcell`, `privadr`, `score`, `geschlecht`, `mkdate`, `chdate`, `title_front`, `title_rear`, `preferred_language`, `smsforward_copy`, `smsforward_rec`, `guestbook`, `email_forward`, `smiley_favorite`, `smiley_favorite_publish`, `motto`) VALUES ('205f3efb7997a0fc9755da2b535038da', '', NULL, '', '', '', '', '', '', 0, 0, 0, 0, '', '', NULL, 1, '', 0, 0, '', 0, '');
REPLACE INTO `user_info` (`user_id`, `hobby`, `lebenslauf`, `publi`, `schwerp`, `Home`, `privatnr`, `privatcell`, `privadr`, `score`, `geschlecht`, `mkdate`, `chdate`, `title_front`, `title_rear`, `preferred_language`, `smsforward_copy`, `smsforward_rec`, `guestbook`, `email_forward`, `smiley_favorite`, `smiley_favorite_publish`, `motto`) VALUES ('6235c46eb9e962866ebdceece739ace5', '', NULL, '', '', '', '', '', '', 0, 0, 0, 0, '', '', NULL, 1, '', 0, 0, '', 0, '');
REPLACE INTO `user_info` (`user_id`, `hobby`, `lebenslauf`, `publi`, `schwerp`, `Home`, `privatnr`, `privatcell`, `privadr`, `score`, `geschlecht`, `mkdate`, `chdate`, `title_front`, `title_rear`, `preferred_language`, `smsforward_copy`, `smsforward_rec`, `guestbook`, `email_forward`, `smiley_favorite`, `smiley_favorite_publish`, `motto`) VALUES ('7e81ec247c151c02ffd479511e24cc03', '', NULL, '', '', '', '', '', '', 0, 0, 0, 0, '', '', NULL, 1, '', 0, 0, '', 0, '');

-- 
-- Daten für Tabelle `user_inst`
-- 

REPLACE INTO `user_inst` (`user_id`, `Institut_id`, `inst_perms`, `sprechzeiten`, `raum`, `Telefon`, `Fax`, `externdefault`, `priority`, `visible`) VALUES ('205f3efb7997a0fc9755da2b535038da', '2560f7c7674942a7dce8eeb238e15d93', 'dozent', '', '', '', '', 0, 0, 1);
REPLACE INTO `user_inst` (`user_id`, `Institut_id`, `inst_perms`, `sprechzeiten`, `raum`, `Telefon`, `Fax`, `externdefault`, `priority`, `visible`) VALUES ('6235c46eb9e962866ebdceece739ace5', '2560f7c7674942a7dce8eeb238e15d93', 'admin', '', '', '', '', 0, 0, 1);
REPLACE INTO `user_inst` (`user_id`, `Institut_id`, `inst_perms`, `sprechzeiten`, `raum`, `Telefon`, `Fax`, `externdefault`, `priority`, `visible`) VALUES ('7e81ec247c151c02ffd479511e24cc03', '2560f7c7674942a7dce8eeb238e15d93', 'tutor', '', '', '', '', 0, 0, 1);
REPLACE INTO `user_inst` (`user_id`, `Institut_id`, `inst_perms`, `sprechzeiten`, `raum`, `Telefon`, `Fax`, `externdefault`, `priority`, `visible`) VALUES ('e7a0a84b161f3e8c09b4a0a2e8a58147', '2560f7c7674942a7dce8eeb238e15d93', 'user', '', '', '', '', 0, 0, 1);

-- 
-- Daten für Tabelle `user_studiengang`
-- 


-- 
-- Daten für Tabelle `user_token`
-- 


-- 
-- Daten für Tabelle `vote`
-- 

REPLACE INTO `vote` (`vote_id`, `author_id`, `range_id`, `type`, `title`, `question`, `state`, `startdate`, `stopdate`, `timespan`, `mkdate`, `chdate`, `resultvisibility`, `multiplechoice`, `anonymous`, `changeable`, `co_visibility`, `namesvisibility`) VALUES ('b5329b23b7f865c62028e226715e1914', '76ed43ef286fb55cf9e41beadb484a9f', 'studip', 'vote', 'Nutzen Sie bereits Stud.IP?', 'Haben Sie Stud.IP bereits im Einsatz oder planen Sie, es einzusetzen?', 'active', 1176473101, NULL, NULL, 1142525062, 1176473102, 'delivery', 1, 0, 1, NULL, 0);

