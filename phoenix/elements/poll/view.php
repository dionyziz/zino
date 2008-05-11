<?php
	
	function ElementPollView( tInteger $pollid ) {
		global $page;
		global $libs;
		
		$libs->Load( 'poll/poll' );
		
		//$page->AttachStyleSheet( 'css/poll/view.css' );
		$poll = New Poll( $pollid->Get() );
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
