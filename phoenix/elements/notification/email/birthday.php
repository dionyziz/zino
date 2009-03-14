<?php
    /// Content-type: text/plain ///
    class ElementNotificationBirthday extends Element {
        public function Render( Notification $notification ) {
            global $rabbit_settings;
        
            $image = New Image( $notification->Item->Imageid );
        
            $from = $notification->FromUser;
 
            w_assert( $from instanceof User );
            w_assert( $from->Exists() );
 
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
            ?> ένα χρόνια πολλά στο Zino προφίλ:
<?php
            echo $from->Subdomain . '.' . $rabbit_settings[ 'webaddress' ];
            
            Element( 'email/footer' );
            
            return $subject;
        }
    }
?>
