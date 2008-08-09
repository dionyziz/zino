<?php
    
    class ElementFrontpageCommentView extends Element {
        public function Render( $comment ) {
            ?><div class="event">
                <div class="toolbox">
                    <span class="time"><?php
                    Element( 'date/diff', $comment->Created );
                    ?></span>
                </div>
                <div class="who">
                    <a href="<?php
                    Element( 'user/url' , $comment->User->Id , $comment->User->Subdomain );
                    ?>"><?php
                        Element( 'user/avatar' , $comment->User , 100 , 'avatar' , '' , true , 50 , 50 );
                        echo $comment->User->Name;
                    ?></a> έγραψε:
                </div>
                <div class="subject">
                    <p><?php
                        $text = $comment->GetText( 35 );
                        if ( !empty( $text ) ) {
                            ?><span class="text">"<?php
                            echo trim( $text );
                            if ( strlen( $text ) > 30 ) {
                                ?>...<?php
                            }
                            ?>"</span>, <?php
                        }
                        switch ( $comment->Typeid ) {
                            case TYPE_POLL:
                                ?>στη δημοσκόπηση <a href="<?php
                                ob_start();
                                Element( 'url' , $comment );
                                echo htmlspecialchars( ob_get_clean() );
                                ?>"><?php
                                echo htmlspecialchars( $comment->Item->Title );
                                ?></a><?php
                                break;
                            case TYPE_IMAGE:
                                ?>στη φωτογραφία <a href="<?php
                                ob_start();
                                Element( 'url' , $comment );
                                echo htmlspecialchars( ob_get_clean() );
                                ?>" class="itempic"><?php
                                Element( 'image/view' , $comment->Item , IMAGE_CROPPED_100x100 , '' , $comment->Item->Name , $comment->Item->Name , '' , true , 75 , 75 );
                                ?></a><?php
                                break;
                            case TYPE_USERPROFILE:
                                $user = $comment->Item;
                                ?>στο προφίλ <?php
                                if ( $user->Gender == 'f' ) {
                                    ?>της <?php
                                }
                                else {
                                    ?>του <?php
                                }
                                ?><a href="<?php
                                ob_start();
                                Element( 'url', $comment );
                                echo htmlspecialchars( ob_get_clean() );
                                ?>" class="itempic"><?php
                                Element( 'user/avatar' , $user, IMAGE_CROPPED_100x100 , '' , '' , true , 75 , 75 );
                                ?></a><?php
                                break;
                            case TYPE_JOURNAL:
                                ?>στο ημερολόγιο <a href="<?php
                                ob_start();
                                Element( 'url' , $comment );
                                echo htmlspecialchars( ob_get_clean() );
                                ?>"><?php
                                echo htmlspecialchars( $comment->Item->Title );
                                ?></a><?php
                                break;
                        }
                        ?>
                    </p>
                </div>
            </div><?php
        }
    }
?>
