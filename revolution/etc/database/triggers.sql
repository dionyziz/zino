SET NAMES UTF8;

DROP TRIGGER IF EXISTS commentinsert;
DROP TRIGGER IF EXISTS commentdelete;
DROP TRIGGER IF EXISTS imageinsert;
DROP TRIGGER IF EXISTS imageupdate;
DROP TRIGGER IF EXISTS albuminsert;
DROP TRIGGER IF EXISTS albumupdate;
DROP TRIGGER IF EXISTS albumdelete;
DROP TRIGGER IF EXISTS pollinsert;
DROP TRIGGER IF EXISTS polldelete;
DROP TRIGGER IF EXISTS voteinsert;
DROP TRIGGER IF EXISTS votedelete;
DROP TRIGGER IF EXISTS journalinsert;
DROP TRIGGER IF EXISTS journaldelete;
DROP TRIGGER IF EXISTS shoutinsert;
DROP TRIGGER IF EXISTS shoutdelete;
DROP TRIGGER IF EXISTS relationinsert;
DROP TRIGGER IF EXISTS relationdelete;
DROP TRIGGER IF EXISTS commentinsert;
DROP TRIGGER IF EXISTS commentinsert;
DROP TRIGGER IF EXISTS favouriteinsert;
DROP TRIGGER IF EXISTS favouritedelete;
DROP TRIGGER IF EXISTS answerinsert;
DROP TRIGGER IF EXISTS answerdelete;
DROP TRIGGER IF EXISTS questiondelete;
DROP TRIGGER IF EXISTS songinsert;
DROP TRIGGER IF EXISTS songdelete;
DROP TRIGGER IF EXISTS statusinsert;
DROP TRIGGER IF EXISTS statusdelete;
DROP TRIGGER IF EXISTS beforebirth;
DROP TRIGGER IF EXISTS userbirth;
DROP TRIGGER IF EXISTS userdeath;
DROP TRIGGER IF EXISTS userupdate;

delimiter |

CREATE TRIGGER commentinsert AFTER INSERT ON `comments`
   FOR EACH ROW BEGIN
   		DECLARE activitytext, activityurl VARCHAR( 512 );
        UPDATE `usercounts` SET `count_comments` = `count_comments` + 1 WHERE `count_userid` = NEW.`comment_userid` LIMIT 1;
        CASE NEW.`comment_typeid`
            WHEN 1 THEN BEGIN
                UPDATE `polls` SET `poll_numcomments` = `poll_numcomments` + 1 WHERE `poll_id`=NEW.`comment_itemid` LIMIT 1;
                SELECT `poll_question`, `poll_url` FROM `polls` WHERE `poll_id`=NEW.`comment_itemid` LIMIT 1 INTO activitytext, activityurl;
            END;
            WHEN 2 THEN BEGIN
                UPDATE `images` SET `image_numcomments` = `image_numcomments` + 1 WHERE `image_id`=NEW.`comment_itemid` LIMIT 1;
                SELECT `image_name`, '' FROM `images` WHERE `image_id` = NEW.`comment_itemid` LIMIT 1 INTO activitytext, activityurl;
            END;
            WHEN 3 THEN BEGIN
                UPDATE `userprofiles` SET `profile_numcomments` = `profile_numcomments` + 1 WHERE `profile_userid`=NEW.`comment_itemid` LIMIT 1;
                SELECT `user_name`, `user_subdomain` FROM `users` WHERE `user_id` = NEW.`comment_itemid` LIMIT 1 INTO activitytext, activityurl;
            END;
            WHEN 4 THEN BEGIN
                UPDATE `journals` SET `journal_numcomments` = `journal_numcomments` + 1 WHERE `journal_id`=NEW.`comment_itemid` LIMIT 1;
                SELECT `journal_title`, `journal_url` FROM `journals` WHERE `journal_id` = NEW.`comment_itemid` LIMIT 1 INTO activitytext, activityurl;
            END;
            WHEN 7 THEN BEGIN
                UPDATE `schools` SET `school_numcomments` = `school_numcomments` + 1 WHERE `school_id`=NEW.`comment_itemid` LIMIT 1;
                SELECT `school_name`, '' FROM `schools` WHERE `school_id` = NEW.`comment_itemid` LIMIT 1 INTO activitytext, activityurl;
            END;
        END CASE;
        INSERT INTO `activities` ( `activity_index`, `activity_userid`, `activity_typeid`, `activity_refid`, `activity_itemid`, `activity_itemtype`, `activity_bulkid`, `activity_text`, `activity_url`, `activity_created` ) VALUES( RAND()*100, NEW.`comment_userid`, 1, NEW.`comment_id`, NEW.`comment_itemid`, NEW.`comment_typeid`, NEW.`comment_bulkid`, activitytext, activityurl, NOW() ) ON DUPLICATE KEY UPDATE
        	`activity_userid` = NEW.`comment_userid`,
        	`activity_typeid` = 1,
        	`activity_refid` = NEW.`comment_id`,
        	`activity_itemid` = NEW.`comment_itemid`,
        	`activity_itemtype` = NEW.`comment_typeid`,
        	`activity_bulkid` = NEW.`comment_bulkid`,
        	`activity_text` = activitytext,
        	`activity_url` = activityurl,
        	`activity_created` = NOW();
   END;
