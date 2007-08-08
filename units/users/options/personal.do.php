<?php

	function UnitUsersOptionsPersonal( tString $gender ) {
		global $user;
		
		$gender = $gender->Get();
		
		UpdateUser( $user->Signature(), "", $user->Email(),  $gender, $user->DateOfBirthDay(), $user->Hobbies(), $user->Subtitle(), $user->Place(), $user->MSN(), $user->Skype(), $user->YIM(), $user->AIM(), $user->ICQ(), $user->Gtalk() ,$user->Height(), $user->Weight(), $user->EyeColor(),$user->HairColor() );
	}

?>