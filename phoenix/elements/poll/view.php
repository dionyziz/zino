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
			?><div id="pollview"><?php
				Element( 'poll/small' , $poll , false ); //don't show comments number
				?>
				<div class="delete">
					<a href="" onclick="PollView.Delete( '<?php
					echo $poll->Id;
					?>' );return false;"><img src="<?php
					echo $rabbit_settings[ 'imagesurl' ];
					?>delete.png" alt="Διαγραφή" title="Διαγραφή" /> Διαγραφή δημοσκόπησης
					</a>
				</div>
				<div class="comments"><?php
					Element( 'comment/list' );
				?></div>
			</div><?php
		}
		else {
			?>H δημοσκόπηση έχει διαγραφεί<?php
		}
		?><div class="eof"></div><?php
	}
?>
