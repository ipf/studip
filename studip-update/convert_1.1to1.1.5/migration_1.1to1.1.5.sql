# mysql migration script
# base version: 1.1
# update version: 1.1.5

PLEASE NOTE: Since there exists no migration-tool, please use this script MANUEL to convert your old database to
a newer version.
Don`t paste this script directly into your SQL-client, because you have to excute the convert scripts and/or delete-queries 
in the specified order! 

# For detailed informations, please take a look at the update protocol from our installation in Goettingen!
# (Should be located in the same folder)

# #1
# create new table for visits
#

 	
CREATE TABLE `object_user_visits` (
`object_id` char(32) NOT NULL default '',
`user_id` char(32) NOT NULL default '',
`type` enum('vote','documents','forum','literature','schedule','scm','sem','wiki','news','eval','inst','ilias_connect') NOT NULL default 'vote',
`visitdate` int(20) NOT NULL default '0',
`last_visitdate` int(20) NOT NULL default '0',
PRIMARY KEY (`object_id`,`user_id`,`type`)
) TYPE=MyISAM;

# #2
# >>>please use the script convert_loginfile.php at this point
#

# #3
# changes to the messaging system
#

ALTER TABLE `message` ADD `subject` VARCHAR( 255 ) NOT NULL AFTER `autor_id` ;
ALTER TABLE `message` ADD `readed` TINYINT( 1 ) DEFAULT '0' NOT NULL AFTER `mkdate` ;
ALTER TABLE `message` ADD `reading_confirmation` TINYINT( 1 ) DEFAULT '0' NOT NULL;
ALTER TABLE `message_user` ADD `confirmed_read` TINYINT( 1 ) DEFAULT '0' NOT NULL ;
ALTER TABLE `message_user` ADD `answered` TINYINT( 1 ) DEFAULT '0' NOT NULL ; 

# #4
# alter message_user table to be static with optimized indizes
# (if the table contains many rows (>100000) this will take some time
#also make sure you got a backup of your databse if the following batch fails)

CREATE TABLE `message_user_tmp` (
  `user_id` char(32) NOT NULL default '',
  `message_id` char(32) NOT NULL default '',
  `readed` tinyint(1) NOT NULL default '0',
  `deleted` tinyint(1) NOT NULL default '0',
  `snd_rec` enum('rec','snd') NOT NULL default 'rec',
  `dont_delete` tinyint(1) NOT NULL default '0',
  `folder` tinyint(4) UNSIGNED NOT NULL default '0',
  `confirmed_read` tinyint(1) NOT NULL default '0',
  `answered` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`message_id`,`snd_rec`,`user_id`),
  KEY `user_id` (`user_id`,`snd_rec`,`deleted`,`readed`),
  KEY `user_id_2` (`user_id`,`snd_rec`,`deleted`,`folder`)
) TYPE=MyISAM;
#uncomment next line if Mysql Version > 4
#ALTER TABLE message_user_tmp DISABLE KEYS;
INSERT INTO message_user_tmp SELECT * FROM message_user;
#uncomment next line if Mysql Version > 4
#ALTER TABLE message_user_tmp ENABLE KEYS;
DROP TABLE message_user ;
ALTER TABLE message_user_tmp RENAME message_user;

# #5
# >>>please use the script convert_sms_subject.php at this point
#

# #6
# >>>please use the script convert_sms_user_info.php at this point
#

# #7
# changes to the wiki
#

ALTER TABLE `wiki` CHANGE `keyword` `keyword` VARCHAR( 128 ) BINARY NOT NULL ;
ALTER TABLE `wiki_links` CHANGE `to_keyword` `to_keyword` CHAR( 128 ) BINARY NOT NULL ;
ALTER TABLE `wiki_links` CHANGE `from_keyword` `from_keyword` CHAR( 128 ) BINARY NOT NULL ;
ALTER TABLE `wiki_locks` CHANGE `keyword` `keyword` VARCHAR( 128 ) BINARY NOT NULL ;

# #8
# create new table and changes for new smiley-management
#

CREATE TABLE `smiley` (
  `smiley_id` bigint(20) NOT NULL auto_increment,
  `smiley_name` varchar(50) NOT NULL default '',
  `smiley_width` int(11) NOT NULL default '0',
  `smiley_height` int(11) NOT NULL default '0',
  `short_name` varchar(50) NOT NULL default '',
  `smiley_counter` bigint(20) NOT NULL default '0',
  `short_counter` bigint(20) NOT NULL default '0',
  `fav_counter` bigint(20) NOT NULL default '0',
  `mkdate` int(10) unsigned default NULL,
  `chdate` int(10) unsigned default NULL,
  PRIMARY KEY  (`smiley_id`),
  UNIQUE KEY `name` (`smiley_name`),
  KEY `short` (`short_name`)
) TYPE=MyISAM;

ALTER TABLE user_info
  ADD smiley_favorite VARCHAR(255) NOT NULL ,
  ADD smiley_favorite_publish TINYINT(1) DEFAULT '0' NOT NULL ;
  
# #9
# changes to the table user_inst
#

ALTER TABLE `user_inst` ADD `externdefault` TINYINT( 3 ) UNSIGNED DEFAULT '0' NOT NULL ,
ADD `priority` TINYINT( 3 ) UNSIGNED DEFAULT '0' NOT NULL ;
ALTER TABLE `user_inst` CHANGE `raum` `raum` VARCHAR( 200 ) NOT NULL, 
ADD `visible` TINYINT UNSIGNED DEFAULT '1' NOT NULL ; 

# #10
# change the indexes of votes for better performance
#

ALTER TABLE `voteanswers_user` ADD INDEX ( `user_id` );

#11
#
#
ALTER TABLE `object_views` ADD INDEX ( `views` ) ;

#12
#
#
ALTER TABLE `seminar_user` DROP INDEX `Seminar_id` ;
ALTER TABLE `seminar_user` ADD INDEX ( `status` , `Seminar_id` ) ;

#13
#
#
ALTER TABLE `active_sessions` DROP PRIMARY KEY ;
ALTER TABLE `active_sessions` DROP INDEX `changed`;

ALTER TABLE `active_sessions` ADD PRIMARY KEY ( `sid` , `name` );
ALTER TABLE `active_sessions` ADD INDEX ( `name` , `changed`);

#14
#
#
ALTER TABLE `user_inst` DROP INDEX `inst_perms` ;
ALTER TABLE `user_inst` DROP INDEX `user_id` ;
ALTER TABLE `user_inst` DROP INDEX `Institut_id` ;
ALTER TABLE `user_inst` DROP PRIMARY KEY ;

ALTER TABLE `user_inst` CHANGE `inst_perms` `inst_perms` VARCHAR( 10 ) NOT NULL ;
ALTER TABLE `user_inst` ADD PRIMARY KEY ( `Institut_id` , `user_id` ) ;
ALTER TABLE `user_inst` ADD INDEX ( `user_id` , `inst_perms` ) ;
ALTER TABLE `user_inst` ADD INDEX ( `inst_perms` , `Institut_id` ) ;

#15
#
#
ALTER TABLE `contact` DROP INDEX `owner_id` ;
ALTER TABLE `contact` DROP INDEX `user_id` ;
ALTER TABLE `contact` ADD INDEX ( `owner_id` , `buddy`, `user_id` ) ;

#16
#
#
ALTER TABLE `Institute` ADD INDEX ( `fakultaets_id` ) ;


