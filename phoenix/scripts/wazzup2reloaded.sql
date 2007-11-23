-- `merlin_categories` --

ALTER TABLE `merlin_categories` CHANGE `id` `category_id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
CHANGE `creatoruserid` `category_creatorid` INT( 11 ) NOT NULL DEFAULT '0',
CHANGE `name` `category_name` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
CHANGE `description` `category_description` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
CHANGE `created` `category_created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
CHANGE `parentcategoryid` `category_parentid` INT( 11 ) NOT NULL DEFAULT '0',
CHANGE `delid` `category_delid` INT( 11 ) NOT NULL DEFAULT '0',
CHANGE `icon` `category_icon` INT( 11 ) NOT NULL DEFAULT '0'

------------------------------

-- `merlin_chat` --

 ALTER TABLE `merlin_chat` CHANGE `chat_ip` `chat_userip` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL 

------------------------------

-- `merlin_comments` --

ALTER TABLE `merlin_comments` ADD `commentraw` longtext NOT NULL;
ALTER TABLE `merlin_comments` ADD `typeid` int(11) NOT NULL default '0';

ALTER TABLE `merlin_comments` CHANGE `id` `comment_id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
CHANGE `userid` `comment_userid` INT( 11 ) NOT NULL DEFAULT '0',
CHANGE `submitdate` `comment_created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
CHANGE `submithost` `comment_userip` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
CHANGE `comment` `comment_text` LONGTEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
CHANGE `commentraw` `comment_textraw` LONGTEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
CHANGE `storyid` `comment_storyid` INT( 11 ) NOT NULL DEFAULT '0',
CHANGE `parentcommentid` `comment_parentid` INT( 11 ) NOT NULL DEFAULT '0',
CHANGE `delid` `comment_delid` INT( 11 ) NOT NULL DEFAULT '0',
CHANGE `stars` `comment_stars` INT( 11 ) NOT NULL DEFAULT '0',
CHANGE `votes` `comment_votes` INT( 11 ) NOT NULL DEFAULT '0',
CHANGE `typeid` `comment_typeid` INT( 11 ) NOT NULL DEFAULT '0'

------------------------------

--`merlin_images`--

ALTER TABLE `merlin_images` CHANGE `id` `image_id` INT( 11 ) NOT NULL auto_increment;
ALTER TABLE `merlin_images` CHANGE `userid` `image_userid` INT( 11 ) NOT NULL default '0';
ALTER TABLE `merlin_images` CHANGE `submitdate` `image_submitdate` datetime NOT NULL default '0000-00-00 00:00:00';
ALTER TABLE `merlin_images` CHANGE `submithost` `image_submithost`  text NOT NULL;
ALTER TABLE `merlin_images` CHANGE `name` `image_name` text NOT NULL;
ALTER TABLE `merlin_images` DROP `image`;
ALTER TABLE `merlin_images` ADD `image_mime` text NOT NULL;

ALTER TABLE `merlin_images` CHANGE `image_submitdate` `image_created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
CHANGE `image_submithost` `image_userip` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL 

------------------------------

-- `merlin_ipban` --

 ALTER TABLE `merlin_ipban` CHANGE `id` `ipban_id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
