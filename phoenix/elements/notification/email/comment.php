<?php
    /// Content-type: text/plain ///
	class ElementNotificationEmailComment extends Element {
        public function Render( Notification $notification ) {
            $from = $notification->FromUser;
            $comment = $notification->Item;

            w_assert( $from instanceof User );
            w_assert( $from->Exists() );
            w_assert( $comment instanceof Comment );

            ob_start();
            if ( $from->Gender == 'f' ) {
                ?>Η<?php
            }
            else {
                ?>Ο<?php
            }
            ?> <?php
            echo $from->Name;
            if ( $comment->Parentid ) {
                ?> απάντησε στο σχόλιό σου<?php
            }
            else {
                ?> σχολίασε <?php

                switch ( $comment->Typeid ) {
                    case TYPE_JOURNAL:
                        ?>στο ημερολόγιό σου "<?php
                        echo $comment->Item->Title;
                        ?>" <?php
                        break;
                    case TYPE_IMAGE:
                        ?>στην εικόνα σου<?php
                        if ( !empty( $comment->Item->Name ) ) {
                            ?> "<?php
                            echo $comment->Item->Name;
                            ?>"<?php
                        }
                        break;
                    case TYPE_USERPROFILE:
                        ?>στο προφίλ σου<?php
                        break;
                    case TYPE_POLL:
                        ?>στην δημοσκόπισή σου "<?php
                        echo $comment->Item->Question;
                        ?>"<?php
                        break;
                }
            }
            $subject = ob_get_clean();
            echo $subject;

            $text = trim( htmlspecialchars_decode( strip_tags( $comment->Text ) ) );
            if ( !empty( $text ) ) {
                ?> και έγραψε: 
                
    "<?php
                echo $text;
                ?>"
                
    <?php
            }
            ?>

    Για να απαντήσεις στο σχόλιό <?php
            if ( $from->Gender == 'f' ) {
                ?>της<?php
            }
            else {
                ?>του<?php
            }
            ?> <?php
            echo $from->Name;
            ?> κάνε κλικ στον παρακάτω σύνδεσμο:
    <?php
            Element( 'url', $comment );

            Element( 'email/footer' );

            return $subject;
        }
    }
?>
