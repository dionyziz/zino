<?php
	
	function ElementPollView( tInteger $id ) {
		global $page;
		global $libs;
		global $water;
		global $rabbit_settings;
		global $user;
		
		$libs->Load( 'poll/poll' );
		
		$poll = New Poll( $id->Get() );
		
		if ( $poll->Exists() ) {
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
						//Element( 'comment/list' );
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
