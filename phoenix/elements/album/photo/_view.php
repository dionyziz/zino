<?php
    class ElementAlbumPhotoView extends Element {
        public function Render( tInteger $id , tInteger $commentid , tInteger $pageno ) {
            global $user;
            global $page;
            global $libs;
            global $water;
            global $rabbit_settings;
            
            $libs->Load( 'comment' );
            $libs->Load( 'favourite' );
            $libs->Load( 'notify' );
            $libs->Load( 'relation/relation' );
            $libs->Load( 'image/tag' );
            
            $id = $id->Get();
            $commentid = $commentid->Get();
            $pageno = $pageno->Get();
            $image = New Image( $id );
            $theuser = $image->User;
			
            if ( $user->HasPermission( PERMISSION_TAG_CREATE ) ) {
                $jsarr = "Tag.photoid = " . $id . ";";
                $page->AttachInlineScript( $jsarr );
				
            }
            
            if( !$image->Exists() ) {
                ?>Η φωτογραφία δεν υπάρχει<div class="eof"></div><?php
                return;
            }
            switch ( $image->Album->Ownertype ) {
                case TYPE_USERPROFILE:
                    Element( 'user/sections', 'album' , $theuser );
                    break;
                case TYPE_SCHOOL:
                    Element( 'school/info', $image->Album->Owner, true );
                    break;
            }
            if ( $image->IsDeleted() ) {
                ?>Η φωτογραφία έχει διαγραφεί<div class="eof"></div><?php
                return;
            }

            if ( $image->Name != "" ) {
                $title = $image->Name;
                $page->SetTitle( $title );
            }
            else {
                switch ( $image->Album->Ownertype ) {
                    case TYPE_USERPROFILE:
                        if ( $image->Album->Owner->Egoalbumid == $image->Album->Id ) {
                            if ( strtoupper( substr( $image->Album->Owner->Name, 0, 1 ) ) == substr( $image->Album->Owner->Name, 0, 1 ) ) {
                                $page->SetTitle( $image->Album->Owner->Name . " Φωτογραφίες" );
                            }
                            else {
                                $page->SetTitle( $image->Album->Owner->Name . " φωτογραφίες" );
                            }
                            $title = $theuser->Name;
                        }    
                        else {
                            $page->SetTitle( $image->Album->Name );
                            $title = $image->Album->Name;
                        }
                        break;
                    case TYPE_SCHOOL:
                        $page->SetTitle( $image->Album->Owner->Name );
                        $title = $image->Album->Owner->Name;
                        break;
                }
            }
            if ( $pageno <= 0 ) {
                $pageno = 1;
            }
            $finder = New FavouriteFinder();
            $fav = $finder->FindByUserAndEntity( $user, $image );
            ?><div id="pview">
                <h2><?php
                echo htmlspecialchars( $image->Name );
                ?></h2>
                <span>στο album</span> <a href="?p=album&amp;id=<?php
                echo $image->Album->Id;
                ?>"><?php
                switch ( $image->Album->Ownertype ) {
                    case TYPE_USERPROFILE:
                        if ( $image->Album->Id == $theuser->Egoalbumid ) {
                            ?>Εγώ<?php
                        }
                        else {
                            echo htmlspecialchars( $image->Album->Name );
                        }
                        break;
                    case TYPE_SCHOOL:
                        echo htmlspecialchars( $image->Album->Owner->Name );
                        break;
                }
                ?></a>
                <dl><?php
                    if ( $image->Numcomments > 0 ) {
                        ?><dd class="commentsnum"><span class="s_commnum">&nbsp;</span><?php
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
					?><dd class="time"><span class="s_clock">&nbsp;</span><?php
					Element( 'date/diff', $image->Created );
					?></dd><?php
                 ?></dl><?php
				 if ( $user->Exists() ) {
					?><ul class="edit"><?php
					if ( $user->Id != $theuser->Id && !$user->HasPermission( PERMISSION_JOURNAL_DELETE_ALL ) ) {
						?><li>
							<a href="" title="<?php
	                        if ( !$fav ) {
	                            ?>Προσθήκη στα αγαπημένα<?php
	                        } 
	                        else {
	                            ?>Αγαπημένο<?php
	                        }
	                        ?>" onclick="return PhotoView.AddFav( '<?php
	                        echo $image->Id;
	                        ?>' , this )"><span class="<?php
	                        if ( !$fav ) {
	                            ?>s_addfav<?php
	                        }
	                        else {
	                            ?>s_isaddedfav<?php
	                        }
							?>">&nbsp;</span><?php
	                        if ( !$fav ) {
	                            ?>Προσθήκη στα αγαπημένα<?php
	                        }
	                        ?></a>
						</li><?php
						$relfinder = New FriendRelationFinder();
						if ( $image->Album->Ownertype == TYPE_USERPROFILE 
                            && $user->HasPermission( PERMISSION_TAG_CREATE )
							&& ( $theuser->Id == $user->Id || 
                            $relfinder->IsFriend( $theuser, $user ) == FRIENDS_BOTH )
							&& $image->Width > 45 && $image->Height > 45 ) {
							?><li>
								<a href="" title="Ποιος είναι στην φωτογραφία" onclick="Tag.prestart( false, '', true );return false"><span class="s_addtag">&nbsp;</span>Γνωρίζεις κάποιον;</a>
							</li><?php
						}
					}
					else {
						if ( $user->Id != $theuser->Id ) {
							?><li>
								<a href="" title="<?php
		                        if ( !$fav ) {
		                            ?>Προσθήκη στα αγαπημένα<?php
		                        } 
		                        else {
		                            ?>Αγαπημένο<?php
		                        }
		                        ?>" onclick="return PhotoView.AddFav( '<?php
		                        echo $image->Id;
		                        ?>' , this )"><span class="<?php
		                        if ( !$fav ) {
		                            ?>s_addfav<?php
		                        }
		                        else {
		                            ?>s_isaddedfav<?php
		                        }
								?>">&nbsp;</span><?php
		                        if ( !$fav ) {
		                            ?>Προσθήκη στα αγαπημένα<?php
		                        }
		                        ?></a>
							</li><?php
						}
                        if ( $user->Id == $theuser->Id ) {
							?><li>
								<a href="" onclick="return PhotoView.Rename( '<?php
		                        echo $image->Id;
		                        ?>' , <?php
		                        echo htmlspecialchars( w_json_encode( $image->Album->Name ) );
		                        ?> )"><span class="s_edit">&nbsp;</span><?php
		                        if ( $image->Name == '' ) {
		                            ?>Όρισε όνομα<?php
		                        }
		                        else {
		                            ?>Μετονομασία<?php
		                        }
		                        ?></a>
							</li><?php
						}
						?><li>
							<a href="" onclick="return PhotoView.Delete( '<?php
	                        echo $image->Id;
	                        ?>' )"><span class="s_delete">&nbsp;</span>Διαγραφή</a>
						</li><?php
						$relfinder = New FriendRelationFinder();
						if ( $image->Album->Ownertype == TYPE_USERPROFILE 
                            && $user->HasPermission( PERMISSION_TAG_CREATE )
							&& ( $theuser->Id == $user->Id || 
                            $relfinder->IsFriend( $theuser, $user ) == FRIENDS_BOTH )
							&& $image->Width > 45 && $image->Height > 45 ) {
							?><li>
								<a href="" title="Ποιος είναι στην φωτογραφία" onclick="Tag.prestart( false, '', true );return false"><span class="s_addtag">&nbsp;</span>Γνωρίζεις κάποιον;</a>
							</li><?php
						}
						if ( $image->Album->Ownertype == TYPE_USERPROFILE && $user->Id == $theuser->Id ) {
							if ( $image->Album->Mainimageid != $image->Id ) {
	                            ?><li><a href="" onclick="return PhotoView.MainImage( '<?php
	                            echo $image->Id;
	                            ?>' , this )"><span class="s_mainimage">&nbsp;</span>Ορισμός προεπιλεγμένης</a>
	                            </li><?php
	                        }
						}
					}
					?></ul><?php
				}
                ?><div class="eof"></div><?php
                if ( $image->Album->Numphotos > 1 ) {
                    ?><div class="pthumbs plist"><?php
                        $finder = New ImageFinder();
                        $photos = $finder->FindAround( $image , 10 );
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
                                ?>" class="nav"><img src="<?php
                                echo $rabbit_settings[ 'imagesurl' ];
                                ?>previous.jpg" alt="Προηγούμενη" title="Προηγούμενη" class="hoverclass" /></a>
                            </div><?php
                        }
                        if ( $pivot + 1 < count( $photos ) && count( $photos ) > 1 ) {
                            ?><div class="right arrow">
                                <a href="?p=photo&amp;id=<?php
                                echo $photos[ $pivot + 1 ]->Id;
                                ?>" class="nav"><img src="<?php
                                echo $rabbit_settings[ 'imagesurl' ];
                                ?>next.jpg" alt="Επόμενη" title="Επόμενη" class="hoverclass" /></a>
                            </div><?php
                        }
                        ?><ul><?php    
                            if ( $pivot > 0 ) {
                                for ( $i = 0; $i < $pivot ; ++$i ) {
                                    ?><li><span><a href="?p=photo&amp;id=<?php
                                    echo $photos[ $i ]->Id;
                                    ?>"><?php
                                    Element( 'image/view' , $photos[ $i ]->Id , $photos[ $i ]->User->Id , $photos[ $i ]->Width , $photos[ $i ]->Height  , IMAGE_CROPPED_100x100 , '' , $photos[ $i ]->Name , '' , false , 0 , 0 , $photos[ $i ]->Numcomments );
                                    ?></a></span></li><?php
                                }
                            }
                            ?><li class="selected"><?php
                                Element( 'image/view' , $photos[ $pivot ]->Id , $photos[ $pivot ]->User->Id , $photos[ $pivot ]->Width , $photos[ $pivot ]->Height , IMAGE_CROPPED_100x100, '' , $photos[ $pivot ]->Name , '' , false , 0 , 0 , 0 );
                            ?></li><?php
                            if ( $pivot < 12 ) {                        
                                for ( $i = $pivot + 1; $i < count( $photos ); ++$i ) {
                                    ?><li><span><a href="?p=photo&amp;id=<?php
                                    echo $photos[ $i ]->Id;
                                    ?>"><?php
                                    Element( 'image/view' , $photos[ $i ]->Id , $photos[ $i ]->User->Id , $photos[ $i ]->Width , $photos[ $i ]->Height  , IMAGE_CROPPED_100x100 , '' , $photos[ $i ]->Name , '' , false , 0 , 0 , $photos[ $i ]->Numcomments );
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
                    Element( 'image/view' , $image->Id , $image->User->Id , $image->Width , $image->Height , IMAGE_FULLVIEW, '' , $title , '' , false , 0 , 0 , 0 );
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
							?> style="height:45px;width:45px"<?php
						}
						?>>
							<div class="resizer" onmousedown="Tag.resize_down( event );return false" onmouseout="Tag.ekso( event, true );return false"></div>
						</div>
                        <div class="frienders">
                            <div>Ποιός είναι αυτός;</div>
                            <form action="" onsubmit="return false">
                                <div>
                                    <input type="text" value="" onmousedown="Tag.focusInput( event );" onkeydown="Tag.autocomplete( event );" onkeyup="Tag.filterSug( event );" />
                                </div>
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
                ?><div class="image_tags" <?php
                    $favouritefinder = New FavouriteFinder();
                    $favourites = $favouritefinder->FindByEntity( $image );
                    if ( count( $favourites ) == 0 ) {
                        ?>style="display:none"<?php
                    }
                    ?>><?php
                        $i = 0;
                        if ( count( $favourites ) < 5 ) {
                            $size = count( $favourites );
                        }
                        else {
                            $size = 3;
                        }
                        for( $i = 0; $i < $size; ++$i ) {
                            ?><div><?php
                            if ( ( count( $favourites ) >= 5 ) && ( $i == $size - 1 ) ) {
                                echo count( $favourites ) - $size;
                                ?> άλλοι</div><?php
                                break;
                            }
                            if ( $favourites[ $i ]->User->Gender == 'f' ) {
                                ?>η <?php
                            }
                            else {
                                ?>ο <?php
                            }
                            Element( 'user/name', $favourites[ $i ]->User->Id, $favourites[ $i ]->User->Name, $favourites[ $i ]->User->Subdomain, true );
                            if ( $i == $size -2 ) {
                                ?> και <?php
                            }
                            else if( $i < $size -2 ) {
                                ?>, <?php
                            }
                            ?></div><?php
                        }
                        if ( count( $favourites ) == 1 ) {
                            ?> έχει<?php
                        }
                        else {
                            ?> έχουν<?php
                        }
                        ?> τη φωτογραφία στα αγαπημένα.</div>
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
                            if ( $comments === false ) { // no such comment
                                return Redirect( '?p=photo&id=' . $image->Id );
                            }
                            $total_pages = $comments[ 0 ];
                            $pageno = $comments[ 1 ];
                            $comments = $comments[ 2 ];
                            $finder = New NotificationFinder();
                            $finder->DeleteByCommentAndUser( $speccomment, $user );
                        }
                        $page->AttachInlineScript( 'var nowdate = "' . NowDate() . '";' );
                        $indentation = Element( 'comment/list' , $comments , TYPE_IMAGE , $image->Id );
                        if ( $commentid > 0 && isset( $indentation[ $commentid ] ) ) {
                            Element( 'comment/focus', $commentid, $indentation[ $commentid ] );
                        }
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