|

CREATE TRIGGER commentdelete AFTER DELETE ON `comments`
   FOR EACH ROW BEGIN
        UPDATE `usercounts` SET `count_comments` = `count_comments` - 1 WHERE `count_userid` = OLD.`comment_userid` LIMIT 1;
        CASE OLD.`comment_typeid`
            WHEN 1 THEN UPDATE `polls` SET `poll_numcomments` = `poll_numcomments` - 1 WHERE `poll_id`=OLD.`comment_itemid` LIMIT 1;
            WHEN 2 THEN BEGIN
                UPDATE `images` SET `image_numcomments` = `image_numcomments` - 1 WHERE `image_id`=OLD.`comment_itemid` LIMIT 1;
                UPDATE `albums`, `images` SET `album_numcomments` = `album_numcomments` - 1 WHERE `image_albumid`=`album_id` AND `image_id`=OLD.`comment_itemid`;
            END;
            WHEN 3 THEN UPDATE `userprofiles` SET `profile_numcomments` = `profile_numcomments` - 1 WHERE `profile_userid`=OLD.`comment_itemid` LIMIT 1;
            WHEN 4 THEN UPDATE `journals` SET `journal_numcomments` = `journal_numcomments` - 1 WHERE `journal_id`=OLD.`comment_itemid` LIMIT 1;
            WHEN 7 THEN UPDATE `schools` SET `school_numcomments` = `school_numcomments` - 1 WHERE `school_id`=OLD.`comment_itemid` LIMIT 1;
        END CASE;
        DELETE FROM `bulk` WHERE `bulk_id`=OLD.`comment_bulkid` LIMIT 1;
        DELETE FROM `activities` WHERE `activity_userid` = OLD.`comment_userid` AND `activity_typeid` = 1 AND `activity_itemid` = OLD.`comment_itemid` LIMIT 1;
   END;
|

CREATE TRIGGER imageinsert AFTER INSERT ON `images`
   FOR EACH ROW BEGIN
        UPDATE `usercounts` SET `count_images` = `count_images` + 1 WHERE `count_userid` = NEW.`image_userid` LIMIT 1;
        UPDATE `albums` SET `album_numphotos` = `album_numphotos` + 1 WHERE `album_id` = NEW.`image_albumid` LIMIT 1;
        INSERT INTO `activities` ( `activity_index`, `activity_userid`, `activity_typeid`, `activity_refid`, `activity_itemid`, `activity_itemtype`, `activity_bulkid`, `activity_text`, `activity_url`, `activity_created` ) VALUES( RAND()*100, NEW.`image_userid`, 7, NEW.`image_id`, 0, 2, 0, NEW.`image_name`, '', NOW() ) ON DUPLICATE KEY UPDATE
        	`activity_userid` = NEW.`image_userid`,
        	`activity_typeid` = 7,
        	`activity_refid` = NEW.`image_id`,
        	`activity_itemid` = 0,
        	`activity_itemtype` = 2,
        	`activity_bulkid` = 0,
        	`activity_text` = NEW.`image_name`,
        	`activity_url` = '',
        	`activity_created` = NOW();
   END;
