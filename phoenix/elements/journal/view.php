<?php
    
    class ElementJournalView extends Element {
        public function Render( tInteger $id, tInteger $commentid, tInteger $pageno, tText $subdomain, tText $url ) {
            global $page;
            global $user;
            global $libs;
            global $rabbit_settings;
			
            $libs->Load( 'comment' );
            $libs->Load( 'favourite' );
            $libs->Load( 'notify' );
            $libs->Load( 'user/user' );
            $libs->Load( 'journal' );

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
                if ( $pageno <= 0 ) {
                    $pageno = 1;
                }
                Element( 'user/sections', 'journal', $journal->User );
                ?><div id="journalview"><?php
                if ( !$journal->IsDeleted() ) {
                    $page->SetTitle( $journal->Title );
                    ?><h2><?php
                    echo htmlspecialchars( $journal->Title );
                    ?></h2>
                    <div class="journal" style="clear:none">    
                        <dl><?php
		                    if ( $journal->Numcomments > 0 ) {
		                        ?><dd class="commentsnum"><span class="s_commnum">&nbsp;</span><?php
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
							?><dd class="time"><span class="s_clock">&nbsp;</span><?php
							Element( 'date/diff', $journal->Created );
							?></dd>
						</dl><?php
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
									?>" onclick="return JournalView.AddFav( '<?php
									echo $journal->Id;
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
										?>" onclick="return JournalView.AddFav( '<?php
										echo $journal->Id;
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
										<a href="?p=addjournal&amp;id=<?php
										echo $journal->Id;
                                        ?>"><span class="s_edit">&nbsp;</span>Επεξεργασία
										</a>
									</li><?php
								}
								?><li>
									<a href="" onclick="return JournalView.Delete( '<?php
									echo $journal->Id;
									?>' )"><span class="s_delete">&nbsp;</span>Διαγραφή</a>
								</li><?php
							}
							?></ul><?php
						}
						?>
						<div class="eof"></div>
                        <p><?php
                        echo $journal->Text; // purposely not formatted
                        ?></p>
                    </div>
					<div class="eof"></div>
                    <div class="comments"><?php
                    if ( $user->HasPermission( PERMISSION_COMMENT_VIEW ) ) {
                        if ( $user->HasPermission( PERMISSION_COMMENT_CREATE ) ) {
                            Element( 'comment/reply', $journal->Id, TYPE_JOURNAL, $user->Id, $user->Avatar->Id );
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
                                $finder = New NotificationFinder();
                                $finder->DeleteByCommentAndUser( $speccomment, $user );
                            }       
                            $page->AttachInlineScript( 'var Comments.nowdate = "' . NowDate() . '";' );
                            $indentation = Element( 'comment/list', $comments, TYPE_JOURNAL, $journal->Id );
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
                $page->SetTitle( "Η καταχώρηση δεν υπάρχει" );
                ?>Η καταχώρηση δεν υπάρχει<?php
            }
            ?><div class="eof"></div><?php
        }
    }
?>
