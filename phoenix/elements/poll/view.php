<?php
	
	function ElementPollView( tInteger $id ) {
		global $page;
		global $libs;
		global $water;
		global $rabbit_settings;
		
		$libs->Load( 'poll/poll' );
		
		$poll = New Poll( $id->Get() );
		Element( 'user/sections' , 'poll' , $poll->User );
		$water->Trace( 'poll delid is : ' . $poll->Delid );
		if ( !$poll->IsDeleted() ) {
			$page->SetTitle( $poll->Question );
			?><div id="pollview">
				<div><?php
				Element( 'poll/small' , $poll , false ); //don't show comments number
				?>
				</div>
				<div class="eof"></div>
				<div class="delete">
					<a href="" onclick="PollView.Delete( '<?php
					echo $poll->Id;
					?>' );return false;">Διαγραφή
					</a>
				</div>
				<div class="comments"><?php
					Element( 'comment/list' );
				?></div>
			</div><?php
		}
		else {
			$page->SetTitle( 'Η δημοσκόπηση έχει διαγραφεί' );
			?>H δημοσκόπηση έχει διαγραφεί<?php
		}
		?><div class="eof"></div><?php
	}
?>