|

CREATE TRIGGER imageupdate AFTER UPDATE ON `images`
    FOR EACH ROW BEGIN
   		DECLARE albummainid, albumfirstid INT(11);
        IF OLD.`image_delid` = 0 AND NEW.`image_delid` = 1 THEN
            UPDATE `usercounts` SET `count_images` = `count_images` - 1 WHERE `count_userid` = OLD.`image_userid` LIMIT 1;
            UPDATE `albums` SET `albuιιιιιm_numphotos` = `album_numphotos` - 1 WHERE `album_id` = OLD.`image_albumid` LIMIT 1;
            DELETE FROM `activities` WHERE `activity_userid` = OLD.`image_userid` AND `activity_typeid` = 7 AND `activity_itemid` = OLD.`image_id` LIMIT 1;

            SELECT 
                `album_mainimageid`, `photo_id` 
            FROM 
                `albums` 
                LEFT JOIN `images` ON
                    `image_albumid` = `album_id` AND
                    `image_delid` = 0
            WHERE 
                `album_id` = OLD.`image_albumid` 
            ORDER BY
                `photo_id` ASC
            LIMIT 1
            INTO albummainid, albumfirstid;

            IF albummainid = OLD.`image_id` THEN
                -- IF albumfirstid IS NULL THEN -- is this necessary?
                --    albumfirstid = 0
                -- END IF;
                UPDATE `albums` SET `album_mainimageid` = albumfirstid WHERE `album_id` = OLD.`image_albumid`;
            END IF;

            /*
            DELETE FROM `comments` WHERE `comment_itemid` = OLD.`image_id` AND `comment_typeid` = 2 LIMIT 1;
            DELETE FROM `favourites` WHERE `favourite_itemid` = OLD.`image_id` AND `favourite_typeid` = 2 LIMIT 1;
            */
        END IF;
        IF OLD.`image_numcomments` <> NEW.`image_numcomments` THEN
            UPDATE `albums` SET `album_numcomments` = `album_numcomments` + (NEW.`image_numcomments` - OLD.`image_numcomments`) WHERE `album_id`=OLD.`image_albumid` LIMIT 1;
        END IF;
		IF OLD.`image_id` <> NEW.`image_id` OR OLD.`image_userid` <> NEW.`image_userid` OR OLD.`image_name` <> NEW.`image_name` THEN
			UPDATE 
				`activities` 
			SET 
				`activity_itemid` = NEW.`image_id`,
				`activity_userid` = NEW.`image_userid`,
				`activity_text` = NEW.`image_name`
			WHERE
				`activity_userid` = OLD.`image_userid` AND
				`activity_itemid` = OLD.`image_id` AND
				`activity_typeid` = 7 AND
				`activity_itemtype` = 2
			LIMIT 1;
		END IF;
    END;
|

CREATE TRIGGER albuminsert AFTER INSERT ON `albums`
    FOR EACH ROW BEGIN
		IF NEW.`album_ownertype` = 3 AND NEW.`album_ownerid` IS NOT NULL THEN
			UPDATE `usercounts` SET `count_albums` = `count_albums` + 1 WHERE `count_userid` = NEW.`album_ownerid` LIMIT 1;
            INSERT INTO `activities` ( `activity_index`, `activity_userid`, `activity_typeid`, `activity_refid`, `activity_itemid`, `activity_itemtype`, `activity_bulkid`, `activity_text`, `activity_url`, `activity_created` ) VALUES( RAND()*100, NEW.`album_ownerid`, 7, NEW.`album_id`, 0, 9, 0, NEW.`album_name`, NEW.`album_url`, NOW() ) ON DUPLICATE KEY UPDATE
            	`activity_userid` = NEW.`album_ownerid`,
            	`activity_typeid` = 7,
            	`activity_refid` = NEW.`album_id`,
            	`activity_itemid` = 0,
            	`activity_itemtype` = 9,
            	`activity_bulkid` = 0,
            	`activity_text` = NEW.`album_name`,
            	`activity_url` = NEW.`album_url`,
            	`activity_created` = NOW();
		END IF;
    END;
