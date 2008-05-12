<?php

	function UnitPollVote( tInteger $optionid , tInteger $pollid ) {
		global $libs;
		global $user;
		
		$libs->Load( 'poll/poll' );
		
		$optionid = $optionid->Get();
		$pollid = $pollid->Get();
		?>alert( 'option id is: <?php echo $optionid; ?>' );
		alert( 'poll id is: <?php echo $pollid; ?>' );<?php
		$vote = New PollVote();
		$vote->Userid = $user->Id;
		$vote->Optionid = $optionid;
		$vote->Pollid = $pollid;
		if ( !$vote->Exists() ){
			$vote->Save();
		}
	}
?>
