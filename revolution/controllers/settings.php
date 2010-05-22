<?php
    class ControllerSettings {
        public static function View() {
            $usersettings = array();
            if ( isset( $_SESSION[ 'user' ] ) ) {
                $userid = $_SESSION[ 'user' ][ 'id' ];
                clude( "models/usersettings.php" );
                $usersettings = Usersettings::Get( $userid );
            }
            else {
                //you are not logged in
            }      
            include 'views/settings/view.php';
        }
        public static function Update( $email, $notify ) {
            if ( isset( $_SESSION[ 'user' ] ) ) {
                $userid = $_SESSION[ 'user' ][ 'id' ];
                clude( "models/usersettings.php" );
            }
            else {
                return false;
            }

            if ( ( $email !== "yes" AND $email !== "no" ) 
                OR ( $notify !== "yes" AND $notify !== "no" )  ) {
                return false;
            }
            
            $pref = array();
            $pref[ 'emailprofilecomment'] = $pref[ 'emailphotocomment'] = $pref[ 'emailphototag'] = $pref[ 'emailjournalcomment'] = $pref[ 'emailpollcomment'] = $pref[ 'emailreply'] = $pref[ 'emailfriendaddition'] = $pref[ 'emailfriendjournal'] = $pref[ 'emailfriendpoll'] = $pref[ 'emailfriendphoto'] = $pref[ 'emailfavourite'] = $pref[ 'emailbirthday'] = $email;
            $pref[ 'notifyprofilecomment'] = $pref[ 'notifyphotocomment'] = $pref[ 'notifyphototag'] = $pref[ 'notifyjournalcomment'] = $pref[ 'notifypollcomment'] = $pref[ 'notifyreply'] = $pref[ 'notifyfriendaddition'] = $pref[ 'notifyfriendjournal'] = $pref[ 'notifyfriendphoto'] = $pref[ 'notifyfriendpoll'] = $pref[ 'notifyfavourite'] = $pref[ 'notifybirthday'] = $notify;
            return Usersettings::Set( $userid, $pref );
        }
       
    }
?>