|

CREATE TRIGGER albumupdate AFTER UPDATE ON `albums`
   FOR EACH ROW BEGIN
        DECLARE useregoalbumid INT(11);
        IF OLD.`album_delid` = 0 AND NEW.`album_delid` = 1 THEN
            IF OLD.`album_ownertype` = 3 THEN 
                UPDATE `usercounts` SET `count_albums` = `count_albums` - 1, `count_images` = `count_images` - OLD.`album_numphotos` WHERE `count_userid` = OLD.`album_ownerid` LIMIT 1;
                DELETE FROM `activities` WHERE `activity_userid` = OLD.`album_ownerid` AND `activity_typeid` = 7 AND `activity_itemid` = OLD.`album_id` AND `activity_itemtype` = 9 LIMIT 1;
            END IF;
            /*
            UPDATE `images` SET `image_delid`=1 WHERE `image_albumid` = OLD.`album_id`;
            */
            DELETE FROM `images` WHERE `image_albumid` = OLD.`album_id`;
        END IF;
        IF OLD.`album_mainimageid` <> NEW.`album_mainimageid` THEN
            SELECT `user_egoalbumid` FROM `users` WHERE `user_id` = NEW.`album_ownerid` INTO useregoalbumid;
            IF useregoalbumid = NEW.`album_id` THEN
                UPDATE `users` SET `user_avatarid` = NEW.`album_mainimageid` WHERE `user_id` = OLD.`album_ownerid` LIMIT 1;
            END IF;
        END IF;
   END;
|

CREATE TRIGGER pollinsert AFTER INSERT ON `polls`
    FOR EACH ROW BEGIN
        UPDATE `usercounts` SET `count_polls` = `count_polls` + 1 WHERE `count_userid` = NEW.`poll_userid` LIMIT 1;
        INSERT INTO `activities` ( `activity_index`, `activity_userid`, `activity_typeid`, `activity_refid`, `activity_itemid`, `activity_itemtype`, `activity_bulkid`, `activity_text`, `activity_url`, `activity_created` ) VALUES( RAND()*100, NEW.`poll_userid`, 7, NEW.`poll_id`, 0, 1, 0, NEW.`poll_question`, NEW.`poll_url`, NOW() ) ON DUPLICATE KEY UPDATE
        	`activity_userid` = NEW.`poll_userid`,
        	`activity_typeid` = 7,
        	`activity_refid` = NEW.`poll_id`,
        	`activity_itemid` = 0,
        	`activity_itemtype` = 1,
        	`activity_bulkid` = 0,
        	`activity_text` = NEW.`poll_question`,
        	`activity_url` = NEW.`poll_url`,
        	`activity_created` = NOW();
    END;
|

CREATE TRIGGER polldelete AFTER UPDATE ON `polls`
    FOR EACH ROW BEGIN
        IF OLD.`poll_delid` = 0 AND NEW.`poll_delid` = 1 THEN
            UPDATE `usercounts` SET `count_polls` = `count_polls` - 1 WHERE `count_userid` = OLD.`poll_userid` LIMIT 1;
            DELETE FROM `activities` WHERE `activity_userid` = OLD.`poll_userid` AND `activity_typeid` = 7 AND `activity_itemid` = OLD.`poll_id` AND `activity_itemtype` = 1 LIMIT 1;
            /*
            DELETE FROM `comments` WHERE `comment_itemid` = `image_itemid` AND `comment_typeid` = 1 LIMIT 1;
            */
        END IF;
    END;
|

CREATE TRIGGER voteinsert AFTER INSERT ON `votes`
    FOR EACH ROW BEGIN
        UPDATE `polls` SET `poll_numvotes` = `poll_numvotes` + 1 WHERE `poll_id` = NEW.`vote_pollid` LIMIT 1;
        UPDATE `polloptions` SET `polloption_numvotes` = `polloption_numvotes` + 1 WHERE `polloption_id` = NEW.`vote_optionid` LIMIT 1;
    END;
|

