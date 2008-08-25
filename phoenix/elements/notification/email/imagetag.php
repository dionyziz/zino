<?php
    /// Content-type: text/plain ///
    class ElementNotificationEmailImagetag extends Element {
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
            ?> σε αναγνώρισε σε μια εικόνα<?php
            if ( !empty( $image->Name ) ) {
                ?>, την "<?php
                echo $image->Name;
                ?>"<?php
            }
            $subject = ob_get_clean();
            echo $subject;
            
            ?>.
            
Για να δεις σε ποια εικόνα σε αναγνώρισε <?php
            if ( $from->Gender == 'f' ) {
                ?>η<?php
            }
            else {
                ?>ο<?php
            }
            ?> <?php
            echo $from->Name;
            ?> κάνε κλικ στον παρακάτω σύνδεσμο:
            
<?php
            echo $rabbit_settings[ 'webaddress' ];
            ?>/?p=photo&amp;id=<?php
            echo $image->Id;
            
            Element( 'email/footer' );
            
            return $subject;
        }
    }
?>