CHANGE `banip` `ipban_ip` VARCHAR( 32 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
CHANGE `bandate` `ipban_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
CHANGE `expiredate` `ipban_expiredate` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
CHANGE `sysopid` `ipban_sysopid` INT( 11 ) NOT NULL DEFAULT '0' 

------------------------------

-- `merlin_logs` --

------------------------------

-- `merlin_monitor` --

------------------------------

-- `merlin_places` --

ALTER TABLE `merlin_places` CHANGE `id` `place_id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
CHANGE `name` `place_name` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
CHANGE `x` `place_x` INT( 11 ) NOT NULL DEFAULT '0',
CHANGE `y` `place_y` INT( 11 ) NOT NULL DEFAULT '0',
CHANGE `updateuserid` `place_updateuserid` INT( 11 ) NOT NULL DEFAULT '0',
CHANGE `updatedate` `place_updatedate` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
CHANGE `updateip` `place_updateip` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL 

------------------------------

-- `merlin_pms` --

ALTER TABLE `merlin_pms` ADD `pm_textraw` longtext NOT NULL;

ALTER TABLE `merlin_pms` CHANGE `id` `pm_id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
CHANGE `from` `pm_from` INT( 11 ) NOT NULL DEFAULT '0',
CHANGE `to` `pm_to` INT( 11 ) NOT NULL DEFAULT '0',
CHANGE `submitdate` `pm_created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
CHANGE `submithost` `pm_userip` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
CHANGE `text` `pm_text` LONGTEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
CHANGE `delid` `pm_delid` INT( 11 ) NOT NULL DEFAULT '0'

------------------------------

-- `merlin_pmscondition` --

CREATE TABLE `merlin_pmscondition` (
 `pmcondition_id` int(11) NOT NULL auto_increment,
 `pmcondition_pmid` int(11) NOT NULL default '0',
 `pmcondition_userid` int(11) NOT NULL default '0',
 `pmcondition_folderid` int(11) NOT NULL default '0',
 `pmcondition_delid` int(11) NOT NULL default '0',
 PRIMARY KEY( `pmcondition_id` )
);

------------------------------

-- `merlin_pmsfolders` --

CREATE TABLE `merlin_pmsfolders` (
 `pmsfolder_id` int(11) NOT NULL auto_increment,
 `pmsfolder_name` text NOT NULL,
 `pmsfolder_displayname` text NOT NULL,
 `pmsfolder_userid` int(11) NOT NULL default '0',
 `pmsfolder_icon` text NOT NULL,
 `pmsfolder_created` datetime NOT NULL default '0000-00-00 00:00:00',
 `pmsfolder_parentid` int(11) NOT NULL default '0',
 `pmsfolder_delid` int(11) NOT NULL default '0',
 PRIMARY KEY( `pmsfolder_id` )
);

------------------------------

-- `merlin_polloptions` --

------------------------------

-- `merlin_polls` --

 ALTER TABLE `merlin_polls` CHANGE `poll_date` `poll_created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'

------------------------------

-- `merlin_profile` --

ALTER TABLE `merlin_profile` CHANGE `id` `profile_id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
CHANGE `userid` `profile_userid` INT( 11 ) NOT NULL DEFAULT '0',
CHANGE `answer` `profile_answer` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
CHANGE `questionid` `profile_questionid` INT( 11 ) NOT NULL DEFAULT '0',
CHANGE `date` `profile_date` DATE NOT NULL DEFAULT '0000-00-00',
CHANGE `deleted` `profile_delid` INT( 11 ) NOT NULL DEFAULT '0',
CHANGE `submithost` `profile_userip` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL 

------------------------------

-- `merlin_profileq` --

 ALTER TABLE `merlin_profileq` CHANGE `id` `profileq_id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
CHANGE `userid` `profileq_userid` INT( 11 ) NOT NULL DEFAULT '0',
CHANGE `date` `profileq_created` DATE NOT NULL DEFAULT '0000-00-00',
CHANGE `question` `profileq_question` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
CHANGE `permissions` `profileq_permissions` INT( 11 ) NOT NULL DEFAULT '0',
CHANGE `submithost` `profileq_userip` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
CHANGE `deleted` `profileq_delid` INT( 11 ) NOT NULL DEFAULT '0'

------------------------------

-- `merlin_ricons` --

------------------------------

-- `merlin_searches` --

 ALTER TABLE `merlin_searches` CHANGE `search_host` `search_userip` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL 

------------------------------

-- `merlin_shoutbox` --

ALTER TABLE `merlin_shoutbox` ADD `shout_delid` int(11) NOT NULL default '0';
ALTER TABLE `merlin_shoutbox` ADD `shout_delreason` text NOT NULL;
ALTER TABLE `merlin_shoutbox` ADD `shout_deluserid` longtext NOT NULL;

ALTER TABLE `merlin_shoutbox` CHANGE `id` `shout_id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
CHANGE `userid` `shout_userid` INT( 11 ) NOT NULL DEFAULT '0',
CHANGE `text` `shout_text` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
CHANGE `submitdate` `shout_created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
CHANGE `submithost` `shout_userip` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL

------------------------------

-- `merlin_starring` --

 ALTER TABLE `merlin_starring` CHANGE `starring_ip` `starring_userip` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL 

------------------------------

-- `merlin_stories` --

ALTER TABLE `merlin_stories` ADD `storyraw` longtext NOT NULL;
ALTER TABLE `merlin_stories` ADD `showemoticons` enum('yes','no') NOT NULL default 'no';

------------------------------

-- `merlin_templates` --

ALTER TABLE `merlin_templates` CHANGE `templateid` `template_id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
CHANGE `name` `template_name` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
CHANGE `css` `template_css` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
CHANGE `author` `template_creator` INT( 11 ) NOT NULL DEFAULT '0',
CHANGE `updateauthor` `template_updateuserid` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
CHANGE `updatedate` `template_updated` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
CHANGE `updateip` `template_updateip` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
CHANGE `delid` `template_delid` INT( 11 ) NOT NULL DEFAULT '0'

------------------------------

-- `merlin_userban` --

------------------------------

-- `merlin_users` --

ALTER TABLE `merlin_users` ADD `user_templateid` int(11) NOT NULL default '0'; -- we do not use this yet
ALTER TABLE `merlin_users` ADD `user_shoutboxactivated` enum('yes','no') NOT NULL default 'no'; -- we do not use this yet
ALTER TABLE `merlin_users` ADD `user_contribs` int(11) NOT NULL default '0'; -- we do not use this yet
ALTER TABLE `merlin_users` ADD `user_respect` int(11) NOT NULL default '0'; -- we do not use this yet

ALTER TABLE `merlin_users` CHANGE `id` `user_id` INT(11) NOT NULL AUTO_INCREMENT,
CHANGE `name` `user_name` LONGTEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
CHANGE `password` `user_password` LONGTEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
CHANGE `created` `user_created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
CHANGE `registerhost` `user_registerhost` VARCHAR(32) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
CHANGE `lastlogon` `user_lastlogon` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
CHANGE `rights` `user_rights` INT(11) NOT NULL DEFAULT '0',
CHANGE `email` `user_email` LONGTEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
CHANGE `signature` `user_signature` LONGTEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
CHANGE `icon` `user_icon` INT(11) NOT NULL DEFAULT '0',
CHANGE `gender` `user_gender` ENUM('-','male','female') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '-',
CHANGE `msn` `user_msn` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL, 
CHANGE `yim` `user_yim` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL, 
CHANGE `aim` `user_aim` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL, 
CHANGE `icq` `user_icq` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL, 
CHANGE `gtalk` `user_gtalk` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL, 
CHANGE `dob` `user_dob` DATE NOT NULL DEFAULT '0000-00-00', 
CHANGE `hobbies` `user_hobbies` LONGTEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL, 
CHANGE `subtitle` `user_subtitle` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL, 
CHANGE `blogid` `user_blogid` INT(11) NOT NULL DEFAULT '0', 
CHANGE `place` `user_place` INT(11) NOT NULL DEFAULT '0', 
CHANGE `lowres` `user_lowres` ENUM('no','yes') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'no', 
CHANGE `inchat` `user_inchat` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00', 
CHANGE `lastprofedit` `user_lastprofedit` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00', 
CHANGE `starpoints` `user_starpoints` INT(11) NOT NULL DEFAULT '0', 
CHANGE `starpointsexpire` `user_starpointsexpire` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00', 
CHANGE `locked` `user_locked` ENUM('no','yes') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'no', 
CHANGE `lastactive` `user_lastactive` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'

------------------------------

-- `merlin_usershout` --

CREATE TABLE `merlin_usershout` (
 `usershout_id` int(11) NOT NULL auto_increment,
 `usershout_userid` int(11) NOT NULL default '0',
 `usershout_shoutowner` int(11) NOT NULL default '0',
 `usershout_text` text NOT NULL,
 `usershout_created` datetime NOT NULL default '0000-00-00 00:00:00',
 `usershout_userip` text NOT NULL,
 PRIMARY KEY( `id` )
);

------------------------------

-- `merlin_vars` --

------------------------------

-- `merlin_votes` --

------------------------------

-- `merlin_pageviews` --

CREATE TABLE `merlin_pageviews` (
 `pageview_id` int(11) NOT NULL auto_increment,
 `pageview_date` datetime NOT NULL default '0000-00-00 00:00:00',
 `pageview_type` enum('story', 'user	') NOT NULL default 'story',
 `pageview_userid` int(11) NOT NULL,
 `pageview_pageid` int(11) NOT NULL,
 PRIMARY KEY( `pageview_id` )
);
------------------------------
 