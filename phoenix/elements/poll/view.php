<?php
	
	function ElementPollView( tInteger $id , tInteger $commentid , tInteger $pageno ) {
		global $page;
		global $libs;
		global $water;
		global $rabbit_settings;
		global $user;
		
		$libs->Load( 'poll/poll' );
		$libs->Load( 'comment' );
		$libs->Load( 'notify' );
		$poll = New Poll( $id->Get() );
		$commentid = $commentid->Get();
		$pageno = $pageno->Get();
		
		if ( $poll->Exists() ) {
			if ( $pageno <= 0 ) {
				$pageno = 1;
			}
			Element( 'user/sections' , 'poll' , $poll->User );
			if ( !$poll->IsDeleted() ) {
				$page->SetTitle( $poll->Question );
				?><div id="pollview" style="width:500px;">
					<div><?php
					Element( 'poll/small' , $poll , false ); //don't show comments number
					?>
					</div>
					<div class="eof"></div><?php
					if ( ( $poll->User->Id == $user->Id && $user->HasPermission( PERMISSION_POLL_DELETE ) ) || $user->HasPermission( PERMISSION_POLL_DELETE_ALL ) ) {
						?><div class="delete">
							<a href="" onclick="PollView.Delete( '<?php
							echo $poll->Id;
							?>' );return false;">Διαγραφή
							</a>
						</div><?php
					}
					?><div class="comments"><?php
                        if ( $user->HasPermission( PERMISSION_COMMENT_VIEW ) ) {
                            if ( $user->HasPermission( PERMISSION_COMMENT_CREATE ) ) {
                                Element( 'comment/reply', $poll->Id, TYPE_POLL );
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
                                    $notification = $finder->FindByComment( $speccomment );
                                    if ( $notification ) {
                                        $notification->Delete();
                                    }
                                }
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
?>
