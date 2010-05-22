<?php
    class ControllerSettings {
        public static function View( $userid ) {
            $userid = ( int )$userid;
            clude( "models/usersettings.php" );
            $usersettings = Usersettings::Get( $userid );
            clude( "views/settings/view.php" );
        }
        public static function Listing() {
            
        }
       
    }
?>