CREATE TRIGGER votedelete AFTER DELETE ON `votes`
    FOR EACH ROW BEGIN
        UPDATE `polls` SET `poll_numvotes` = `poll_numvotes` - 1 WHERE `poll_id` = OLD.`vote_pollid` LIMIT 1;
        UPDATE `polloptions` SET `polloption_numvotes` = `polloption_numvotes` - 1 WHERE `polloption_id` = OLD.`vote_optionid` LIMIT 1;
    END;
|

CREATE TRIGGER journalinsert AFTER INSERT ON `journals`
    FOR EACH ROW BEGIN
        UPDATE `usercounts` SET `count_journals` = `count_journals` + 1 WHERE `count_userid` = NEW.`journal_userid` LIMIT 1;
        INSERT INTO `activities` ( `activity_index`, `activity_userid`, `activity_typeid`, `activity_refid`, `activity_itemid`, `activity_itemtype`, `activity_bulkid`, `activity_text`, `activity_url`, `activity_created` ) VALUES( RAND()*100, NEW.`journal_userid`, 7, NEW.`journal_id`, 0, 4, 0, NEW.`journal_title`, NEW.`journal_url`, NOW() ) ON DUPLICATE KEY UPDATE
        	`activity_userid` = NEW.`journal_userid`,
        	`activity_typeid` = 7,
        	`activity_refid` = NEW.`journal_id`,
        	`activity_itemid` = 0,
        	`activity_itemtype` = 4,
        	`activity_bulkid` = 0,
        	`activity_text` = NEW.`journal_title`,
        	`activity_url` = NEW.`journal_url`,
        	`activity_created` = NOW();
    END;
|

CREATE TRIGGER journaldelete AFTER UPDATE ON `journals`
    FOR EACH ROW BEGIN
        IF OLD.`journal_delid` = 0 AND NEW.`journal_delid` = 1 THEN
            UPDATE `usercounts` SET `count_journals` = `count_journals` - 1 WHERE `count_userid` = OLD.`journal_userid` LIMIT 1;
            DELETE FROM `activities` WHERE `activity_userid` = OLD.`journal_userid` AND `activity_typeid` = 4 AND `activity_itemid` = OLD.`journal_id` AND `activity_itemtype` = 4 LIMIT 1;
            /*
            DELETE FROM `bulk` WHERE `bulk_id` = OLD.`journal_bulkid` LIMIT 1;
            DELETE FROM `comments` WHERE `comment_itemid` = OLD.`journal_id` AND `comment_typeid` = 4 LIMIT 1;
            DELETE FROM `favourites` WHERE `favourite_itemid` = OLD.`journal_id` AND `favourite_typeid` = 4 LIMIT 1;
            */
        END IF;
    END;
|

CREATE TRIGGER shoutinsert AFTER INSERT ON `shoutbox`
    FOR EACH ROW BEGIN
        UPDATE `usercounts` SET `count_shouts` = `count_shouts` + 1 WHERE `count_userid` = NEW.`shout_userid` LIMIT 1;
    END;
|

CREATE TRIGGER shoutdelete AFTER UPDATE ON `shoutbox`
    FOR EACH ROW BEGIN
        UPDATE `usercounts` SET `count_shouts` = `count_shouts` - 1 WHERE `count_userid` = OLD.`shout_userid` LIMIT 1;
        DELETE FROM `bulk` WHERE `bulk_id`=OLD.`shout_bulkid` LIMIT 1;
    END;
|

