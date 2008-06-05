<?php
	
	function ElementPollView( tInteger $id , tInteger $commentid , tInteger $offset ) {
		global $page;
		global $libs;
		global $water;
		global $rabbit_settings;
		global $xc_settings;
		global $user;
		
		$libs->Load( 'poll/poll' );
		$libs->Load( 'comment' );
		
		$poll = New Poll( $id->Get() );
		$commentid = $commentid->Get();
		$offset = $offset->Get();
		
		if ( $poll->Exists() ) {
			if ( $offset <= 0 ) {
				$offset = 1;
			}
			Element( 'user/sections' , 'poll' , $poll->User );
			if ( !$poll->IsDeleted() ) {
				$page->SetTitle( $poll->Question );
				?><div id="pollview">
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
						if ( $user->HasPermission( PERMISSION_COMMENT_CREATE ) || $xc_settings[ 'anonymouscomments' ] ) {
							Element( 'comment/reply', $poll->Id, TYPE_POLL );
						}
						if ( $poll->Numcomments > 0 ) {
							$finder = New CommentFinder();
							if ( $commentid == 0 ) {
								$comments = $finder->FindByPage( $poll , $offset , true );
							}
							else {
								$speccomment = New Comment( $commentid );
								$comments = $finder->FindNear( $poll , $speccomment );
								$offset = $comments[ 0 ];
								$comments = $comments[ 1 ];
							}
							Element( 'comment/list' , $comments , 0 , 0 );
							?><div class="pagifycomments"><?php
								Element( 'pagify' , $offset , 'poll&id=' . $poll->Id , $poll->Numcomments , 50 , 'offset' );
							?></div><?php
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
