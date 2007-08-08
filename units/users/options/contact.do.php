<?php

	function UnitUsersOptionsContact( tString $msn, tString $yim, tString $aim, tString $skype, tString $icq, tString $gtalk ) {
		global $user;
		
		$msn = $msn->Get();
		$yim = $yim->Get();
		$aim = $aim->Get();
		$skype = $skype->Get();
		$icq = $icq->Get();
		$gtalk = $gtalk->Get();
		
		UpdateUser( $user->Signature(), "", $user->Email(),  $user->Gender(), $user->DateOfBirthDay(), $user->Hobbies(), $user->Subtitle(), $user->Place(), $msn, $skype, $yim, $aim, $icq, $gtalk ,$user->Height(), $user->Weight(), $user->EyeColor(),$user->HairColor() );
	}

?>