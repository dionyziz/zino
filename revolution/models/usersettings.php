<?php
    class Usersettings {
        public function Get( $userid ) {
            clude( 'models/db.php' );
             $res = db( 'SELECT
                            `setting_userid` AS userid, `setting_emailprofilecomment` AS emailprofilecomment, `setting_emailphotocomment` AS emailphotocomment, `setting_emailphototag` AS emailphototag, `setting_emailjournalcomment` AS emailjournalcomment, `setting_emailpollcomment` AS emailpollcomment,	`setting_emailreply` AS emailreply,	`setting_emailfriendaddition` AS emailfriendaddition, `setting_emailfriendjournal` AS emailfriendjournal, `setting_emailfriendpoll` AS emailfriendpoll, `setting_emailfriendphoto` AS emailfriendphoto,	`setting_emailfavourite` AS emailfavourite, `setting_emailbirthday` AS emailbirthday, `setting_notifyprofilecomment` AS notifyprofilecomment, `setting_notifyphotocomment` AS notifyphotocomment, `setting_notifyphototag` AS notifyphototag, `setting_notifyjournalcomment` AS notifyjournalcomment, `setting_notifypollcomment` AS notifypollcomment, `setting_notifyreply` AS notifyreply, `setting_notifyfriendaddition` AS notifyfriendaddition,	`setting_notifyfriendjournal` AS notifyfriendjournal, `setting_notifyfriendphoto` AS notifyfriendphoto, `setting_notifyfriendpoll` AS notifyfriendpoll, `setting_notifyfavourite` AS notifyfavourite, `setting_notifybirthday` AS notifybirthday
                        FROM `usersettings`
                        WHERE
                            `setting_userid` = :userid
                        LIMIT 1', compact( 'userid' ) );
            
            if (  mysql_num_rows( $res ) == 0 ) {
                return false;
            }

           return mysql_fetch_array( $res );
        }
        public function Set( $userid, $pref ) {
            clude( 'models/db.php' );
            if ( !is_array( $pref ) ) {
                return false;
            }
            
            $res = db( 'UPDATE
                    `usersettings`
                    SET
                        `setting_emailprofilecomment` = :emailprofilecomment,
                        `setting_emailphotocomment` = :emailphotocomment,
                        `setting_emailphototag` = :emailphototag,
                        `setting_emailjournalcomment` = :emailjournalcomment,
                        `setting_emailpollcomment` = :emailpollcomment,
                        `setting_emailreply` = :emailreply,
                        `setting_emailfriendaddition` = :emailfriendaddition,
                        `setting_emailfriendjournal` = :emailfriendjournal,
                        `setting_emailfriendpoll` = :emailfriendpoll,
                        `setting_emailfriendphoto` = :emailfriendphoto,
                        `setting_emailfavourite` = :emailfavourite,
                        `setting_emailbirthday` = :emailbirthday,
                        `setting_notifyprofilecomment` = :notifyprofilecomment,
                        `setting_notifyphotocomment` = :notifyphotocomment,
                        `setting_notifyphototag` = :notifyphototag,
                        `setting_notifyjournalcomment` = :notifyjournalcomment,
                        `setting_notifypollcomment` = :notifypollcomment,
                        `setting_notifyreply` = :notifyreply,
                        `setting_notifyfriendaddition` = :notifyfriendaddition,
                        `setting_notifyfriendjournal` = :notifyfriendjournal,
                        `setting_notifyfriendphoto` = :notifyfriendphoto,
                        `setting_notifyfriendpoll` = :notifyfriendpoll,
                        `setting_notifyfavourite` = :notifyfavourite,
                        `setting_notifybirthday` = :notifybirthday
                    WHERE
                        `setting_userid` = :userid
                    LIMIT 1', array( 'userid' => $userid, 
                            'emailprofilecomment' => $pref[ 'emailprofilecomment'],
                            'emailphotocomment' => $pref[ 'emailphotocomment'],
                            'emailphototag' => $pref[ 'emailphototag'],
                            'emailjournalcomment' => $pref[ 'emailjournalcomment'],
                            'emailpollcomment' => $pref[ 'emailpollcomment'],
                            'emailreply' => $pref[ 'emailreply'],
                            'emailfriendaddition' => $pref[ 'emailfriendaddition'],
                            'emailfriendjournal' => $pref[ 'emailfriendjournal'],
                            'emailfriendpoll' => $pref[ 'emailfriendpoll'],
                            'emailfriendphoto' => $pref[ 'emailfriendphoto'],
                            'emailfavourite' => $pref[ 'emailfavourite'],
                            'emailbirthday' => $pref[ 'emailbirthday'],
                            'notifyprofilecomment' => $pref[ 'notifyprofilecomment'],
                            'notifyphotocomment' => $pref[ 'notifyphotocomment'],
                            'notifyphototag' => $pref[ 'notifyphototag'],
                            'notifyjournalcomment' => $pref[ 'notifyjournalcomment'],
                            'notifypollcomment' => $pref[ 'notifypollcomment'],
                            'notifyreply' => $pref[ 'notifyreply'],
                            'notifyfriendaddition' => $pref[ 'notifyfriendaddition'],
                            'notifyfriendjournal' => $pref[ 'notifyfriendjournal'],
                            'notifyfriendphoto' => $pref[ 'notifyfriendphoto'],
                            'notifyfriendpoll' => $pref[ 'notifyfriendpoll'],
                            'notifyfavourite' => $pref[ 'notifyfavourite'],
                            'notifybirthday' => $pref[ 'notifybirthday'] ) );
            return true;
        }
    }
?>