CREATE TRIGGER relationinsert AFTER INSERT ON `relations`
    FOR EACH ROW BEGIN
   		DECLARE username, userurl, friendname, friendurl VARCHAR( 512 );
        SELECT `user_name`, `user_subdomain` FROM `users` WHERE `user_id` = NEW.`relation_userid` LIMIT 1 INTO username, userurl;
        SELECT `user_name`, `user_subdomain` FROM `users` WHERE `user_id` = NEW.`relation_friendid` LIMIT 1 INTO friendname, friendurl;
        UPDATE `usercounts` SET `count_relations` = `count_relations` + 1 WHERE `count_userid` = NEW.`relation_userid` LIMIT 1;
        INSERT INTO `activities` ( `activity_index`, `activity_userid`, `activity_typeid`, `activity_refid`, `activity_itemid`, `activity_itemtype`, `activity_bulkid`, `activity_text`, `activity_url`, `activity_created` ) VALUES( RAND()*100, NEW.`relation_userid`, 3, NEW.`relation_id`, NEW.`relation_friendid`, 3, 0, friendname, friendurl, NOW() ) ON DUPLICATE KEY UPDATE
        	`activity_userid` = NEW.`relation_userid`,
        	`activity_typeid` = 3,
        	`activity_refid` = NEW.`relation_id`,
        	`activity_itemid` = NEW.`relation_friendid`,
        	`activity_itemtype` = 3,
        	`activity_bulkid` = 0,
        	`activity_text` = friendname,
        	`activity_url` = friendurl,
        	`activity_created` = NOW(); 
        INSERT INTO `activities` ( `activity_index`, `activity_userid`, `activity_typeid`, `activity_refid`, `activity_itemid`, `activity_itemtype`, `activity_bulkid`, `activity_text`, `activity_url`, `activity_created` ) VALUES( RAND()*100, NEW.`relation_friendid`, 4, NEW.`relation_id`, NEW.`relation_userid`, 3, 0, username, userurl, NOW() ) ON DUPLICATE KEY UPDATE
        	`activity_userid` = NEW.`relation_friendid`,
        	`activity_typeid` = 4,
        	`activity_refid` = NEW.`relation_id`,
        	`activity_itemid` = NEW.`relation_userid`,
        	`activity_itemtype` = 3,
        	`activity_bulkid` = 0,
        	`activity_text` = username,
        	`activity_url` = userurl,
        	`activity_created` = NOW(); 
    END;
|

CREATE TRIGGER relationdelete AFTER DELETE ON `relations`
    FOR EACH ROW BEGIN
        UPDATE `usercounts` SET `count_relations` = `count_relations` - 1 WHERE `count_userid` = OLD.`relation_userid` LIMIT 1;
        DELETE FROM `activities` WHERE ( `activity_typeid` = 3 OR `activity_typeid` = 4 ) AND `activity_refid` = OLD.`relation_id` LIMIT 2;
    END;
|

CREATE TRIGGER favouriteinsert AFTER INSERT ON `favourites`
    FOR EACH ROW BEGIN
   		DECLARE activitytext, activityurl VARCHAR( 512 );
        UPDATE `usercounts` SET `count_favourites` = `count_favourites` + 1 WHERE `count_userid` = NEW.`favourite_userid` LIMIT 1;
        CASE NEW.`favourite_typeid`
            WHEN 1 THEN BEGIN
                SELECT `poll_question`, `poll_url` FROM `polls` WHERE `poll_id`=NEW.`favourite_itemid` LIMIT 1 INTO activitytext, activityurl;
            END;
            WHEN 2 THEN BEGIN
                SELECT `image_name`, '' FROM `images` WHERE `image_id` = NEW.`favourite_itemid` LIMIT 1 INTO activitytext, activityurl;
            END;
            WHEN 3 THEN BEGIN
                SELECT `user_name`, `user_subdomain` FROM `users` WHERE `user_id` = NEW.`favourite_itemid` LIMIT 1 INTO activitytext, activityurl;
            END;
            WHEN 4 THEN BEGIN
                SELECT `journal_title`, `journal_url` FROM `journals` WHERE `journal_id` = NEW.`favourite_itemid` LIMIT 1 INTO activitytext, activityurl;
            END;
            WHEN 7 THEN BEGIN
                SELECT `school_name`, '' FROM `schools` WHERE `school_id` = NEW.`favourite_itemid` LIMIT 1 INTO activitytext, activityurl;
            END;
        END CASE;
        INSERT INTO `activities` ( `activity_index`, `activity_userid`, `activity_typeid`, `activity_refid`, `activity_itemid`, `activity_itemtype`, `activity_bulkid`, `activity_text`, `activity_url`, `activity_created` ) VALUES( RAND()*100, NEW.`favourite_userid`, 2, NEW.`favourite_id`, NEW.`favourite_itemid`, NEW.`favourite_typeid`, 0, activitytext, activityurl, NOW() ) ON DUPLICATE KEY UPDATE
        	`activity_userid` = NEW.`favourite_userid`,
        	`activity_typeid` = 2,
        	`activity_refid` = NEW.`favourite_id`,
        	`activity_itemid` = NEW.`favourite_itemid`,
        	`activity_itemtype` = NEW.`favourite_typeid`,
        	`activity_bulkid` = 0,
        	`activity_text` = activitytext,
        	`activity_url` = activityurl,
        	`activity_created` = NOW(); 
    END;
