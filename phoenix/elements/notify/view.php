<?php
    class ElementNotifyView extends Element {
        public function Render( $notif ) {
            if ( !$notif->Exists() || !$notif->Item->Exists() ) {
                return;
            }
            
            ?><li>
                <a href="" class="remove" alt="X" title="Απόκρυψη ενημέρωσης">X</a>
                <?php
                switch ( $notif->Typeid ) {
                    case EVENT_COMMENT_CREATED:
                        Element( 'notify/type/comment', $notif->Item );
                        break;
                    case EVENT_FRIENDRELATION_CREATED:
                        Element( 'notify/type/relation', $notif );
                        break;
                    case EVENT_IMAGETAG_CREATED:
                        Element( 'notify/type/imagetag' );
                        break;
                    case EVENT_FAVOURITE_CREATED:
                        Element( 'notify/type/favourite' );
                        break;
                    case EVENT_USER_BIRTHDAY:
                        Element( 'notify/type/birthday' );
                        break;
                }
            ?></li><?php 
        }
    }
?>