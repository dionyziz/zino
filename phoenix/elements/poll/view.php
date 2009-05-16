<?php
    class ElementPollView extends Element {
        function Render( tInteger $id , tInteger $commentid , tInteger $pageno, tText $subdomain, tText $url ) {
            global $page;
            global $libs;
            global $water;
            global $rabbit_settings;
            global $user;
            
            $libs->Load( 'poll/poll' );
            $libs->Load( 'comment' );
            $libs->Load( 'notify' );
            $libs->Load( 'favourite' );

            Element( 'user/subdomainmatch' );

            if ( $subdomain->Exists() && $url->Exists() ) {
                $subdomain = $subdomain->Get();
                $url = $url->Get();
                $finder = New UserFinder();
                $owner = $finder->FindBySubdomain( $subdomain );
                $finder = New PollFinder();
                $poll = $finder->FindByUserAndUrl( $owner, $url );
            }
            else {
                $poll = New Poll( $id->Get() );
            }
            if ( $poll === false || !$poll->Exists() ) {
                return Element( '404', 'Δεν βρέθηκε η δημοσκόπηση' );
            }
            $commentid = $commentid->Get();
            $pageno = $pageno->Get();

            $finder = New FavouriteFinder();
            $fav = $finder->FindByUserAndEntity( $user, $poll );

            $finder = New PollVoteFinder();
            $showresults = $finder->FindByPollAndUser( $poll, $user );
            $theuser = $poll->User;

            if ( $pageno <= 0 ) {
                $pageno = 1;
            }
            Element( 'user/sections' , 'poll' , $poll->User );
            if ( $poll->IsDeleted() ) {
                $page->SetTitle( 'Η δημοσκόπηση έχει διαγραφεί' );
                ?>H δημοσκόπηση έχει διαγραφεί<?php
                return;
            }
            ?><div id="poview"><?php
            $page->SetTitle( $poll->Question );
                ?><h2><?php
                    echo htmlspecialchars( $poll->Question );
                ?></h2>
                <dl><?php
                    if ( $poll->Numcomments > 0 ) {
                        ?><dd class="commentsnum"><span class="s_commnum">&nbsp;</span><?php
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
                    ?><dd class="time"><span class="s_clock">&nbsp;</span><?php
                    Element( 'date/diff', $poll->Created );
                    ?></dd><?php
                ?></dl><?php
                if ( $user->Exists() ) {
                    ?><ul class="edit"><?php
                        if ( $user->Id == $theuser->Id || $user->HasPermission( PERMISSION_POLL_DELETE_ALL ) ) {
                            ?><li>
                                <a href="" onclick="return PollView.Delete( '<?php
                                echo $poll->Id;
                                ?>' )"><span class="s_delete">&nbsp;</span>Διαγραφή</a>
                            </li><?php
                        }
                    ?></ul><?php
                }
                ?><div>
                    <div class="posmall">
                        <div class="results"><?php
                        Element( 'poll/result/view', $poll, $showresults );
                        Element( 'poll/vote' );
                        ?></div>
                    </div>
                </div>
                <div class="eof"></div>
                <br />
                <div class="comments"><?php
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
                            $indentation = Element( 'comment/list' , $comments , TYPE_POLL , $poll->Id );
                            $page->AttachInlineScript( 'Comments.nowdate = "' . NowDate() . '";' );
                            $page->AttachInlineScript( "Comments.OnLoad();" );
                            if ( $commentid > 0 && isset( $indentation[ $commentid ] ) ) {
                                Element( 'comment/focus', $commentid, $indentation[ $commentid ] );
                            }
                            ?><div class="pagifycomments"><?php
                                $link = '?p=poll&id=' . $poll->Id . '&pageno=';
                                Element( 'pagify', $pageno, $link, $total_pages );
                            ?></div><?php
                        }
                    }
                ?></div>
            </div><div class="eof"></div><?php
        }
    }
?>
