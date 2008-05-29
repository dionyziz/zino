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
		
		if ( $dobd >=1 && $dobd <=31  && $dobm >= 1 && $dobm <= 12 && $doby ) {
			if ( strtotime( $doby . '-' . $dobm . '-' . $dobd ) ) {
				$user->Profile->BirthMonth = $dobm;
				$user->Profile->BirthDay = $dobd;
				$user->Profile->BirthYear = $doby;
			}
		}
		if( $gender == 'm' || $gender == 'f' ) {
			$user->Gender = $gender;
		}
		
		?>alert( '<?php
		echo $location;
		?>' );<?php
		$place = New Place( $location );
		?>alert( '<?php
		echo $place->Name;
		?>' );<?php
		if ( $place->Exists() && !$place->IsDeleted() ) {
			?>alert( 'location ok' );<?php
		}
		$user->Save();
		$user->Profile->Save();
		?>location.href = '<?php
		echo $rabbit_settings[ 'webaddress' ];
		?>?newuser=true';<?php
	}
?>
