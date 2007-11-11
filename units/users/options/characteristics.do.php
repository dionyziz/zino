<?php

	function UnitUsersOptionsCharacteristics( tString $height, tString $weight, tString $eyecolor, tString $haircolor ) {
		global $user;
		
		$height = $height->Get();
		$weight = $weight->Get();
		$eyecolor = $eyecolor->Get();
		$haircolor = $haircolor->Get();
		
		UpdateUser( $user->Signature(), "", $user->Email(),  $user->Gender(), $user->DateOfBirthDay(), $user->Subtitle(), $user->Place(), $user->MSN(), $user->Skype(), $user->YIM(), $user->AIM(), $user->ICQ(), $user->Gtalk() ,$height, $weight, $eyecolor,$haircolor );
	}

?>
