<?php
    /// Content-type: text/plain ///
    class ElementNotificationEmailFavourite extends Element {
        public function Render( Notification $notification ) {
            global $rabbit_settings;
            global $user;
        
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
            ?> πρόσθεσε στα αγαπημένα <?php
            switch ( $notif->Item->Typeid ) {
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
                    ?>το ημερολόγιό σου "<?php
                    echo $favourite->Item->Title;
                    ?>"<?php
                    break;
            }
            ?>.
            
Για να δεις <?php
            switch ( $notif->Item->Typeid ) {
                case TYPE_IMAGE:
                    ?>την εικόνα στην οποία<?php
                    break;
                case TYPE_JOURNAL:
                    ?>το ημερολόγιο στο οποίο<?php
                    break;
            }
            ?> σε αναγνώρισε <?php
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
            Element( 'url', $image );
            
            Element( 'email/footer' );
            
            return $subject;
        }
    }
?>
