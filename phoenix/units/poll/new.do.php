<?php
	
	function UnitPollNew( tString $question , tString $options , tCoalaPointer $node ) {
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
			$polloption->Pollid = $poll->Id;
			$polloption->Save();
		}
		?>$( <?php
		echo $node;
		?> ).html( <?php
		ob_start();
    	Element( 'poll/small' , $poll , true );
    	echo w_json_encode( ob_get_clean() );
    	?> );<?php
	}
?>
