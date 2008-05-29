<?php
	function UnitUserJoined( tInteger $doby , tInteger $dobm , tInteger $dobd , tString $gender , tInteger $location ) {
		global $user;
		//global $libs;
		
		//$libs->Load( 'place' );
		$doby = $doby->Get();
		$dobm = $dobm->Get();
		$dobd = $dobd->Get();
		$gender = $gender->Get();
		$location = $location->Get();
		
		if ( ( $doby >= 1940 && $doby <= 2000 ) && ( $dobm >= 1 && $dobm <= 12 ) && ( $dobd >= 1 && $dobd <= 31 ) ) {
			$user->Profile->BirthDay = $dobd;
			$user->Profile->BirthMonth = $dobm;
			$user->Profile->BirthYear = $doby;
		}
		if( $gender == 'm' || $gender == 'f' ) {
			$user->Gender = $gender;
		}
		$place = new Place( $location );
		if ( $place->Exists() ) {
			?>alert( 'location ok' );<?php
		}
		$user->Save();
		$user->Profile->Save();
		?>location.href = '<?php
		echo $rabbit_settings[ 'webaddress' ];
		?>?newuser=true';<?php
	}
?>
