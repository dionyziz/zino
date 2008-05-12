<?php

	function UnitPollVote( tInteger $optionid , tInteger $pollid ) {
		global $libs;
		global $user;
		
		$libs->Load( 'poll/poll' );
		
		$optionid = $optionid->Get();
		$pollid = $pollid->Get();
		
		$vote = New PollVote();
		$vote->Userid = $user->Id;
		$vote->Optionid = $optionid;
		$vote->Pollid = $pollid;
		if ( !$vote->Exists() ){
			$vote->Save();
		}
	}
?>
