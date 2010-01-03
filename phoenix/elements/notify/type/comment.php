<?php
    class ElementNotifyTypeComment extends Element {
        public function Render( $comment ) {
            global $user;
            
            ?><a class="item" href="<?php
                ob_start();
                Element( 'url', $comment );
                echo htmlspecialchars( ob_get_clean() );
                ?>"><?php
                if ( $comment->User->Avatarid > 0 ) {
                    ?><div class="avatar"><?php
                        Element( 'image/view', $comment->User->Avatarid, $comment->Userid, 50, 50, IMAGE_CROPPED_150x150, '', $comment->User->Name, '', true, 50, 50, 0 );
                    ?></div><?php
                }
                ?><span class="username"><?php
                    echo htmlspecialchars( $comment->User->Name );
                ?></span>
                <span class="text">"<?php
                $text = $comment->GetText( 30 );
                echo $text;
                if ( mb_strlen( $comment->Text ) > 30 ) {
                    ?>...<?php
                }
                ?>"</span><?php
                
                /*switch ( Type_FromObject( $comment->Item ) ) {
                    case TYPE_JOURNAL:
                        
                    case TYPE_IMAGE:
                        
                    case TYPE_POLL:
                        
                    case TYPE_USERPROFILE:
                        
                }*/
            ?></a><?php
        }
    }
?>