<?php
	
	function UnitPollNew( tString $question , tString $options ) {
		global $user;
		global $libs;
		
		$libs->Load( 'poll/poll' );
		
		$poll = New Poll();
		$poll->Userid = $user->Id;
		$poll->Question = $question->Get();
		$poll->Save();

		$options = explode( "|" , $options->Get() );

		foreach( $options as $option ) {
			$polloption = New PollOption();
			$polloption->Text = $option;
			$option->Pollid = $poll->Id;
			$option->Save();
		}	
	}
?>
