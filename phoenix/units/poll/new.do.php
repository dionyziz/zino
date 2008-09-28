<?php
	
	function UnitPollNew( tText $question , tText $options , tCoalaPointer $node ) {
		global $user;
		global $libs;
		
		$libs->Load( 'poll/poll' );
		
		$question = $question->Get();
		$options = explode( "|" , $options->Get() );
		
		if ( !empty( $options ) && $question != '' ) {
			$poll = New Poll();
			$poll->Userid = $user->Id;
			$poll->Question = $question;
			$poll->Save();
			foreach( $options as $option ) {
				$polloption = New PollOption();
				$polloption->Text = $option;
				$polloption->Pollid = $poll->Id;
				$polloption->Save();
			}
			?>$( <?php
			echo $node;
			?> ).html( <?php
			ob_start();
			Element( 'poll/small' , $poll , true );
			echo w_json_encode( ob_get_clean() );
			?> );
			PollList.Cancel();<?php
		}
	}
?>
