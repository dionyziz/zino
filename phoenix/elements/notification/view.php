<?php

    class ElementNotificationView extends Element {
        public function Render( $notif ) {
            global $rabbit_settings;
            global $libs;
            global $user;
            
            $libs->Load( 'relation/relation' );
            $libs->Load( 'image/tag' );

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
                if ( $notif->Event->Typeid != EVENT_FRIENDRELATION_CREATED && $notif->Event->Typeid != EVENT_IMAGETAG_CREATED ) {
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
                    Element( 'user/avatar' , $notif->FromUser->Avatar->Id , $notif->FromUser->Id , $notif->FromUser->Avatar->Width , $notif->FromUser->Avatar->Height , $notif->FromUser->Name , 100 , 'avatar' , '' , true , 50 , 50 );
                    Element( 'user/name' , $notif->FromUser->Id , $notif->FromUser->Name , $notif->FromUser->Subdomain , false );
                    if ( $notif->Event->Typeid == EVENT_FRIENDRELATION_CREATED ) {
                        ?> σε πρόσθεσε στους φίλους:<?php
                    }
                    else if ( $notif->Event->Typeid == EVENT_IMAGETAG_CREATED ) {
                        ?> σε αναγνώρισε:<?php
                    }
                    else {
                        if ( $notif->Item->Parentid == 0 ) {
                            ?> έγραψε:<?php
                        }
                        else {
                            ?> απάντησε στο σχόλιό σου:<?php
                        }
                    }
                ?></div>
                <div class="subject"<?php
                if ( $notif->Event->Typeid == EVENT_IMAGETAG_CREATED ) {
                    ?> onclick="Notification.Visit( '<?php
                    ob_start();
                    Element( 'url', $notif->Item );
                    echo htmlspecialchars( ob_get_clean() );
                    ?>' , '0', '<?php
                    echo $notif->Event->Id;
                    ?>', '0' );"<?php
                }
                else if ( $notif->Event->Typeid != EVENT_FRIENDRELATION_CREATED ) { // Comment
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
                    if ( $notif->Event->Typeid == EVENT_FRIENDRELATION_CREATED ) {
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
                    else if ( $notif->Event->Typeid == EVENT_IMAGETAG_CREATED ) {
                        /*?><div class="viewprofile"><a href="" onclick="Notification.Visit( '<?php
                        echo $rabbit_settings[ 'webaddress' ];
                        ?>/?p=photo&amp;id=<?php
                        echo $notif->Item->Imageid;
                        ?>' , '0' , '<?php
                        echo $notif->Event->Id;
                        ?>' , '0' );return false;">Προβολή εικόνας&raquo;</a></div><?php*/
                        ?><p><?php
                        $image = New Image( $notif->Item->Imageid );
                        if ( $image->Name != '' ) {
                            ?>στην εικόνα <?php
                            echo $image->Name;
                        }
                        else if ( $image->Album->Id == $image->User->Egoalbumid ) {
                            ?>στις φωτογραφίες <?php
                            if ( $image->User->Gender == 'f' ) {
                                ?>της <?php
                            }
                            else {
                                ?>του <?php
                            }
                            echo $image->User->Name;
                        }
                        else {
                            ?>σε μια εικόνα του Album "<?php
                            echo $image->Album->Name;
                            ?>"<?php
                        }
                        Element( 'image/view' , $image->Id , $image->User->Id , $image->Width , $image->Height , IMAGE_CROPPED_100x100 , '' , $image->Name , $image->Name , '' , true , 75 , 75 );
                        ?></p><?php
                    }
                    else {
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
                    ?><div class="eof"></div>
                </div>
            </div><?php
        }
    }
?>
