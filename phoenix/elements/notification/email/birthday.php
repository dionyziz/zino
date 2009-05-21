<?php
    /// Content-type: text/plain ///
    class ElementNotificationEmailBirthday extends Element {
        public function Render( Notification $notification ) {
            global $rabbit_settings;
        
            $from = $notification->FromUser;
 
            w_assert( $from instanceof User, 'From user is not an object' );
            w_assert( $from->Exists(), 'From user does not exist' );
 
            ob_start();
            if ( $from->Gender == 'f' ) {
                ?>Η<?php
            }
            else {
                ?>Ο<?php
            }
            ?> <?php
            echo $from->Name;
            ?> έχει γενέθλεια!<?php
            $subject = ob_get_clean();
            echo $subject;
            
            ?>.
            
Πες <?php
            if ( $from->Gender == 'f' ) {
                ?>της<?php
            }
            else {
                ?>του<?php
            }
            ?> ένα χρόνια πολλά στο προφίλ <?php
            if ( $from->Gender == 'f' ) {
                ?>της<?php
            }
            else {
                ?>του<?php
            }
            ?> στο Zino: 
<?php
            ob_start();
            Element( 'user/url', $from );
            $url = ob_get_clean();
            echo $url;
            
            Element( 'email/footer' );
            
            return $subject;
        }
    }
?>
