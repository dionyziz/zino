<?php
    
    class ElementJournalView extends Element {
        public function Render( tInteger $id , tInteger $commentid , tInteger $pageno ) {
            global $page;
            global $rabbit_settings;
            global $user;
            global $libs;
            
            $libs->Load( 'comment' );
            $libs->Load( 'favourite' );
            $libs->Load( 'notify' );
            $journal = New Journal( $id->Get() );
            $commentid = $commentid->Get();
            $pageno = $pageno->Get();
            $finder = New FavouriteFinder();
            $fav = $finder->FindByUserAndEntity( $user, $journal );
            
            if ( $journal->Exists() ) {
                if ( $pageno <= 0 ) {
                    $pageno = 1;
                }
                Element( 'user/sections' , 'journal' , $journal->User );
                ?><div id="journalview"><?php
                if ( !$journal->IsDeleted() ) {
                    $page->SetTitle( $journal->Title );
                    ?><h2><?php
                    echo htmlspecialchars( $journal->Title );
                    ?></h2>
                    <div class="journal" style="clear:none">    
                        <dl><?php
                            ?><dd class="createdate"><?php
                            Element( 'date/diff', $journal->Created );
                            ?></dd><?php
                            if ( $journal->Numcomments > 0 ) {
                                ?><dd class="commentsnum"><span>&nbsp;</span><?php
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
                            if ( $journal->User->Id != $user->Id ) {
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
                                ?>" onclick="JournalView.AddFav( '<?php
                                echo $journal->Id;
                                ?>' , this );return false"><?php
                                if ( !$fav ) {
                                    ?>Προσθήκη στα αγαπημένα<?php
                                }
                                ?></a></dd><?php
                            }
                            ?></dl><?php
                            if ( $journal->User->Id == $user->Id || $user->HasPermission( PERMISSION_JOURNAL_DELETE_ALL ) ) {
                                ?><div class="owner">
                                    <div class="edit">
                                        <a href="?p=addjournal&amp;id=<?php
                                        echo $journal->Id;
                                        ?>"><span>&nbsp;</span>Επεξεργασία</a>
                                    </div>
                                    <div class="delete">
                                        <a href="" onclick="JournalView.Delete( '<?php
                                        echo $journal->Id;
                                        ?>' );return false"><span>&nbsp;</span>Διαγραφή
                                        </a>
                                    </div>                        
                                </div><?php
                            }
                        ?><div class="eof"></div>
                        <p><?php
                        echo $journal->Text; //purposely not formatted
                        ?></p>
                    </div><?php
                    Element( 'ad/view', AD_JOURNAL, $page->XMLStrict() ); 
                    ?><div class="comments"><?php
                    if ( $user->HasPermission( PERMISSION_COMMENT_VIEW ) ) {
                        if ( $user->HasPermission( PERMISSION_COMMENT_CREATE ) ) {
                            Element( 'comment/reply', $journal->Id, TYPE_JOURNAL , $user->Id , $user->Avatar->Id );
                        }
                        if ( $journal->Numcomments > 0 ) {
                            $finder = New CommentFinder();
                            if ( $commentid == 0 ) {
                                $comments = $finder->FindByPage( $journal , $pageno , true );
                                $total_pages = $comments[ 0 ];
                                $comments = $comments[ 1 ];
                            }
                            else {
                                $speccomment = New Comment( $commentid );
                                $comments = $finder->FindNear( $journal , $speccomment );
                                $total_pages = $comments[ 0 ];
                                $pageno = $comments[ 1 ];
                                $comments = $comments[ 2 ];
                                $finder = New NotificationFinder();
                                $finder->DeleteByCommentAndUser( $speccomment, $user );
                            }
                            Element( 'comment/list' , $comments );
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
