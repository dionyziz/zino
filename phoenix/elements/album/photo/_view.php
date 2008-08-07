<?php
    // Masked by kostis90gr for Photo Tagging
    class ElementAlbumPhotoView extends Element {
        public function Render( tInteger $id , tInteger $commentid , tInteger $pageno ) {
            global $user;
            global $page;
            global $libs;
            global $water;
            
            $libs->Load( 'comment' );
            $libs->Load( 'favourite' );
            $libs->Load( 'notify' );
            $libs->Load( 'relation/relation' );
            
            //------------------
            $page->AttachStylesheet( 'css/album/photo/tag.css' );
            if ( $user->HasPermission( PERMISSION_TAG_CREATE ) ) {
                $relfinder = New FriendRelationFinder();
                $mutual = $relfinder->FindMutualByUser( $user );
                $jsarr = "Tag.friends = [ ";
                foreach( $mutual as $mutual_friend ) {
                    $jsarr .= "'" . $mutual_friend . "', ";
                }
                $jsarr = substr( $jsarr, 0, -2);
                $jsarr .= " ];";
                
                if ( !empty( $mutual ) ) {
                    $page->AttachInlineScript( $jsarr );
                }
            }
            $page->AttachScript( 'js/album/photo/tag.js' );
            //------------------
            $id = $id->Get();
            $commentid = $commentid->Get();
            $pageno = $pageno->Get();
            $image = New Image( $id );
            
            if( !$image->Exists() ) {
                ?>Η φωτογραφία δεν υπάρχει<div class="eof"></div><?php
                return;
            }
        Element( 'user/sections', 'album' , $image->User );
            if ( $image->IsDeleted() ) {
                ?>Η φωτογραφία έχει διαγραφεί<div class="eof"></div><?php
                return;
            }

            if ( $image->Name != "" ) {
                $title = htmlspecialchars( $image->Name );
                $page->SetTitle( $title );
            }
            else {
                if ( $image->Album->User->Egoalbumid == $image->Album->Id ) {
                    if ( strtoupper( substr( $image->Album->User->Name, 0, 1 ) ) == substr( $image->Album->User->Name, 0, 1 ) ) {
                        $page->SetTitle( $image->Album->User->Name . " Φωτογραφίες" );
                    }
                    else {
                        $page->SetTitle( $image->Album->User->Name . " φωτογραφίες" );
                    }
                    $title = $image->User->Name;
                }    
                else {
                    $page->SetTitle( $image->Album->Name );
                    $title = htmlspecialchars( $image->Album->Name );
                }
            }
            if ( $pageno <= 0 ) {
                $pageno = 1;
            }
            $finder = New FavouriteFinder();
            $fav = $finder->FindByUserAndEntity( $user, $image );
            ?><div id="photoview">
                <h2><?php
                echo htmlspecialchars( $image->Name );
                ?></h2>
                <span>στο album</span> <a href="?p=album&amp;id=<?php
                echo $image->Album->Id;
                ?>"><?php
                if ( $image->Album->Id == $image->User->Egoalbumid ) {
                    ?>Εγώ<?php
                }
                else {
                    echo htmlspecialchars( $image->Album->Name );
                }
                ?></a>
                <dl><?php
                    if ( $image->Numcomments > 0 ) {
                        ?><dd class="commentsnum"><?php
                        echo $image->Numcomments;
                        ?> σχόλι<?php
                        if ( $image->Numcomments == 1 ) {
                            ?>ο<?php
                        }
                        else {
                            ?>α<?php
                        }
                        ?></dd><?php
                    }
                    if( $user->Id != $image->User->Id ) { 
                        ?><dd class="addfav"><a href="" class="<?php
                        if ( !$fav ) {
                            ?>add<?php
                        }
                        else {
                            ?>isadded<?php
                        }
                        ?>" title="<?php
                        if ( !$fav ) {
                            ?>Προσθήκη στα αγαπημένα<?php
                        } 
                        else {
                            ?>Αγαπημένο<?php
                        }
                        ?>" onclick="PhotoView.AddFav( '<?php
                        echo $image->Id;
                        ?>' , this );return false;"><?php
                        if ( !$fav ) {
                            ?>Προσθήκη στα αγαπημένα<?php
                        }
                        ?></a></dd><?php
                    }
                    if ( $user->HasPermission( PERMISSION_TAG_CREATE ) ) {
                        ?><dd class="addtag"><a href="" title="Ποιος είναι στην φωτογραφία" onclick="Tag.start( false );return false;">Γνωρίζεις κάποιον;</a></dd><?php
                    }
                 ?></dl><?php
                if ( $image->User->Id == $user->Id || $user->HasPermission( PERMISSION_IMAGE_DELETE_ALL ) ) {
                    ?><div class="owner">
                        <div class="edit"><a href="" onclick="PhotoView.Rename( '<?php
                        echo $image->Id;
                        ?>' , <?php
                        echo htmlspecialchars( w_json_encode( $image->Album->Name ) );
                        ?> );return false;"><?php
                        if ( $image->Name == '' ) {
                            ?>Όρισε όνομα<?php
                        }
                        else {
                            ?>Μετονομασία<?php
                        }
                        ?></a></div>
                        <div class="delete"><a href="" onclick="PhotoView.Delete( '<?php
                        echo $image->Id;
                        ?>' );return false;">Διαγραφή</a></div><?php
                        if ( $image->Album->Mainimageid != $image->Id ) {
                            ?><div class="mainimage"><a href="" onclick="PhotoView.MainImage( '<?php
                            echo $image->Id;
                            ?>' );return false;">
                            Ορισμός προεπιλεγμένης</a>
                            </div><?php
                        }
                    ?></div><?php
                }
                ?><div class="eof"></div><?php
                if ( $image->Album->Numphotos > 1 ) {
                    ?><div class="photothumbs"><?php
                        $finder = New ImageFinder();
                        $photos = $finder->FindAround( $image , 12 );
                        $pivot = $i = 0;
                        foreach ( $photos as $photo ) {
                            if ( $photo->Id == $image->Id ) {
                                $pivot = $i;
                                break;
                            }
                            ++$i;
                        }
                        if ( $pivot > 0 ) {
                            ?><div class="left arrow">
                                <a href="?p=photo&amp;id=<?php
                                echo $photos[ $pivot - 1 ]->Id;
                                ?>" class="nav"><img src="images/previous.jpg" alt="Προηγούμενη" title="Προηγούμενη" class="hoverclass" /></a>
                            </div><?php
                        }
                        if ( $pivot + 1 < count( $photos ) && count( $photos ) > 1 ) {
                            ?><div class="right arrow">
                                <a href="?p=photo&amp;id=<?php
                                echo $photos[ $pivot + 1 ]->Id;
                                ?>" class="nav"><img src="images/next.jpg" alt="Επόμενη" title="Επόμενη" class="hoverclass" /></a>
                            </div><?php
                        }
                        ?><ul><?php    
                            if ( $pivot > 0 ) {
                                for ( $i = 0; $i < $pivot ; ++$i ) {
                                    ?><li><span><a href="?p=photo&amp;id=<?php
                                    echo $photos[ $i ]->Id;
                                    ?>"><?php
                                    Element( 'image/view' , $photos[ $i ] , IMAGE_CROPPED_100x100, '' , $photos[ $i ]->Name , $photos[ $i ]->Name , '' , false , 0 , 0 );
                                    ?></a></span></li><?php
                                }
                            }
                            ?><li class="selected"><?php
                                Element( 'image/view' , $photos[ $pivot ] , IMAGE_CROPPED_100x100, '' , $photos[ $pivot ]->Name , $photos[ $pivot ]->Name , '' , false , 0 , 0 );
                            ?></li><?php
                            if ( $pivot < 12 ) {                        
                                for ( $i = $pivot + 1; $i < count( $photos ); ++$i ) {
                                    ?><li><span><a href="?p=photo&amp;id=<?php
                                    echo $photos[ $i ]->Id;
                                    ?>"><?php
                                    Element( 'image/view' , $photos[ $i ] , IMAGE_CROPPED_100x100 , '' , $photos[ $i ]->Name , $photos[ $i ]->Name , '' , false , 0 , 0 );
                                    ?></a></span></li><?php
                                }
                            }
                        ?></ul><?php
                    ?></div><?php
                }
                ?><div class="thephoto" style="width:<?php
                echo $image->Width;
                ?>px;height:<?php
                echo $image->Height;
                ?>px;" onmousedown="Tag.katoPontike( event );return false;" onmouseup="Tag.showSug( event );return false;" onmouseout="Tag.ekso( event );return false;" onmousemove="Tag.drag( event );return false;"><?php
                    Element( 'image/view' , $image , IMAGE_FULLVIEW, '' , $title , $title , '' , false , 0 , 0 );
                    if ( $image->Width > 170 && $image->Height > 170 ) {
                        ?><div class="tagme"></div>
                        <a name="tagging_area" class="ankh" href="" />
                        <div class="frienders">
                            <a href="" class="closer" />
                            <div>Ποιός είναι αυτός;</div>
                            <form action="">
                                <input type="text" value="" onmousedown="Tag.focusInput( event );" onkeyup="Tag.filterSug( event );" />
                            </form>
                            <ul class="frienders">
                                <li></li>
                            </ul>
                        </div><?php
                    }
                ?></div><?php
                /*
                if ( $image->Album->Numphotos > 1 ) {
                    ?><div class="photothumbs"><?php
                        $finder = New ImageFinder();
                        $photos = $finder->FindAround( $image , 12 );
                        $pivot = $i = 0;
                        foreach ( $photos as $photo ) {
                            if ( $photo->Id == $image->Id ) {
                                $pivot = $i;
                                break;
                            }
                            ++$i;
                        }
                        if ( $pivot > 0 ) {
                            ?><div class="left arrow">
                                <a href="?p=photo&amp;id=<?php
                                echo $photos[ $pivot - 1 ]->Id;
                                ?>" class="nav"><img src="images/previous.jpg" alt="Προηγούμενη" title="Προηγούμενη" class="hoverclass" /></a>
                            </div><?php
                        }
                        if ( $pivot + 1 < count( $photos ) && count( $photos ) > 1 ) {
                            ?><div class="right arrow">
                                <a href="?p=photo&amp;id=<?php
                                echo $photos[ $pivot + 1 ]->Id;
                                ?>" class="nav"><img src="images/next.jpg" alt="Επόμενη" title="Επόμενη" class="hoverclass" /></a>
                            </div><?php
                        }
                        ?><ul><?php    
                            if ( $pivot > 0 ) {
                                for ( $i = 0; $i < $pivot ; ++$i ) {
                                    ?><li><span><a href="?p=photo&amp;id=<?php
                                    echo $photos[ $i ]->Id;
                                    ?>"><?php
                                    Element( 'image/view' , $photos[ $i ] , IMAGE_CROPPED_100x100, '' , $photos[ $i ]->Name , $photos[ $i ]->Name , '' , false , 0 , 0 );
                                    ?></a></span></li><?php
                                }
                            }
                            ?><li class="selected"><?php
                                Element( 'image/view' , $photos[ $pivot ] , IMAGE_CROPPED_100x100, '' , $photos[ $pivot ]->Name , $photos[ $pivot ]->Name , '' , false , 0 , 0 );
                            ?></li><?php
                            if ( $pivot < 12 ) {                        
                                for ( $i = $pivot + 1; $i < count( $photos ); ++$i ) {
                                    ?><li><span><a href="?p=photo&amp;id=<?php
                                    echo $photos[ $i ]->Id;
                                    ?>"><?php
                                    Element( 'image/view' , $photos[ $i ] , IMAGE_CROPPED_100x100 , '' , $photos[ $i ]->Name , $photos[ $i ]->Name , '' , false , 0 , 0 );
                                    ?></a></span></li><?php
                                }
                            }
                        ?></ul><?php
                    ?></div><?php
                }
                */
                ?><div class="comments"><?php
                if ( $user->HasPermission( PERMISSION_COMMENT_VIEW ) ) {
                    if ( $user->HasPermission( PERMISSION_COMMENT_CREATE ) ) {
                        Element( 'comment/reply', $image->Id, TYPE_IMAGE );
                    }
                    if ( $image->Numcomments > 0 ) {
                        $finder = New CommentFinder();
                        if ( $commentid == 0 ) {
                            $comments = $finder->FindByPage( $image , $pageno , true );
                            $total_pages = $comments[ 0 ];
                            $comments = $comments[ 1 ];
                        }
                        else {
                            $speccomment = New Comment( $commentid );
                            $comments = $finder->FindNear( $image , $speccomment );
                            $total_pages = $comments[ 0 ];
                            $pageno = $comments[ 1 ];
                            $comments = $comments[ 2 ];
                            $finder = New NotificationFinder();
                            $finder->DeleteByCommentAndUser( $speccomment, $user );
                        }
                        Element( 'comment/list' , $comments );
                        ?><div class="pagifycomments"><?php
                            $link = '?p=photo&id=' . $image->Id . '&pageno=';
                            Element( 'pagify', $pageno, $link, $total_pages );
                        ?></div><?php
                    }
                }
                ?></div>
            </div><div class="eof"></div><?php
        }
    }
?>
