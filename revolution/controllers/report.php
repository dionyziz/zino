<?php
    class ControllerReport {
        public static function Create( $id, $opt, $details ) {
            $id = (int)$id;
            clude( 'models/db.php' );
            clude( 'models/user.php' ); 
            clude( 'models/report.php' ); 
            $user = User::Item( $id );
            $user = $user[ 'name' ];
            $myname = isset( $_SESSION[ 'user' ] ) ? $_SESSION[ 'user' ][ 'name' ] : 0;
            if ( $myname ) {
                Report::Create( $id );
                $reports = Report::Listing( $id );
                return mail( "themicp@gmail.com", "Αναφορά κακής χρήσης", "Ο χρήστης $myname αναφέρει τον χρήστη $user για: $opt.\nΠερισσότερες πληροφορίες:\n$details\n\nΟ χρήστης $user αναφέρεται για " . $reports . "η φορά.\n\nZino Reports", "From: Zino Reports themis@kamibu.com" );
            }
        }
        public static function Listing( $userid = 0 ) {
            $userid = (int)$userid;
            return Report::Listing( $userid );
        }
    }
?>
