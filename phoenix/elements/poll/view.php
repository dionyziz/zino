<?php
	
	function ElementPollView( tInteger $id ) {
		global $page;
		global $libs;
		global $water;
		
		$libs->Load( 'poll/poll' );
		
		$water->Trace( 'pollid is: ' . $id->Get() );
		$poll = New Poll( $id->Get() );
		$water->Trace( 'poll question is: ' . $poll->Question );
		$page->SetTitle( $poll->Question );
		Element( 'user/sections' , 'poll' , $poll->User );
		?><div id="pollview"><?php
			Element( 'poll/small' , $poll , false ); //don't show comments number
			?><div class="comments"><?php
				Element( 'comment/list' );
			?></div>
		</div>
		<div class="eof"></div><?php
	}
?>
