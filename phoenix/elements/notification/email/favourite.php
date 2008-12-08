<?php
    /// Content-type: text/plain ///
    class ElementNotificationEmailFavourite extends Element {
        public function Render( Notification $notification ) {
            global $rabbit_settings;
            global $user;
        
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
            ?> πρόσθεσε στα αγαπημένα <?php
            $subject = ob_get_clean();
            echo $subject;
            $subject .= 'κάτι δικό σου';
            switch ( $notification->Item->Typeid ) {
                case TYPE_IMAGE:
                    $image = $notif->Item->Item;
                    if ( $image->Name != '' ) {
                        ?>την εικόνα σου "<?php
                        echo $image->Name;
                        ?>"<?php
                    }
                    else if ( $image->Album->Id == $image->User->Egoalbumid ) {
                        ?>μια φωτογραφία σου<?php
                    }
                    else {
                        ?>μια εικόνα από το album σου "<?php
                        echo $image->Album->Name;
                        ?>"<?php
                    }
                    break;
                case TYPE_JOURNAL:
                    $journal = $notif->Item->Item;
                    ?>το ημερολόγιό σου "<?php
                    echo $journal->Title;
                    ?>"<?php
                    break;
            }
            ?>.
            
<?php
            Element( 'email/footer' );
            
            return $subject;
        }
    }
?>
