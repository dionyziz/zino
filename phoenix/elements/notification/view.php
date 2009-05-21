<?php
    class ElementNotificationView extends Element {
        public function Render( $notif ) {
            global $rabbit_settings;
            global $libs;
            global $user;
            
            $libs->Load( 'relation/relation' );
            $libs->Load( 'image/tag' );

            if ( !$notif->Exists() ) {
                return;
            }

            ?><div class="event" id="event_<?php
            echo $notif->Id;
            ?>">
                <div class="toolbox">
                    <span class="time"><?php
                    Element( 'date/diff', $notif->Created );
                    ?></span>
                    <a href="" onclick="return Notification.Delete( '<?php
                    echo $notif->Id;
                    ?>' )" title="Διαγραφή" class="s_delete">.</a>
                </div>
                <div class="who"<?php
                if ( $notif->Typeid == EVENT_COMMENT_CREATED ) {
                    ?> onclick="Notification.Visit( '<?php
                    ob_start();
                    Element( 'url' , $notif->Item );
                    echo htmlspecialchars( ob_get_clean() );
                    ?>' , '<?php
                    echo $notif->Item->Typeid;
                    ?>' , '<?php
                    echo $notif->Id;
                    ?>' , '<?php
                    echo $notif->Item->Id;
                    ?>' );"<?php
                }
                ?>><?php
                    Element( 'user/avatar' , $notif->FromUser->Avatar->Id , $notif->FromUser->Id , $notif->FromUser->Avatar->Width , $notif->FromUser->Avatar->Height , $notif->FromUser->Name , 100 , 'avatar' , '' , true , 50 , 50 );
                    Element( 'user/name' , $notif->FromUser->Id , $notif->FromUser->Name , $notif->FromUser->Subdomain , false );
                    switch ( $notif->Typeid ) {
                        case EVENT_FRIENDRELATION_CREATED:
                            ?> σε πρόσθεσε στους φίλους:<?php
                            break;
                        case EVENT_IMAGETAG_CREATED:
                            ?> σε αναγνώρισε:<?php
                            break;
                        case EVENT_FAVOURITE_CREATED:
                            ?> πρόσθεσε στα αγαπημένα:<?php
                            break;
                        case EVENT_COMMENT_CREATED:
                            if ( $notif->Item->Parentid == 0 ) {
                                ?> έγραψε:<?php
                            }
                            else {
                                ?> απάντησε στο σχόλιό σου:<?php
                            }
                            break;
                    }
                ?></div>
                <div class="subject"<?php
                switch ( $notif->Typeid ) {
                    case EVENT_IMAGETAG_CREATED:
                        ?> onclick="Notification.Visit( '<?php
                        ob_start();
                        Element( 'url', $notif->Item );
                        echo htmlspecialchars( ob_get_clean() );
                        ?>' , '0', '<?php
                        echo $notif->Id;
                        ?>', '0' );"<?php
                        break;
                    case EVENT_USER_BIRTHDAY:
                        ?> onclick="Notification.Visit( '<?php
                        ob_start();
                        Element( 'url', $notif->FromUser );
                        echo htmlspecialchars( ob_get_clean() );
                        ?>' , '0', '<?php
                        echo $notif->Id;
                        ?>', '0' );"<?php
                        break;
                    case EVENT_FRIENDRELATION_CREATED:
                        break;
                    case EVENT_FAVOURITE_CREATED:
                        ?> onclick="Notification.Visit( '<?php
                        ob_start();
                        Element( 'url', $notif->Item );
                        echo htmlspecialchars( ob_get_clean() );
                        ?>' , '0', '<?php
                        echo $notif->Id;
                        ?>', '0' );"<?php
                        break;
                    case EVENT_COMMENT_CREATED:
                        ?> onclick="Notification.Visit( '<?php
                        ob_start();
                        Element( 'url' , $notif->Item );
                        echo htmlspecialchars( ob_get_clean() );
                        ?>' , '<?php
                        echo $notif->Item->Typeid;
                        ?>' , '<?php
                        echo $notif->Id;
                        ?>' , '<?php
                        echo $notif->Item->Id;
                        ?>' );"<?php
                        break;
                }
                ?>><?php
                    switch ( $notif->Typeid ) {
                        case EVENT_FRIENDRELATION_CREATED:
                            $finder = New FriendRelationFinder();
                            $res = $finder->FindFriendship( $user , $notif->FromUser );
                            if ( !$res ) {
                                ?><div class="addfriend" id="addfriend_<?php
                                echo $notif->Fromuserid;
                                ?>"><a href="" onclick="return Notification.AddFriend( '<?php
                                echo $notif->Id;
                                ?>' , '<?php
                                echo $notif->FromUser->Id;
                                ?>' )"><span class="s_addfriend">&nbsp;</span>Πρόσθεσέ τ<?php
                                if ( $notif->FromUser->Gender == 'f' ) {
                                    ?>η<?php
                                }
                                else {
                                    ?>o<?php
                                }
                                ?>ν στους φίλους</a></div><?php
                            }
                            ?><div class="viewprofile"><a href="" onclick="return Notification.Visit( '<?php
                            Element( 'user/url' , $notif->FromUser->Id , $notif->FromUser->Subdomain );
                            ?>' , '0' , '<?php
                            echo $notif->Id;
                            ?>' , '0' )">Προβολή προφίλ&raquo;</a></div><?php
                            break;
                        case EVENT_IMAGETAG_CREATED:
                            ?><p><?php
                            $image = New Image( $notif->Item->Imageid );
                            if ( $image->Name != '' ) {
                                ?>στην εικόνα "<?php
                                echo htmlspecialchars( $image->Name );
                                ?>"<?php
                            }
                            else if ( $image->Album->Id == $image->User->Egoalbumid ) {
                                ?>στις φωτογραφίες <?php
                                if ( $image->User->Id == $user->Id ) {
                                    ?>σου<?php
                                }
                                else if ( $image->User->Gender == 'f' ) {
                                    ?>της <?php
                                }
                                else {
                                    ?>του <?php
                                }
                                if ( $image->User->Id != $user->Id ) {
                                    echo htmlspecialchars( $image->User->Name );
                                }
                            }
                            else {
                                ?>σε μια εικόνα του Album "<?php
                                switch ( $image->Album->Ownertype ) {
                                    case TYPE_USERPROFILE:
                                        echo htmlspecialchars( $image->Album->Name );
                                        break;
                                    case TYPE_SCHOOL:
                                        echo htmlspecialchars( $image->Album->Owner->Name );
                                        break;
                                }
                                ?>"<?php
                            }
                            Element( 'image/view' , $image->Id , $image->User->Id , $image->Width , $image->Height , IMAGE_CROPPED_100x100 , '' , $image->Name , '' , true , 75 , 75 , 0 );
                            ?></p><?php
                            break;
                        case EVENT_USER_BIRTHDAY:
                            ?><p><?php
                            if ( $notif->FromUser->Gender == 'f' ) {
                                ?>Η <?php
                            }
                            else {
                                ?>O <?php
                            }
                            $days = daysDiff( $notif->Created );
                            if ( $days == 0 ) {
                                ?>έχει γενέθλια σήμερα!<?php
                            }
                            else {
                                ?>είχε γενέθλια πριν <?php
                                echo $days;
                                ?> μέρες!<?php
                            }
                            ?> Πες <?php
                            if ( $notif->FromUser->Gender == 'f' ) {
                                ?>της <?php
                            }
                            else {
                                ?>του <?php
                            }
                            ?> χρόνια πολλά! <span class="emoticon-cake">.</span></p><?php
                            break;
                        case EVENT_FAVOURITE_CREATED:
                            ?><p><?php
                            switch ( $notif->Item->Typeid ) {
                                case TYPE_IMAGE:
                                    $image = $notif->Item->Item;
									Element( 'image/view' , $image->Id , $image->User->Id , $image->Width , $image->Height , IMAGE_CROPPED_100x100 , '' , $image->Name , '' , true , 75 , 75 , 0 );
                                    if ( $image->Name != '' ) {
                                        ?>την εικόνα "<?php
                                        echo htmlspecialchars( $image->Name );
                                        ?>"<?php
                                    }
                                    else if ( $image->Album->Id == $image->User->Egoalbumid ) {
                                        ?>μια φωτογραφία σου<?php
                                    }
                                    else {
                                        ?>μια εικόνα του Album "<?php
                                        switch ( $image->Album->Ownertype ) {
                                            case TYPE_USERPROFILE:
                                                echo htmlspecialchars( $image->Album->Name );
                                                break;
                                            case TYPE_SCHOOL:
                                                echo htmlspecialchars( $image->Album->Owner->Name );
                                                break;
                                        }
                                        ?>"<?php
                                    }
                                    break;
                                case TYPE_JOURNAL:
                                    $journal = $notif->Item->Item;
                                    ?>Το ημερολόγιό σου <a href="<?php
                                    ob_start();
                                    Element( 'url', $journal );
                                    echo htmlspecialchars( ob_get_clean() );
                                    ?>"><?php
                                    echo htmlspecialchars( $journal->Title );
                                    ?></a><?php
                                    break;
                            }
                            ?></p><?php
                            break;
                        case EVENT_COMMENT_CREATED: 
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
                                        if ( $notif->Item->Item->Gender == 'f' ) {
                                            ?>της <?php
                                        }
                                        else {
                                            ?>του <?php
                                        }
                                        if ( $notif->Fromuserid != $comment->Item->Id ) {
                                            ?><a href="<?php
                                            ob_start();
                                            Element( 'url', $comment );
                                            echo htmlspecialchars( ob_get_clean() );
                                            ?>" class="itempic"><?php
                                            Element( 'user/avatar' , $comment->Item->Avatar->Id , $comment->Item->Id , $comment->Item->Avatar->Width , $comment->Item->Avatar->Height , $comment->Item->Name , IMAGE_CROPPED_100x100 , '' , '' , false , 0 , 0 );
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
                                    Element( 'image/view' , $comment->Item->Id , $comment->Item->User->Id , $comment->Item->Width , $comment->Item->Height , IMAGE_CROPPED_100x100 , '' , $comment->Item->Name , '' , true , 75 , 75 , 0 );
                                    break;
                                case TYPE_JOURNAL:
                                    ?>στο ημερολόγιο "<?php
                                    echo htmlspecialchars( $comment->Item->Title );
                                    ?>"<?php
                                    break;
                                case TYPE_SCHOOL:
                                    ?>στο <?php
                                    echo htmlspecialchars( $comment->Item->Name );
                                    break;
                            }
                            ?></p>
                            <div class="eof"></div><?php
                            break;
                    }
                    ?><div class="eof"></div>
                </div>
            </div><?php
        }
    }
?>
