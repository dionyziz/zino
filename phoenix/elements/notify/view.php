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
                    case EVENT_FRIENDRELATION_CREATED:
                        Element( 'notify/type/relation' );
                    case EVENT_IMAGETAG_CREATED:
                        Element( 'notify/type/imagetag' );
                    case EVENT_FAVOURITE_CREATED:
                        Element( 'notify/type/favourite' );
                    case EVENT_USER_BIRTHDAY:
                        Element( 'notify/type/birthday' );
                }
            ?></li><?php 
        }
    }
?>