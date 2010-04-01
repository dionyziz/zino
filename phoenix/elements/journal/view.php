<?php
    class ElementJournalView extends Element {
        public function Render( tInteger $id, tInteger $commentid, tInteger $pageno, tText $subdomain, tText $url ) {
            global $page;
            global $user;
            global $libs;
            global $rabbit_settings;
			
            $libs->Load( 'comment' );
            $libs->Load( 'favourite' );
            $libs->Load( 'user/user' );
            $libs->Load( 'journal/journal' );

            Element( 'user/subdomainmatch' );

            if ( $subdomain->Exists() && $url->Exists() ) {
                $subdomain = $subdomain->Get();
                $url = $url->Get();
                $finder = New UserFinder();
                $owner = $finder->FindBySubdomain( $subdomain );
                $finder = New JournalFinder();
                $journal = $finder->FindByUserAndUrl( $owner, $url );
            }
            else {
                $journal = New Journal( $id->Get() );
            }

            if ( $journal !== false ) {
                $commentid = $commentid->Get();
                $pageno = $pageno->Get();
                $finder = New FavouriteFinder();
                $fav = $finder->FindByUserAndEntity( $user, $journal );
                $theuser = $journal->User;
                if ( $theuser->Deleted ) {
                    $libs->Load( 'rabbit/helpers/http' );
                    return Redirect( 'http://static.zino.gr/phoenix/deleted' );
                }
                if ( Ban::isBannedUser( $theuser->Id ) ) {
                    $libs->Load( 'rabbit/helpers/http' );
                    return Redirect( 'http://static.zino.gr/phoenix/banned' );
                }
                if ( $pageno <= 0 ) {
                    $pageno = 1;
                }
                Element( 'user/sections', 'journal', $journal->User );
                ?><div id="journalview"><?php
                if ( !$journal->IsDeleted() ) {
                    $page->SetTitle( $journal->Title );
                    ?><div class="objectinfo"><h2 class="subheading"><?php
                    echo htmlspecialchars( $journal->Title );
                    ?></h2>
                    <div class="journal" style="clear:none">    
                        <dl><?php
		                    if ( $journal->Numcomments > 0 ) {
		                        ?><dd class="commentsnum small"><span class="s1_0027">&nbsp;</span><?php
		                        echo $journal->Numcomments;
		                        ?> σχόλι<?php
		                        if ( $journal->Numcomments == 1 ) {
		                            ?>ο<?php
		                        }
		                        else {
		                            ?>α<?php
		                        }
		                        ?></dd><?php
		                    }
							?><dd class="time small"><span class="s1_0035">&nbsp;</span><?php
							Element( 'date/diff', $journal->Created );
							?></dd>
						</dl><?php
						if ( $user->Exists() ) {
							?><ul class="edit"><?php
							if ( $user->Id != $theuser->Id && !$user->HasPermission( PERMISSION_JOURNAL_DELETE_ALL ) ) {
								?><li>
									<a href="" title="<?php
									if ( !$fav ) {
										?>Το αγαπώ<?php
									} 
									else {
										?>Αγαπημένο<?php
									}
									?>" onclick="return Favourites.Add( '<?php
									echo $journal->Id;
									?>' , 'Journal' , this )"><span class="<?php
									if ( !$fav ) {
										?>s1_0019<?php
									}
									else {
										?>s1_0020<?php
									}
									?>">&nbsp;</span><?php
									if ( !$fav ) {
										?>Το αγαπώ<?php
									}
									?></a>
								</li><?php
							}
							else {
								if ( $user->Id != $theuser->Id ) {
									?><li>
										<a href="" title="<?php
										if ( !$fav ) {
											?>Το αγαπώ<?php
										} 
										else {
											?>Αγαπημένο<?php
										}
										?>" onclick="return Favourites.Add( '<?php
										echo $journal->Id;
										?>' , 'Journal' , this )"><span class="<?php
										if ( !$fav ) {
											?>s1_0019<?php
										}
										else {
											?>s1_0020<?php
										}
										?>">&nbsp;</span><?php
										if ( !$fav ) {
											?>Το αγαπώ<?php
										}
										?></a>
									</li><?php
								}
								if ( $user->Id == $theuser->Id ) {
									?><li>
										<a href="?p=addjournal&amp;id=<?php
										echo $journal->Id;
                                        ?>"><span class="s1_0011">&nbsp;</span>Επεξεργασία
										</a>
									</li><?php
								}
								?><li>
									<a href="" onclick="return JournalView.Delete( '<?php
									echo $journal->Id;
									?>' )"><span class="s1_0007">&nbsp;</span>Διαγραφή</a>
								</li><?php
							}
							?></ul><?php
						}
						?>
						</div><div class="eof"></div>
                        <p><?php
                        echo $journal->Text; // purposely not formatted
                        ?></p>
                    </div>
					<div class="eof"></div>
                    <div class="comments"><?php
                    if ( $user->HasPermission( PERMISSION_COMMENT_VIEW ) ) {
                        if ( $user->HasPermission( PERMISSION_COMMENT_CREATE ) ) {
                            Element( 'comment/reply', $journal->Id, TYPE_JOURNAL, $user->Id, $user->Avatarid );
                        }
                        if ( $journal->Numcomments > 0 ) {
                            $finder = New CommentFinder();
                            if ( $commentid == 0 ) {
                                $comments = $finder->FindByPage( $journal, $pageno, true );
                                $total_pages = $comments[ 0 ];
                                $comments = $comments[ 1 ];
                            }
                            else {
                                $speccomment = New Comment( $commentid );
                                $comments = $finder->FindNear( $journal, $speccomment );
                                $total_pages = $comments[ 0 ];
                                $pageno = $comments[ 1 ];
                                $comments = $comments[ 2 ];
                                
                                $libs->Load( 'notify/notify' );
                                $finder = New NotificationFinder();
                                $finder->DeleteByCommentAndUser( $speccomment, $user );
                            }       
                            $indentation = Element( 'comment/list', $comments, TYPE_JOURNAL, $journal->Id );
                            $page->AttachInlineScript( 'Comments.nowdate = "' . NowDate() . '";' );
                            $page->AttachInlineScript( "Comments.OnLoad();" );
                            if ( $commentid > 0 && isset( $indentation[ $commentid ] ) ) {
                                Element( 'comment/focus', $commentid, $indentation[ $commentid ] );
                            }
                            ?><div class="pagifycomments"><?php
                                $link = '?p=journal&id=' . $journal->Id . '&pageno=';
                                Element( 'pagify', $pageno, $link, $total_pages );
                            ?></div><?php
                        }
                    }
                    ?></div><?php
                }
                else {
                    $page->SetTitle( "Η καταχώρηση έχει διαγραφεί" );
                    ?>Η καταχώρηση έχει διαγραφεί<?php
                }
                ?></div><?php
            }
            else {
                return Element( '404', "Η καταχώρηση δεν υπάρχει" );
            }
            ?><div class="eof"></div><?php
            //$page->AttachInlineScript( "JournalView.OnLoad();" );
        }
    }
?>
