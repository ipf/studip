<?php
$xslt_files =
$ELEARNING_INTERFACE_MODULES =
$PLUGIN_REPOSITORIES =
$STUDIP_DOMAINS =
$INSTALLED_LANGUAGES =
$_lit_search_plugins =
$STUDIP_AUTH_PLUGIN =
$LATEX_FORMATS =
$WIKI_PLUGINS =
$SEM_TYPE =
$SEM_CLASS =
$UPLOAD_TYPES =
$TEILNEHMER_VIEW =
$INST_TYPE =
$SEM_STATUS_GROUPS =
$INST_STATUS_GROUPS =
$SCM_PRESET =
$INST_MODULES =
$TERMIN_TYP =
$PERS_TERMIN_KAT =
$TIME_PRESETS =
$TITLE_FRONT_TEMPLATE =
$TITLE_REAR_TEMPLATE =
$NAME_FORMAT_DESC =
$SMILE_SHORT =
$SYMBOL_SHORT =
$LIT_IMPORT_PLUGINS =
$INST_ADMIN_DATAFIELDS_VIEW =
$export_o_modes =
$export_ex_types =
$output_formats =
$export_icon =
$record_of_study_templates =
$ilias_status =
$ilias_systemgroup =
$_fullname_sql =
$SEM_TREE_TYPES =
array();

// Notwendige Config-Einträge für Studiengruppen
// (Veranstaltungen, die von Autoren angelegt werden können)
$SEM_TYPE[99]=array("name"=>_("Studiengruppe"), "class"=>99, "short" => "Sg", "title_dozent" => array(_("GruppengründerIn"), _("GruppengründerInnen")), 
																			  "title_tutor"  => array(_("ModeratorIn"), _("ModeratorInnen")), 
																			  "title_autor" => array(_("Mitglied"), _("Mitglieder")));
$SEM_CLASS[99]=array("name"=>_("Studiengruppen"),
					"studygroup_mode"=>TRUE,
					"topic_create_autor"=>TRUE,
					"course_creation_forbidden" => TRUE);

