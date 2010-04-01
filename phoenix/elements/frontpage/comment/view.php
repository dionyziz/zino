<?php
    
    class ElementFrontpageCommentView extends Element {
        public function Render( $comment ) {
            global $libs;
            
            $libs->Load( 'image/image' );
            
            ?><div class="event">
                <div class="who">
                    <a href="<?php
                    ob_start();
                    Element( 'user/url' , $comment->Userid , $comment->User->Subdomain );
                    echo htmlspecialchars( ob_get_clean() );
                    ?>"><?php
                        Element( 'user/avatar' , $comment->User->Avatarid, $comment->Userid , $comment->User->Avatar->Width , $comment->User->Avatar->Height , $comment->User->Name , 100 , 'avatar' , '' , true , 50 , 50 );
                        echo $comment->User->Name;
                    ?></a> milic:
                </div>
                <div class="subject">
                    <p><?php
                        $text = $comment->GetText( 35 );
                        if ( !empty( $text ) ) {
                            ?><span class="text">"<?php
                            //echo trim( $text );
                            echo str_replace( array( 'ς', 'σ', 'Σ' ), array( 'c', 'c', 'C' ), $text );
                            if ( strlen( $text ) > 30 ) {
                                ?>...<?php
                            }
                            ?>"</span>, <?php
                        }
                        switch ( $comment->Typeid ) {
                            case TYPE_POLL:
                                ?>st dim0skopic <a href="<?php
                                ob_start();
                                Element( 'url' , $comment );
                                echo htmlspecialchars( ob_get_clean() );
                                ?>"><?php
                                echo htmlspecialchars( $comment->Item->Title );
                                ?></a><?php
                                break;
                            case TYPE_IMAGE:
                                ?>stn pic <a href="<?php
                                ob_start();
                                Element( 'url' , $comment );
                                echo htmlspecialchars( ob_get_clean() );
                                ?>" class="itempic"><?php
                                Element( 'image/view' , $comment->Itemid , $comment->Item->Userid , $comment->Item->Width , $comment->Item->Height , IMAGE_CROPPED_100x100 , '' , $comment->Item->Name , '' , true , 75 , 75 , 0 );
								?></a><?php
                                break;
                            case TYPE_USERPROFILE:
                                $user = $comment->Item;
                                ?>st profil <?php
                                if ( $user->Gender == 'f' ) {
                                    ?>tc <?php
                                }
                                else {
                                    ?>t <?php
                                }
                                ?><a href="<?php
                                ob_start();
                                Element( 'url', $comment );
                                echo htmlspecialchars( ob_get_clean() );
                                ?>" class="itempic"><?php
                                Element( 'user/avatar' , $user->Avatarid , $user->Id , $user->Avatar->Width , $user->Avatar->Height , $user->Name , IMAGE_CROPPED_100x100 , '' , '' , true , 75 , 75 );
                                ?></a><?php
                                break;
                            case TYPE_JOURNAL:
                                ?>st imrlogio <a href="<?php
                                ob_start();
                                Element( 'url' , $comment );
                                echo htmlspecialchars( ob_get_clean() );
                                ?>"><?php
                                echo htmlspecialchars( $comment->Item->Title );
                                ?></a><?php
                                break;
                            case TYPE_SCHOOL:
                                ?>st <a href="<?php
                                ob_start();
                                Element( 'url', $comment );
                                echo htmlspecialchars( ob_get_clean() );
                                ?>"><?php
                                echo htmlspecialchars( $comment->Item->Name );
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
