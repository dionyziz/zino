<?php
    class ControllerReport {
        public static function Create( $id = 0, $opt = 0, $details, $item ) {
            clude( 'models/db.php' );
            clude( 'models/user.php' ); 
            $item = mysql_real_escape_string( $item );
            clude( 'models/report.php' ); 
            $myname = isset( $_SESSION[ 'user' ] ) ? $_SESSION[ 'user' ][ 'name' ] : 0;
            if ( $myname ) {
                if ( $item == "user" ) {
                    $id = (int)$id;
                    $user = User::Item( $id );
                    $user = $user[ 'name' ];
                    Report::Create( $id );
                    $reports = Report::Listing( $id );
                    return mail( "themicp@gmail.com", "Αναφορά κακής χρήσης", "Ο χρήστης $myname αναφέρει τον χρήστη $user για: $opt.\nΠερισσότερες πληροφορίες:\n$details\n\nΟ χρήστης $user αναφέρεται για " . $reports . "η φορά.\n\nZino Reports", "From: Zino Reports themis@kamibu.com" );
                }
                if ( $item == "photo" ){
                    return mail( "themicp@gmail.com", "Αναφορά φωτογραφίας", "Ο χρήστης $myname αναφέρει την φωτογραφία:\n$details\n\nZino Reports", "From: Zino Reports themis@kamibu.com" );
                }
            }
        }
        public static function Listing( $userid = 0 ) {
            $userid = (int)$userid;
            return Report::Listing( $userid );
        }
    }
?>
