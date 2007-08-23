<?php

	function UnitUsersOptions( tString $input_name, tString $input_value ) {
		global $user;
		
		$input_name 	= $input_name->Get();
		$input_value 	= $input_value->Get();
		
		$signature		= $user->Signature();
		$newpassword	= "";
		$email			= $user->Email();
		$gender			= $user->Gender();
		$dob			= $user->DateOfBirth();
		$hobbies		= $user->Hobbies();
		$slogan			= $user->Subtitle();
		$place			= $user->Place();
		$msn			= $user->MSN();
		$skype			= $user->Skype();
		$yim			= $user->YIM();
		$aim			= $user->AIM();
		$icq			= $user->ICQ();
		$gtalk			= $user->Gtalk();
		$height			= $user->Height();
		$weight			= $user->Weight();
		$eyecolor		= $user->EyeColor();
		$haircolor		= $user->HairColor();
		
		switch ( $input_name ) {
			case "signature":
				$signature = $input_value;
				break;
			case "email":
				$email = $input_value;
				break;
			case "gender":
				$gender = $input_value;
				break;
			case "dob":
				$dob = $input_value;
				break;
			case "hobbies":
				$hobbies = $input_value;
				break;
			case "slogan":
				$slogan = $input_value;
				break;
			case "place":
				$place = $input_value;
				break;
			case "msn":
				$msn = $input_value;
				break;
			case "skype":
				$skype = $input_value;
				break;
			case "yim":
				$yim = $input_value;
				break;
			case "icq":
				$icq = $input_value;
				break;
			case "gtalk":
				$gtalk = $input_value;
				break;
			case "height":
				$height = $input_value;
				break;
			case "weight":
				$weight = $input_value;
				break;
			case "eyecolor":
				$eyecolor = $input_value;
				break;
			case "haircolor":
				$haircolor = $input_value;
				break;
		}
		
		UpdateUser( $signature, "", $email, $gender, $dob, $hobbies, $slogan, $place, $msn, $skype, $yim, $aim, $icq, $gtalk, $height, $weight, $eyecolor, $haircolor );
	}

?>