|

CREATE TRIGGER favouritedelete AFTER DELETE ON `favourites`
    FOR EACH ROW BEGIN
        UPDATE `usercounts` SET `count_favourites` = `count_favourites` - 1 WHERE `count_userid` = OLD.`favourite_userid` LIMIT 1;
        DELETE FROM `activities` WHERE `activity_typeid` = 2 AND `activity_refid` = OLD.`favourite_id` LIMIT 1;
    END;
|

CREATE TRIGGER answerinsert AFTER INSERT ON `answers`
    FOR EACH ROW BEGIN
        UPDATE `usercounts` SET `count_answers` = `count_answers` + 1 WHERE `count_userid` = NEW.`answer_userid` LIMIT 1;
    END;
|

CREATE TRIGGER answerdelete AFTER DELETE ON `answers`
    FOR EACH ROW BEGIN
        UPDATE `usercounts` SET `count_answers` = `count_answers` - 1 WHERE `count_userid` = OLD.`answer_userid` LIMIT 1;
    END;
|

CREATE TRIGGER questiondelete AFTER DELETE ON `questions`
    FOR EACH ROW BEGIN
        DELETE FROM `answers` WHERE `answer_questionid`=OLD.`question_id`;
    END;
|

CREATE TRIGGER beforebirth BEFORE INSERT ON `users`
    FOR EACH ROW BEGIN
        INSERT INTO `albums` (`album_ownerid`, `album_ownertype`) VALUES (NULL, 3);
        SET NEW.`user_egoalbumid` = LAST_INSERT_ID();
    END;
|

CREATE TRIGGER songinsert AFTER INSERT ON `song`
    FOR EACH ROW BEGIN
        INSERT INTO `activities` ( `activity_index`, `activity_userid`, `activity_typeid`, `activity_refid`, `activity_itemid`, `activity_itemtype`, `activity_bulkid`, `activity_text`, `activity_url`, `activity_created` ) VALUES( RAND()*100, NEW.`song_userid`, 5, NEW.`song_id`, 0, 10, 0, NEW.`song_title`, '', NOW() ) ON DUPLICATE KEY UPDATE
        	`activity_userid` = NEW.`song_userid`,
        	`activity_typeid` = 5,
        	`activity_refid` = NEW.`song_id`,
        	`activity_itemid` = 0,
        	`activity_itemtype` = 10,
        	`activity_bulkid` = 0,
        	`activity_text` = NEW.`song_title`,
        	`activity_url` = '',
        	`activity_created` = NOW(); 
    END;
|

CREATE TRIGGER songdelete AFTER DELETE ON `song`
    FOR EACH ROW BEGIN
        DELETE FROM `activities` WHERE `activity_typeid` = 5 AND `activity_refid` = OLD.`song_id` LIMIT 1;
    END;
|

CREATE TRIGGER statusinsert AFTER INSERT ON `statusbox`
    FOR EACH ROW BEGIN
        INSERT INTO `activities` ( `activity_index`, `activity_userid`, `activity_typeid`, `activity_refid`, `activity_itemid`, `activity_itemtype`, `activity_bulkid`, `activity_text`, `activity_url`, `activity_created` ) VALUES( RAND()*100, NEW.`statusbox_userid`, 6, NEW.`statusbox_id`, 0, 11, 0, NEW.`statusbox_message`, '', NOW() ) ON DUPLICATE KEY UPDATE
        	`activity_userid` = NEW.`statusbox_userid`,
        	`activity_typeid` = 6,
        	`activity_refid` = NEW.`statusbox_id`,
        	`activity_itemid` = 0,
        	`activity_itemtype` = 11,
        	`activity_bulkid` = 0,
        	`activity_text` = NEW.`statusbox_message`,
        	`activity_url` = '',
        	`activity_created` = NOW();
    END;
