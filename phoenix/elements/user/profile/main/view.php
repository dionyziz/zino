<?php
    class ElementUserProfileMainView extends Element {
        public function Render( $theuser, $commentid, $pageno ) {
            global $libs;
            global $user;
            global $water;
            global $xc_settings;

            $libs->Load( 'poll/poll' );
            $libs->Load( 'comment' );
            $libs->Load( 'notify' );
            $libs->Load( 'relation/relation' );
        
            if ( $user->HasPermission( PERMISSION_COMMENT_VIEW ) ) {
            // if ( $theuser->Profile->Numcomments > 0 ) { // duh, problem here!
                $finder = New CommentFinder();
                if ( $commentid == 0 ) {
                    $comments = $finder->FindByPage( $theuser, $pageno , true );
                    $total_pages = $comments[ 0 ];
                    $comments = $comments[ 1 ];
                }
                else {
                    $speccomment = New Comment( $commentid );
                    $comments = $finder->FindNear( $theuser, $speccomment );
                    $total_pages = $comments[ 0 ];
                    $pageno = $comments[ 1 ];
                    $comments = $comments[ 2 ];
                    $finder = New NotificationFinder();
                    $finder->DeleteByCommentAndUser( $speccomment, $user );
                    $water->Trace( 'speccoment is ' . $speccomment->Id );
                }
            // }
            }

            $finder = New PollFinder();
            $polls = $finder->FindByUser( $theuser , 0 , 1 );
            $finder = New JournalFinder();
            $journals = $finder->FindByUser( $theuser , 0 , 1 );
            $egoalbum = New Album( $theuser->Egoalbumid );
            if ( $egoalbum->Numphotos > 0 ) {
                $finder = New ImageFinder();
                $images = $finder->FindByAlbum( $egoalbum , 0 , 10 );
            }
            $showspace = $theuser->Id == $user->Id || strlen( $theuser->Space->GetText( 4 ) ) > 0;
            $showuploadavatar = $theuser->Id == $user->Id && $egoalbum->Numphotos == 0;

            $finder = New FriendRelationFinder();
            if ( $finder->IsFriend( $user, $theuser ) == FRIENDS_B_HAS_A ) {
                Element( 'user/profile/main/antisocial', $theuser );
            }

            //show avatar upload only if there are no notifications
            ?><div class="main"><?php
                if ( $showuploadavatar ) {
                    ?><div class="ybubble">    
                        <div class="body">
                            <h3>Ανέβασε μια φωτογραφία σου</h3>
                            <div class="uploaddiv">
                            <?php
                                /*
                                <iframe src="?p=upload&amp;albumid=<?php
                                echo $user->Egoalbumid;
                                ?>&amp;typeid=2" class="uploadframe" id="uploadframe" frameborder="0"></iframe>*/
                                ?>
                                <object data="?p=upload&amp;albumid=<?php
                                echo $user->Egoalbumid;
                                ?>&amp;typeid=2" class="uploadframe" id="uploadframe" type="text/html">
                                    <param src="?p=upload&amp;albumid=<?php
                                    echo $user->Egoalbumid;
                                    ?>&amp;typeid=2" />
                                </object>
                                
                            </div>
                        </div>
                        <i class="bl"></i>
                        <i class="br"></i>
                    </div><?php
                }
                ?><div class="photos"<?php
                if ( $egoalbum->Numphotos == 0 ) {
                    ?> style="display:none"<?php
                }
                ?>><?php
                    if ( $egoalbum->Numphotos > 0 ) {
                        if ( $egoalbum->Numphotos > 5 ) {
                            ?><div class="more"><a href="?p=album&amp;id=<?php
                            echo $egoalbum->Id;
                            ?>" class="button" title="Περισσότερες φωτογραφίες μου">&raquo;</a></div><?php
                        }
                        Element( 'user/profile/main/photos' , $images , $egoalbum );
                    }
                    else {
                        ?><ul></ul><?php
                    }
                ?></div>
                <div class="morealbums"><?php
                    if ( $theuser->Count->Albums > 1 ) {
                        ?><div class="viewalbums"><a href="<?php
                        Element( 'user/url' , $theuser );
                        ?>albums" class="button">Προβολή albums&raquo;</a></div><?php
                    }
                ?></div><?php
                $finder = New FriendRelationFinder();
                $friends = $finder->FindByUser( $theuser , 0 , 5 ); 
                
                if ( !empty( $friends ) || ( $user->Id == $theuser->Id && $user->Count->Relations == 0 ) ) { 
                    ?><div class="friends">
                        <h3>Οι φίλοι μου</h3><?php
                        if ( $user->Id == $theuser->Id && $user->Count->Relations == 0 ) {
                            ?>Δεν έχεις προσθέσει κανέναν φίλο. Μπορείς να προσθέσεις φίλους από το προφίλ τους.<?php
                        }
                        else {
                            Element( 'user/list' , $friends );
                        }
                        if ( $theuser->Count->Relations > 5 ) {
                            ?><a href="<?php
                            echo str_replace( '*', urlencode( $theuser->Subdomain ), $xc_settings[ 'usersubdomains' ] ) . 'friends';
                            ?>" class="button">Περισσότεροι φίλοι&raquo;</a><?php
                        }
                    ?></div>
                    <div class="barfade">
                        <div class="leftbar"></div>
                        <div class="rightbar"></div>
                    </div><?php
                }
                if ( !empty( $polls ) || ( $user->Id == $theuser->Id && $user->Count->Polls == 0 ) ) {
                    ?><div class="lastpoll">
                        <h3>Δημοσκοπήσεις</h3><?php
                        if ( $user->Id == $theuser->Id && $user->Count->Polls == 0 ) {
                            ?><div class="nopolls">
                            Δεν έχεις καμία δημοσκόπηση. Κάνε click στο παρακάτω link για να μεταβείς στη σελίδα
                            με τις δημοσκοπήσεις και να δημιουργήσεις μια.
                            <div><a href="<?php
                            Element( 'user/url' , $theuser );
                            ?>polls">Δημοσκοπήσεις</a>
                            </div>
                            </div><?php
                        } 
                        else {
                            ?><div class="container"><?php
                            Element( 'poll/small' , $polls[ 0 ] , true );
                            ?></div>
                            <a href="<?php
                            Element( 'user/url' , $theuser );
                            ?>polls" class="button">Περισσότερες δημοσκοπήσεις&raquo;</a><?php
                        }
                    ?></div><?php
                }
                Element( 'user/profile/main/questions' , $theuser );
                if ( !empty( $polls ) /*or not empty questions*/ ) {
                    ?><div class="barfade" style="margin-top:20px;clear:right;">
                        <div class="leftbar"></div>
                        <div class="rightbar"></div>
                    </div><?php
                }
                ?><div style="clear:right"></div><?php
                if ( !empty( $journals ) || ( $user->Id == $theuser->Id && $user->Count->Journals == 0 ) ) {
                    ?><div class="lastjournal">
                        <h3>Ημερολόγιο</h3><?php
                        if ( $user->Id == $theuser->Id && $user->Count->Journals == 0 ) {
                            ?><div class="nojournals">
                            Δεν έχεις καμία καταχώρηση.<br />
                            Κανε click στο παρακάτω link για να δημιουργήσεις μια.
                            <div><a href="?p=addjournal">Δημιουργία καταχώρησης</a></div>
                            </div><?php
                        }
                        else {
                            Element( 'journal/small' , $journals[ 0 ] );
                            ?><a href="<?php
                            Element( 'user/url' , $theuser );
                            ?>journals" class="button">Περισσότερες καταχωρήσεις&raquo;</a><?php
                        }    
                    ?></div>
                    <div class="barfade">
                        <div class="leftbar"></div>
                        <div class="rightbar"></div>
                    </div><?php
                }
                if ( $showspace ) {
                    ?><div class="space">
                        <h3>Χώρος</h3><?php
                        $showtext = $theuser->Space->GetText( 300 );
                        if ( strlen( $theuser->Space->GetText( 5 ) ) > 0 ) {
                            ?><div><?php
                            echo $showtext;
                            if ( strlen( $theuser->Space->GetText( 301 ) ) > strlen( $showtext ) ) {
                                ?>...<?php
                            }
                            ?></div><a href="?p=space&amp;subdomain=<?php
                            echo $theuser->Subdomain;
                            ?>" class="button">Προβολή χώρου&raquo;</a><?php
                        }
                        else {
                            ?><div class="nospace">
                                Δεν έχεις επεξεργαστεί τον χώρο σου ακόμα. Κάνε click στο παρακάτω link για να τον επεξεργαστείς.
                                <br /><a href="?p=editspace">Επεξεργασία χώρου</a>
                            </div><?php
                        }
                    ?></div>
                    <div class="barfade">
                            <div class="leftbar"></div>
                            <div class="rightbar"></div>
                    </div><?php
                }
                if ( $user->HasPermission( PERMISSION_COMMENT_VIEW ) ) {
                    ?><div class="comments">
                        <h3>Σχόλια στο προφίλ <?php
                        if ( $theuser->Gender == 'f' ) {
                            ?>της <?php
                        }
                        else {
                            ?>του <?php
                        }
                        Element( 'user/name' , $theuser , false );
                        ?></h3><?php
                        if ( $pageno <= 0 ) {
                            $pageno = 1;
                        }
                        
                            if ( $user->HasPermission( PERMISSION_COMMENT_CREATE ) ) {
                                Element( 'comment/reply', $theuser->Id, TYPE_USERPROFILE );
                            }
                        // if ( $theuser->Profile->Numcomments > 0 ) {
                            Element( 'comment/list' , $comments );
                            ?><div class="pagifycomments"><?php
                                $link = str_replace( '*', urlencode( $theuser->Subdomain ), $xc_settings[ 'usersubdomains' ] ) . '?pageno=';
                                Element( 'pagify' , $pageno , $link, $total_pages );
                            ?></div><?php
                        // }
                    ?></div><?php
                }
            ?></div><?php    
        }
    }
?>
