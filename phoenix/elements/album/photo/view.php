<?php
    
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
            $libs->Load( 'image/tag' );
            
            $id = $id->Get();
            $commentid = $commentid->Get();
            $pageno = $pageno->Get();
            $image = New Image( $id );
            
            $relfinder = New FriendRelationFinder();
            if ( $user->HasPermission( PERMISSION_TAG_CREATE ) ) {
                $mutual = $relfinder->FindMutualByUser( $user );
                $jsarr = "Tag.friends = [ ";
				$jsarr2 = "Tag.genders = [ ";
                foreach( $mutual as $mutual_friend ) {
                    $jsarr .= "'" . $mutual_friend[ 'user_name' ] . "', ";
					$jsarr2 .= "'" . $mutual_friend[ 'user_gender'] . "', ";
                }
                $jsarr .= "'" . $user->Name . "'";
				$jsarr2 .= "'" . $user->Gender . "' ];";
                $jsarr .= " ];Tag.photoid = " . $id . ";";
                
                $page->AttachInlineScript( $jsarr . $jsarr2 );
				
            }
            
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
                $title = $image->Name;
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
                    $title = $image->Album->Name;
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
                        ?>' , this );return false"><?php
                        if ( !$fav ) {
                            ?>Προσθήκη στα αγαπημένα<?php
                        }
                        ?></a></dd><?php
                    }

                    if ( $user->HasPermission( PERMISSION_TAG_CREATE )
                        && ( $image->User->Id == $user->Id || 
                             $relfinder->IsFriend( $image->User, $user ) == FRIENDS_BOTH )
                        && $image->Width > 45 && $image->Height > 45 ) {
                        ?><dd class="addtag"><a href="" title="Ποιος είναι στην φωτογραφία" onclick="Tag.start( false, '', true );return false">Γνωρίζεις κάποιον;</a></dd><?php
                    }
                 ?></dl><?php
                if ( $image->User->Id == $user->Id || $user->HasPermission( PERMISSION_IMAGE_DELETE_ALL ) ) {
                    ?><div class="owner">
                        <div class="edit"><a href="" onclick="PhotoView.Rename( '<?php
                        echo $image->Id;
                        ?>' , <?php
                        echo htmlspecialchars( w_json_encode( $image->Album->Name ) );
                        ?> );return false"><?php
                        if ( $image->Name == '' ) {
                            ?>Όρισε όνομα<?php
                        }
                        else {
                            ?>Μετονομασία<?php
                        }
                        ?></a></div>
                        <div class="delete"><a href="" onclick="PhotoView.Delete( '<?php
                        echo $image->Id;
                        ?>' );return false">Διαγραφή</a></div><?php
                        if ( $image->Album->Mainimageid != $image->Id ) {
                            ?><div class="mainimage"><a href="" onclick="PhotoView.MainImage( '<?php
                            echo $image->Id;
                            ?>' );return false">
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
                                    Element( 'image/view' , $photos[ $i ]->Id , $photos[ $i ]->User->Id , $photos[ $i ]->Width , $photos[ $i ]->Height  , IMAGE_CROPPED_100x100 , '' , $photos[ $i ]->Name , '' , false , 0 , 0 );
                                    ?></a></span></li><?php
                                }
                            }
                            ?><li class="selected"><?php
                                Element( 'image/view' , $photos[ $pivot ]->Id , $photos[ $pivot ]->User->Id , $photos[ $pivot ]->Width , $photos[ $pivot ]->Height , IMAGE_CROPPED_100x100, '' , $photos[ $pivot ]->Name , '' , false , 0 , 0 );
                            ?></li><?php
                            if ( $pivot < 12 ) {                        
                                for ( $i = $pivot + 1; $i < count( $photos ); ++$i ) {
                                    ?><li><span><a href="?p=photo&amp;id=<?php
                                    echo $photos[ $i ]->Id;
                                    ?>"><?php
                                    Element( 'image/view' , $photos[ $i ]->Id , $photos[ $i ]->User->Id , $photos[ $i ]->Width , $photos[ $i ]->Height  , IMAGE_CROPPED_100x100 , '' , $photos[ $i ]->Name , '' , false , 0 , 0 );
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
                ?>px;" onmousedown="Tag.katoPontike( event );return false" onmouseup="Tag.showSug( event );return false" onmouseout="Tag.ekso( event, true );return false" onmousemove="Tag.drag( event );return false"><?php
                    Element( 'image/view' , $image->Id , $image->User->Id , $image->Width , $image->Height , IMAGE_FULLVIEW, '' , $title , '' , false , 0 , 0 );
                    if ( $image->Width > 45 && $image->Height > 45 ) {
                        ?><div class="tanga"><?php
                            $tagfinder = New ImageTagFinder();
                            $tags = $tagfinder->FindByImage( $image );
                            $tags_num = count( $tags );
                            $unames = array();
                            foreach( $tags as $tag ) {
                                $person = New User( $tag->Personid );
                                $person_name = $person->Name;
                                $unames[] = $person;
                                ?><div class="tag" style="left:<?php
                                echo $tag->Left;
                                ?>px;top:<?php
                                echo $tag->Top;
                                ?>px;width:<?php
								echo $tag->Width;
								?>px;height:<?php
								echo $tag->Height;
								?>px;" onclick="document.location.href='http://<?php
                                echo $person->Subdomain;
                                ?>.zino.gr';">
                                <div><?php
                                echo $person_name;
                                ?></div>
                                </div><?php
                            }
                        ?></div>
                        <div class="tagme"<?php
						if ( $image->Height < 170 || $image->Width < 170 ) {
							?> style="height:45px;width:45px;"<?php
						}
						?>>
							<div class="resizer" onmousedown="Tag.resize_down( event );return false" onmouseout="Tag.ekso( event, true );return false"></div>
						</div>
                        <div class="frienders">
                            <div>Ποιός είναι αυτός;</div>
                            <form action="" onsubmit="return false">
                                <input type="text" value="" onmousedown="Tag.focusInput( event );" onkeydown="Tag.autocomplete( event );" onkeyup="Tag.filterSug( event );" />
                            </form>
                            <ul onmousedown="Tag.ekso( event );return false">
                                <li></li>
                            </ul>
                            <div class="closer">
                                <a href="" class="button" onmousedown="Tag.close();return false">Ακύρωση</a>
                            </div>
                        </div><?php
                    }
                ?></div><?php
                if ( $image->Width > 45 && $image->Height > 45 ) {
                    ?>
					<div class="messageboxer"></div>
					<div class="image_tags" <?php
                    if ( $tags_num == 0 ) {
                        ?>style="display:none"<?php
                    }
                    ?>>Σε αυτή την φωτογραφία είναι <?php
					$jsarr2 = "Tag.genders = [ ";
                    for( $i=0; $i<$tags_num; ++$i ) {
                        ?><div><?php
						if ( $unames[ $i ]->Gender == 'f' ) {
							?>η <?php
						}
						else {
							?>o <?php
						}
						?><a href="http://<?php
                        echo $unames[ $i ]->Subdomain;
                        ?>.zino.gr" title="<?php
                        echo $unames[ $i ]->Name;
                        ?>"><?php
                        echo $unames[ $i ]->Name;
                        ?></a><?php
                        if ( $tags[ $i ]->Ownerid == $user->Id || $image->User->Id == $user->Id ) {
                            ?><a class="tag_del" href="" onclick="Tag.del( <?php
                            echo $tags[ $i ]->Id;
                            ?>, '<?php
                            echo $unames[ $i ]->Name;
                            ?>' );return false" title="Διαγραφή"> </a><?php // Space needed for CSS Spriting
                        }
						if ( $i == $tags_num - 2 ) {
							?> και <?php
						}
						else if ( $i != $tags_num - 1 ) {
							?>, <?php
						}
                        ?></div><?php
                    }
                    ?></div><?php
                }
                
                Element( 'ad/view', AD_PHOTO, $page->XMLStrict() );

                ?>
				<div class="comments"><?php
                if ( $user->HasPermission( PERMISSION_COMMENT_VIEW ) ) {
                    if ( $user->HasPermission( PERMISSION_COMMENT_CREATE ) ) {
                        Element( 'comment/reply', $image->Id, TYPE_IMAGE , $user->Id , $user->Avatar->Id );
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