|

CREATE TRIGGER statusdelete AFTER DELETE ON `statusbox`
    FOR EACH ROW BEGIN
        DELETE FROM `activities` WHERE `activity_typeid` = 6 AND `activity_refid` = OLD.`statusbox_id` LIMIT 1;
    END;
|

CREATE TRIGGER userbirth AFTER INSERT ON `users`
    FOR EACH ROW BEGIN
        INSERT INTO `usercounts` (`count_userid`, `count_albums`) VALUES (NEW.`user_id`, 1);
        UPDATE `albums` SET `album_ownerid` = NEW.`user_id` WHERE `album_id`=NEW.`user_egoalbumid` LIMIT 1;
        INSERT INTO `pmfolders` (`pmfolder_userid`, `pmfolder_name`, `pmfolder_typeid`) VALUES (NEW.`user_id`, 'inbox', 'inbox');
        INSERT INTO `pmfolders` (`pmfolder_userid`, `pmfolder_name`, `pmfolder_typeid`) VALUES (NEW.`user_id`, 'outbox', 'outbox');
        INSERT INTO `usersettings` (
           `setting_userid`,
           `setting_emailprofilecomment`,
           `setting_emailphotocomment`,
           `setting_emailphototag`,
           `setting_emailjournalcomment`,
           `setting_emailpollcomment`,
           `setting_emailreply`,
           `setting_emailfriendaddition`,
           `setting_emailfriendjournal`,
           `setting_emailfriendpoll`,
           `setting_emailfriendphoto`,
           `setting_emailfavourite`,
           `setting_emailbirthday`,
           `setting_notifyprofilecomment`,
           `setting_notifyphotocomment`,
           `setting_notifyphototag`,
           `setting_notifyjournalcomment`,
           `setting_notifypollcomment`,
           `setting_notifyreply`,
           `setting_notifyfriendaddition`,
           `setting_notifyfriendjournal`,
           `setting_notifyfriendphoto`,
           `setting_notifyfriendpoll`,
           `setting_notifyfavourite`,
           `setting_notifybirthday`
        ) VALUES (
            NEW.`user_id`,
            'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes',
            'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes',
            'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes'
        );
    END;
|

CREATE TRIGGER userdeath AFTER DELETE ON `users`
    FOR EACH ROW BEGIN
        DELETE FROM `usercounts` WHERE `count_userid`=OLD.`user_id`;
        DELETE FROM `userprofiles` WHERE `profile_userid`=OLD.`user_id`;
        DELETE FROM `albums` WHERE `album_ownerid`=OLD.`user_id`;
        DELETE FROM `comments` WHERE `comment_userid`=OLD.`user_id`;
        DELETE FROM `polls` WHERE `poll_userid`=OLD.`user_id`;
        DELETE FROM `journals` WHERE `journal_userid`=OLD.`user_id`;
        DELETE FROM `images` WHERE `image_userid`=OLD.`user_id`;
        DELETE FROM `notifications` WHERE `notify_touserid`=OLD.`user_id` OR `notify_fromuserid`=OLD.`user_id`;
	END;
|

CREATE TRIGGER userupdate AFTER UPDATE ON `users`
    FOR EACH ROW BEGIN
        IF NEW.`user_deleted` = 1 AND OLD.`user_deleted` = 0 THEN
            UPDATE `images` SET `image_delid` = 2 WHERE `image_userid` = NEW.`user_id`;
            UPDATE `polls` SET `journal_delid` = 2 WHERE `poll_userid`=OLD.`user_id`;
            UPDATE `journals` SET `journal_delid` = 2 WHERE `journal_userid`=OLD.`user_id`;
            DELETE FROM `notifications` WHERE `notify_touserid`=OLD.`user_id` OR `notify_fromuserid`=OLD.`user_id`;
        END IF;
    END;
|
