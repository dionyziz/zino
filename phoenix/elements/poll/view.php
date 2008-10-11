<?php
    class ElementPollView extends Element {
        function Render( tInteger $id , tInteger $commentid , tInteger $pageno ) {
                global $page;
                global $libs;
                global $water;
                global $rabbit_settings;
                global $user;
                
                $libs->Load( 'poll/poll' );
                $libs->Load( 'comment' );
                $libs->Load( 'notify' );
                $libs->Load( 'favourite' );

                $poll = New Poll( $id->Get() );
                $commentid = $commentid->Get();
                $pageno = $pageno->Get();

                $finder = New FavouriteFinder();
                $fav = $finder->FindByUserAndEntity( $user, $poll );

                $finder = New PollVoteFinder();
                $showresults = $finder->FindByPollAndUser( $poll, $user );
                
                if ( $poll->Exists() ) {
                    if ( $pageno <= 0 ) {
                        $pageno = 1;
                    }
                    Element( 'user/sections' , 'poll' , $poll->User );
                    if ( !$poll->IsDeleted() ) {
                        ?><div id="pollview"><?php
                        $page->SetTitle( $poll->Question );
                        ?><h2><?php
                            echo htmlspecialchars( $poll->Question );
                        ?></h2>
                        <dl class="toolbar"><?php
                            ?><dd class="createdate"><?php
                            Element( 'date/diff', $poll->Created );
                            ?></dd><?php
                            if ( $poll->Numcomments > 0 ) {
                                ?><dd class="commentsnum"><span>&nbsp;</span><?php
                                echo $poll->Numcomments;
                                ?> σχόλι<?php
                                if ( $poll->Numcomments == 1 ) {
                                    ?>ο<?php
                                }
                                else {
                                    ?>α<?php
                                }
                                ?></dd><?php
                            }
                            if ( ( $poll->User->Id == $user->Id && $user->HasPermission( PERMISSION_POLL_DELETE ) ) || $user->HasPermission( PERMISSION_POLL_DELETE_ALL ) ) {
                                ?><dd class="delete">
                                    <a href="" onclick="PollView.Delete( '<?php
                                    echo $poll->Id;
                                    ?>' );return false"><span>&nbsp;</span>Διαγραφή
                                    </a>
                                </dd><?php
                            }
                            ?></dl><div><div class="pollsmall">
                                <div class="results"><?php
                                Element( 'poll/result/view', $poll, $showresults );
                                Element( 'poll/vote' );
                                ?></div>
                            </div>
                            </div>
                            <div class="eof"></div>
                            <br /><?php
                            Element( 'ad/view', AD_POLL, $page->XMLStrict() );
                            ?><div class="comments"><?php
                                if ( $user->HasPermission( PERMISSION_COMMENT_VIEW ) ) {
                                    if ( $user->HasPermission( PERMISSION_COMMENT_CREATE ) ) {
                                        Element( 'comment/reply', $poll->Id, TYPE_POLL , $user->Id , $user->Avatar->Id );
                                    }
                                    if ( $poll->Numcomments > 0 ) {
                                        $finder = New CommentFinder();
                                        if ( $commentid == 0 ) {
                                            $comments = $finder->FindByPage( $poll , $pageno , true );
                                            $total_pages = $comments[ 0 ];
                                            $comments = $comments[ 1 ];
                                        }
                                        else {
                                            $speccomment = New Comment( $commentid );
                                            $comments = $finder->FindNear( $poll , $speccomment );
                                            $total_pages = $comments[ 0 ];
                                            $pageno = $comments[ 1 ];
                                            $comments = $comments[ 2 ];
                                            $finder = New NotificationFinder();
                                            $finder->DeleteByCommentAndUser( $speccomment, $user );
                                        }
                                        $page->AttachInlineScript( 'var nowdate = "' . NowDate() . '";' );
                                        Element( 'comment/list' , $comments );
                                        ?><div class="pagifycomments"><?php
                                            $link = '?p=poll&id=' . $poll->Id . '&pageno=';
                                            Element( 'pagify', $pageno, $link, $total_pages );
                                        ?></div><?php
                                    }
                                }
                            ?></div>
                        </div><?php
                    }
                    else {
                        $page->SetTitle( 'Η δημοσκόπηση έχει διαγραφεί' );
                        ?>H δημοσκόπηση έχει διαγραφεί<?php
                    }
                }
                else {
                    ?>Η δημοσκόπηση δεν υπάρχει<?php
                }
                ?><div class="eof"></div><?php
        }
    }
?>
