<?php

    class ElementNotificationView extends Element {
        public function Render( $notif ) {
            global $rabbit_settings;
            global $libs;
            global $user;
            
            $libs->Load( 'relation/relation' );

            if ( !$notif->Event->Exists() ) {
                return;
            }

            ?><div class="event" id="<?php
            echo $notif->Event->Id;
            ?>">
                <div class="toolbox">
                    <span class="time"><?php
                    Element( 'date/diff', $notif->Event->Created );
                    ?></span>
                    <a href="" onclick="Notification.Delete( '<?php
                    echo $notif->Event->Id;
                    ?>' );return false;" title="Διαγραφή"><img src="<?php
                    echo $rabbit_settings[ 'imagesurl' ];
                    ?>delete.png" /></a>
                </div>
                <div class="who"<?php
                if ( $notif->Event->Typeid != EVENT_FRIENDRELATION_CREATED ) {
                    ?> onclick="Notification.Visit( '<?php
                    ob_start();
                    Element( 'url' , $notif->Item );
                    echo htmlspecialchars( ob_get_clean() );
                    ?>' , '<?php
                    echo $notif->Event->Item->Typeid;
                    ?>' , '<?php
                    echo $notif->Event->Id;
                    ?>' , '<?php
                    echo $notif->Event->Item->Id;
                    ?>' );"<?php
                }
                ?>><?php
                    Element( 'user/avatar' , $notif->FromUser->Avatar->Id , $notif->FromUser->Id , $notif->FromUser->Avatar->Width , $notif->FromUser->Height , $notif->FromUser->Name , 100 , 'avatar' , '' , true , 50 , 50 );
                    Element( 'user/name' , $notif->FromUser->Id , $notif->FromUser->Name , $notif->FromUser->Subdomain , false );
                    if ( $notif->Event->Typeid != EVENT_FRIENDRELATION_CREATED ) {
                        if ( $notif->Item->Parentid == 0 ) {
                            ?> έγραψε:<?php
                        }
                        else {
                            ?> απάντησε στο σχόλιό σου:<?php
                        }
                    }
                    else {
                        ?> σε πρόσθεσε στους φίλους:<?php
                    }
                ?></div>
                <div class="subject"<?php
                if ( $notif->Event->Typeid != EVENT_FRIENDRELATION_CREATED ) {
                    ?> onclick="Notification.Visit( '<?php
                    ob_start();
                    Element( 'url' , $notif->Item );
                    echo htmlspecialchars( ob_get_clean() );
                    ?>' , '<?php
                    echo $notif->Event->Item->Typeid;
                    ?>' , '<?php
                    echo $notif->Event->Id;
                    ?>' , '<?php
                    echo $notif->Event->Item->Id;
                    ?>' );"<?php
                }
                ?>><?php
                    if ( $notif->Event->Typeid != EVENT_FRIENDRELATION_CREATED ) {
                        ?><p><span class="text">"<?php
                        $comment = $notif->Item;
                        $text = $comment->GetText( 30 );
                        echo $text;
                        if ( mb_strlen( $comment->Text ) > 30 ) {
                            ?>...<?php
                        }
                        ?>"</span>
                        , <?php
                        switch ( $comment->Typeid ) {
                            case TYPE_USERPROFILE:
                                ?>στο προφίλ <?php
                                if ( $comment->Item->Id == $notif->Touserid ) {
                                    ?>σου<?php
                                }
                                else {
                                    if ( $notif->FromUser->Gender == 'f' ) {
                                        ?>της <?php
                                    }
                                    else {
                                        ?>του <?php
                                    }
                                    if ( $notif->Fromuserid != $comment->Userid ) {
                                        ?><a href="<?php
                                        ob_start();
                                        Element( 'url', $comment );
                                        echo htmlspecialchars( ob_get_clean() );
                                        ?>" class="itempic"><?php
                                        Element( 'user/avatar' , $user->Avatar->Id , $user->Id , $user->Avatar->Width , $user->Avatar->Height , $user->Name , IMAGE_CROPPED_100x100 , '' , '' , false , 0 , 0 );
                                        ?></a><?php
                                    }
                                }
                                break;
                            case TYPE_POLL:
                                ?>στη δημοσκόπηση "<?php
                                echo htmlspecialchars( $comment->Item->Title );
                                ?>"<?php
                                break;
                            case TYPE_IMAGE:
                                ?>στη φωτογραφία <?php
                                Element( 'image/view' , $comment->Item->Id , $comment->Item->User->Id , $comment->Item->Width , $comment->Item->Height , IMAGE_CROPPED_100x100 , '' , $comment->Item->Name , $comment->Item->Name , '' , true , 75 , 75 );
                                break;
                            case TYPE_JOURNAL:
                                ?>στο ημερολόγιο "<?php
                                echo htmlspecialchars( $comment->Item->Title );
                                ?>"<?php
                                break;
                        }
                        ?></p>
                        <div class="eof"></div><?php
                    }
                    else {
                        $finder = New FriendRelationFinder();
                        $res = $finder->FindFriendship( $user , $notif->FromUser );
                        if ( !$res ) {
                            ?><div class="addfriend" id="addfriend_<?php
                            echo $notif->Fromuserid;
                            ?>"><a href="" onclick="Notification.AddFriend( '<?php
                            echo $notif->Event->Id;
                            ?>' , '<?php
                            echo $notif->FromUser->Id;
                            ?>' );return false;">Πρόσθεσέ τ<?php
                            if ( $notif->FromUser->Gender == 'f' ) {
                                ?>η<?php
                            }
                            else {
                                ?>o<?php
                            }
                            ?>ν στους φίλους</a></div><?php
                        }
                        ?><div class="viewprofile"><a href="" onclick="Notification.Visit( '<?php
                        Element( 'user/url' , $notif->FromUser->Id , $notif->FromUser->Subdomain );
                        ?>' , '0' , '<?php
                        echo $notif->Event->Id;
                        ?>' , '0' );return false;">Προβολή προφίλ&raquo;</a></div><?php
                    }
                    ?><div class="eof"></div>
                </div>
            </div><?php
        }
    }
?>
