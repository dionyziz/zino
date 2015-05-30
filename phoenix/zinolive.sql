-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 12, 2014 at 05:57 PM
-- Server version: 5.5.40
-- PHP Version: 5.3.10-1ubuntu3.15

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `zinolive`
--

-- --------------------------------------------------------

--
-- Table structure for table `adminactions`
--

CREATE TABLE IF NOT EXISTS `adminactions` (
  `adminaction_id` int(11) NOT NULL,
  `adminaction_adminid` int(11) NOT NULL,
  `adminaction_date` datetime NOT NULL,
  `adminaction_typeid` int(11) NOT NULL,
  `adminaction_itemid` int(11) NOT NULL,
  PRIMARY KEY (`adminaction_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `adplaces`
--

CREATE TABLE IF NOT EXISTS `adplaces` (
  `ad_active` tinyint(1) NOT NULL,
  `ad_pageviewsremaining` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `ads`
--

CREATE TABLE IF NOT EXISTS `ads` (
  `ad_id` int(11) NOT NULL AUTO_INCREMENT,
  `ad_userid` int(11) NOT NULL DEFAULT '0',
  `ad_title` varchar(256) COLLATE utf8_bin DEFAULT '',
  `ad_body` text COLLATE utf8_bin,
  `ad_url` varchar(256) COLLATE utf8_bin DEFAULT '',
  `ad_imageid` int(11) unsigned NOT NULL DEFAULT '0',
  `ad_budget` int(11) unsigned NOT NULL DEFAULT '0',
  `ad_dailypageviews` int(11) unsigned NOT NULL DEFAULT '0',
  `ad_pageviewsremaining` int(11) unsigned NOT NULL DEFAULT '0',
  `ad_pageviewsremainingtoday` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`ad_id`),
  KEY `USER_ADS` (`ad_userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `albums`
--

CREATE TABLE IF NOT EXISTS `albums` (
  `album_id` int(11) NOT NULL AUTO_INCREMENT,
  `album_ownerid` int(11) NOT NULL DEFAULT '0',
  `album_ownertype` int(11) NOT NULL DEFAULT '0',
  `album_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `album_userip` int(11) NOT NULL DEFAULT '0',
  `album_name` varchar(255) COLLATE utf8_bin DEFAULT '',
  `album_mainimage` int(11) NOT NULL DEFAULT '0',
  `album_description` varchar(511) COLLATE utf8_bin DEFAULT '',
  `album_delid` int(11) NOT NULL DEFAULT '0',
  `album_numcomments` int(11) NOT NULL DEFAULT '0',
  `album_numphotos` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`album_id`),
  KEY `USER` (`album_ownerid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `answers`
--

CREATE TABLE IF NOT EXISTS `answers` (
  `answer_questionid` int(11) NOT NULL,
  `answer_userid` int(11) NOT NULL,
  PRIMARY KEY (`answer_questionid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `badges`
--

CREATE TABLE IF NOT EXISTS `badges` (
  `badge_itemid` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `bannedips`
--

CREATE TABLE IF NOT EXISTS `bannedips` (
  `bannedips_ip` int(11) NOT NULL,
  `bannedips_expire` datetime NOT NULL,
  PRIMARY KEY (`bannedips_ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `bannedusers`
--

CREATE TABLE IF NOT EXISTS `bannedusers` (
  `banned_userid` int(11) NOT NULL,
  `banneduser_expire` datetime NOT NULL,
  PRIMARY KEY (`banneduser_expire`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `bulk`
--

CREATE TABLE IF NOT EXISTS `bulk` (
  `bulk_id` int(11) NOT NULL,
  `bulk_text` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`bulk_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `chatchannels`
--

CREATE TABLE IF NOT EXISTS `chatchannels` (
  `channel_id` int(11) NOT NULL,
  PRIMARY KEY (`channel_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `chatparticipants`
--

CREATE TABLE IF NOT EXISTS `chatparticipants` (
  `participant_channelid` int(11) NOT NULL,
  `participant_userid` int(11) NOT NULL,
  PRIMARY KEY (`participant_userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `chatvideo`
--

CREATE TABLE IF NOT EXISTS `chatvideo` (
  `video_channelid` int(11) NOT NULL,
  `video_userid` int(11) NOT NULL,
  PRIMARY KEY (`video_userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `coins`
--

CREATE TABLE IF NOT EXISTS `coins` (
  `coins_userid` int(11) NOT NULL,
  `coins_amnt` int(11) NOT NULL,
  PRIMARY KEY (`coins_userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `comment_id` int(11) NOT NULL,
  `comment_delid` int(11) NOT NULL,
  `comment_typeid` int(11) NOT NULL,
  `comment_parentid` int(11) NOT NULL,
  `comment_itemid` int(11) NOT NULL,
  `comment_created` datetime NOT NULL,
  `comment_userip` int(11) NOT NULL,
  `comment_userid` int(11) NOT NULL,
  `user_avatarid` int(11) NOT NULL,
  PRIMARY KEY (`comment_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE IF NOT EXISTS `contacts` (
  `contact_userid` int(11) NOT NULL,
  `contact_usermail` text COLLATE utf8_bin NOT NULL,
  `contact_id` int(11) NOT NULL,
  `contact_invited` tinyint(1) NOT NULL,
  PRIMARY KEY (`contact_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `favourites`
--

CREATE TABLE IF NOT EXISTS `favourites` (
  `favourite_id` int(11) NOT NULL,
  `favourite_userid` int(11) NOT NULL,
  `favourite_typeid` int(11) NOT NULL,
  `favourite_itemid` int(11) NOT NULL,
  PRIMARY KEY (`favourite_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `happeningparticipants`
--

CREATE TABLE IF NOT EXISTS `happeningparticipants` (
  `participation_happeningid` int(11) NOT NULL DEFAULT '0',
  `participation_userid` int(11) NOT NULL DEFAULT '0',
  `participation_certainty` int(11) NOT NULL DEFAULT '0',
  `participation_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`participation_happeningid`,`participation_userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `happenings`
--

CREATE TABLE IF NOT EXISTS `happenings` (
  `happening_id` int(11) NOT NULL AUTO_INCREMENT,
  `happening_title` text COLLATE utf8_bin,
  `happening_placeid` int(11) NOT NULL DEFAULT '0',
  `happening_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`happening_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE IF NOT EXISTS `images` (
  `image_id` int(11) NOT NULL AUTO_INCREMENT,
  `image_userid` int(11) NOT NULL DEFAULT '0',
  `image_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `image_userip` int(11) NOT NULL DEFAULT '0',
  `image_name` varchar(255) COLLATE utf8_bin DEFAULT '',
  `image_width` int(11) NOT NULL DEFAULT '0',
  `image_height` int(11) NOT NULL DEFAULT '0',
  `image_size` int(11) NOT NULL DEFAULT '0',
  `image_delid` int(11) NOT NULL DEFAULT '0',
  `image_albumid` int(11) NOT NULL DEFAULT '0',
  `image_numcomments` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`image_id`),
  KEY `image_userid` (`image_userid`,`image_delid`),
  KEY `LATEST` (`image_albumid`,`image_delid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `imagesfrontpage`
--

CREATE TABLE IF NOT EXISTS `imagesfrontpage` (
  `frontpage_imageid` int(11) NOT NULL DEFAULT '0',
  `frontpage_userid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`frontpage_imageid`),
  KEY `BYUSER` (`frontpage_userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `imagetags`
--

CREATE TABLE IF NOT EXISTS `imagetags` (
  `tag_id` int(11) NOT NULL AUTO_INCREMENT,
  `tag_imageid` int(11) NOT NULL DEFAULT '0',
  `tag_personid` int(11) NOT NULL DEFAULT '0',
  `tag_ownerid` int(11) NOT NULL DEFAULT '0',
  `tag_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `tag_left` int(11) NOT NULL DEFAULT '0',
  `tag_top` int(11) NOT NULL DEFAULT '0',
  `tag_width` int(11) NOT NULL DEFAULT '0',
  `tag_height` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`tag_id`),
  UNIQUE KEY `PERSON_IN_IMAGE` (`tag_imageid`,`tag_personid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `institutions`
--

CREATE TABLE IF NOT EXISTS `institutions` (
  `institution_id` int(11) NOT NULL AUTO_INCREMENT,
  `institution_name` varchar(511) COLLATE utf8_bin DEFAULT '',
  `institution_placeid` int(11) NOT NULL DEFAULT '0',
  `institution_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `institution_avatarid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`institution_id`),
  KEY `LOCATION` (`institution_placeid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `journals`
--

CREATE TABLE IF NOT EXISTS `journals` (
  `journal_id` int(11) NOT NULL AUTO_INCREMENT,
  `journal_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `journal_numcomments` int(11) NOT NULL DEFAULT '0',
  `journal_title` varchar(512) COLLATE utf8_bin DEFAULT '',
  `journal_url` varchar(512) COLLATE utf8_bin DEFAULT '',
  `journal_bulkid` int(11) NOT NULL DEFAULT '0',
  `journal_userid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`journal_id`),
  KEY `AUTHOR` (`journal_userid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `journalsfrontpage`
--

CREATE TABLE IF NOT EXISTS `journalsfrontpage` (
  `frontpage_journalid` int(11) NOT NULL AUTO_INCREMENT,
  `frontpage_userid` int(11) NOT NULL,
  PRIMARY KEY (`frontpage_journalid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `journalstickies`
--

CREATE TABLE IF NOT EXISTS `journalstickies` (
  `journal_id` int(11) NOT NULL,
  `journal_userid` int(11) NOT NULL,
  PRIMARY KEY (`journal_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `lastactive`
--

CREATE TABLE IF NOT EXISTS `lastactive` (
  `lastactive_userid` int(11) NOT NULL DEFAULT '0',
  `lastactive_updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`lastactive_userid`),
  KEY `online` (`lastactive_updated`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `loginattempts`
--

CREATE TABLE IF NOT EXISTS `loginattempts` (
  `login_username` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `login_ip` int(11) NOT NULL,
  `login_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `login_success` tinyint(1) NOT NULL,
  PRIMARY KEY (`login_ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `moods`
--

CREATE TABLE IF NOT EXISTS `moods` (
  `mood_id` int(11) NOT NULL AUTO_INCREMENT,
  `mood_label` text COLLATE utf8_bin,
  `mood_icon` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`mood_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `pageviews`
--

CREATE TABLE IF NOT EXISTS `pageviews` (
  `pageview_sessionid` int(11) NOT NULL,
  `pageview_element` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`pageview_sessionid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `passwordrequests`
--

CREATE TABLE IF NOT EXISTS `passwordrequests` (
  `request_id` int(11) NOT NULL AUTO_INCREMENT,
  `request_userid` int(11) NOT NULL DEFAULT '0',
  `request_hash` char(32) COLLATE utf8_bin DEFAULT '',
  `request_used` int(11) NOT NULL DEFAULT '0',
  `request_host` int(11) unsigned NOT NULL DEFAULT '0',
  `request_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`request_id`),
  KEY `user` (`request_userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `polls`
--

CREATE TABLE IF NOT EXISTS `polls` (
  `poll_id` int(11) NOT NULL AUTO_INCREMENT,
  `poll_userid` int(11) NOT NULL,
  `poll_delid` int(11) NOT NULL,
  `poll_created` datetime NOT NULL,
  `poll_title` varchar(512) COLLATE utf8_bin NOT NULL,
  `poll_url` varchar(512) COLLATE utf8_bin NOT NULL,
  `poll_question` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`poll_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `pollsfrontpage`
--

CREATE TABLE IF NOT EXISTS `pollsfrontpage` (
  `frontpage_pollid` int(11) NOT NULL,
  `frontpage_userid` int(11) NOT NULL,
  PRIMARY KEY (`frontpage_pollid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `sequences`
--

CREATE TABLE IF NOT EXISTS `sequences` (
  `sequence_key` int(11) NOT NULL AUTO_INCREMENT,
  `sequence_value` int(11) NOT NULL,
  `sequence_frontpageimagecomments` int(11) NOT NULL,
  `sequence_shout` int(11) NOT NULL,
  `sequence_comment` int(11) NOT NULL,
  `sequence_journal` int(11) NOT NULL,
  `sequence_poll` int(11) NOT NULL,
  PRIMARY KEY (`sequence_key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `shoutbox`
--

CREATE TABLE IF NOT EXISTS `shoutbox` (
  `shout_id` int(11) NOT NULL AUTO_INCREMENT,
  `shout_userid` int(11) NOT NULL,
  `shout_channelid` int(11) NOT NULL,
  `shout_text` text COLLATE utf8_bin NOT NULL,
  `shout_created` datetime NOT NULL,
  `shout_delid` int(11) NOT NULL,
  PRIMARY KEY (`shout_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE IF NOT EXISTS `tags` (
  `tag_id` int(11) NOT NULL AUTO_INCREMENT,
  `tag_userid` int(11) NOT NULL DEFAULT '0',
  `tag_typeid` int(11) NOT NULL DEFAULT '0',
  `tag_text` varchar(256) COLLATE utf8_bin DEFAULT '',
  `tag_nextid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`tag_id`),
  KEY `tag_userid` (`tag_userid`),
  KEY `TAG_SUGGESTIONS` (`tag_text`,`tag_typeid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `usercounts`
--

CREATE TABLE IF NOT EXISTS `usercounts` (
  `count_userid` int(11) NOT NULL DEFAULT '0',
  `count_images` int(11) NOT NULL DEFAULT '0',
  `count_polls` int(11) NOT NULL DEFAULT '0',
  `count_journals` int(11) NOT NULL DEFAULT '0',
  `count_albums` int(11) NOT NULL DEFAULT '0',
  `count_comments` int(11) NOT NULL DEFAULT '0',
  `count_relations` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`count_userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `userprofiles`
--

CREATE TABLE IF NOT EXISTS `userprofiles` (
  `profile_userid` int(11) NOT NULL DEFAULT '0',
  `profile_placeid` int(11) NOT NULL DEFAULT '0',
  `profile_dob` date NOT NULL,
  `profile_slogan` varchar(511) COLLATE utf8_bin DEFAULT '',
  `profile_uniid` int(11) NOT NULL DEFAULT '0',
  `profile_education` enum('-','elementary','gymnasium','TEE','lyceum','TEI','university','finished') COLLATE utf8_bin DEFAULT '-',
  `profile_educationyear` int(11) NOT NULL DEFAULT '0',
  `profile_sexualorientation` enum('-','straight','bi','gay') COLLATE utf8_bin DEFAULT '-',
  `profile_religion` enum('-','christian','muslim','atheist','agnostic','nothing') COLLATE utf8_bin DEFAULT '-',
  `profile_politics` enum('-','right','left','center','radical left','radical right','center left','center right','nothing') COLLATE utf8_bin DEFAULT '-',
  `profile_aboutme` text COLLATE utf8_bin,
  `profile_moodid` int(11) NOT NULL DEFAULT '0',
  `profile_eyecolor` enum('-','black','brown','green','blue','grey') COLLATE utf8_bin DEFAULT '-',
  `profile_haircolor` enum('-','black','brown','red','blond','highlights','dark','grey','skinhead') COLLATE utf8_bin DEFAULT '-',
  `profile_height` int(11) NOT NULL DEFAULT '0',
  `profile_weight` int(11) NOT NULL DEFAULT '0',
  `profile_smoker` enum('-','yes','no','socially') COLLATE utf8_bin DEFAULT '-',
  `profile_drinker` enum('-','yes','no','socially') COLLATE utf8_bin DEFAULT '-',
  `profile_favquote` text COLLATE utf8_bin,
  `profile_skype` varchar(32) COLLATE utf8_bin DEFAULT '',
  `profile_msn` varchar(32) COLLATE utf8_bin DEFAULT '',
  `profile_gtalk` varchar(32) COLLATE utf8_bin DEFAULT '',
  `profile_yim` varchar(32) COLLATE utf8_bin DEFAULT '',
  `profile_homepage` varchar(32) COLLATE utf8_bin DEFAULT '',
  `profile_firstname` varchar(32) COLLATE utf8_bin DEFAULT '',
  `profile_lastname` varchar(32) COLLATE utf8_bin DEFAULT '',
  `profile_mobile` varchar(32) COLLATE utf8_bin DEFAULT '',
  `profile_updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`profile_userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` char(32) COLLATE utf8_bin DEFAULT '',
  `user_password` char(32) COLLATE utf8_bin DEFAULT '',
  `user_authtoken` char(32) COLLATE utf8_bin DEFAULT '',
  `user_registerhost` int(11) unsigned NOT NULL DEFAULT '0',
  `user_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_rights` int(11) NOT NULL DEFAULT '0',
  `user_icon` int(11) NOT NULL DEFAULT '0',
  `user_email` varchar(32) COLLATE utf8_bin DEFAULT '',
  `user_emailverified` enum('no','yes') COLLATE utf8_bin DEFAULT 'no',
  `user_subdomain` char(32) COLLATE utf8_bin DEFAULT '',
  `user_gender` enum('-','m','f') COLLATE utf8_bin DEFAULT '-',
  `user_lastlogin` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_egoalbumid` int(11) NOT NULL DEFAULT '0',
  `user_deleted` tinyint(4) NOT NULL,
  `user_avatarid` int(11) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `name` (`user_name`),
  UNIQUE KEY `subdomain` (`user_subdomain`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `usersettings`
--

CREATE TABLE IF NOT EXISTS `usersettings` (
  `setting_userid` int(11) NOT NULL DEFAULT '0',
  `setting_emailprofile` enum('yes','no') COLLATE utf8_bin DEFAULT 'yes',
  `setting_emailphotos` enum('yes','no') COLLATE utf8_bin DEFAULT 'yes',
  `setting_emailjournals` enum('yes','no') COLLATE utf8_bin DEFAULT 'yes',
  `setting_emailreplies` enum('yes','no') COLLATE utf8_bin DEFAULT 'yes',
  `setting_emailfriends` enum('yes','no') COLLATE utf8_bin DEFAULT 'yes',
  `setting_notifyprofile` enum('yes','no') COLLATE utf8_bin DEFAULT 'yes',
  `setting_notifyphotos` enum('yes','no') COLLATE utf8_bin DEFAULT 'yes',
  `setting_notifyjournals` enum('yes','no') COLLATE utf8_bin DEFAULT 'yes',
  `setting_notifyreplies` enum('yes','no') COLLATE utf8_bin DEFAULT 'yes',
  `setting_notifyfriends` enum('yes','no') COLLATE utf8_bin DEFAULT 'yes',
  PRIMARY KEY (`setting_userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
