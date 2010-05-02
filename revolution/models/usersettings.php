<?php
    class Usersettings {
        public function Get( $userid ) {
            include 'models/db.h';
             $res = db( 'SELECT
                            `setting_userid` AS userid, `setting_emailprofilecomment` AS emailprofilecomment, `setting_emailphotocomment` AS emailphotocomment, `setting_emailphototag` AS emailphototag, `setting_emailjournalcomment` AS emailjournalcomment, `setting_emailpollcomment` AS emailpollcomment,	`setting_emailreply` AS emailreply,	`setting_emailfriendaddition` AS emailfriendaddition, `setting_emailfriendjournal` AS emailfriendjournal, `setting_emailfriendpoll` AS emailfriendpoll, `setting_emailfriendphoto` AS emailfriendphoto,	`setting_emailfavourite` AS emailfavourite, `setting_emailbirthday` AS emailbirthday, `setting_notifyprofilecomment` AS notifyprofilecomment, `setting_notifyphotocomment` AS notifyphotocomment, `setting_notifyphototag` AS notifyphototag, `setting_notifyjournalcomment` AS notifyjournalcomment, `setting_notifypollcomment` AS notifypollcomment, `setting_notifyreply` AS notifyreply, `setting_notifyfriendaddition` AS notifyfriendaddition,	`setting_notifyfriendjournal` AS notifyfriendjournal, `setting_notifyfriendphoto` AS notifyfriendphoto, `setting_notifyfriendpoll` AS notifyfriendpoll, `setting_notifyfavourite` AS notifyfavourite, `setting_notifybirthday` AS notifybirthday
                        FROM `usersettings`
                        WHERE
                            `settings_userid` = :userid
                        LIMIT 1', compact( 'userid' ) );
            
            if (  mysql_num_rows( $res ) == 0 ) {
                return false;
            }

           return mysql_fetch_array( $res );
        }
    }
?>
