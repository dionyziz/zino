<?php
	function UnitUserJoined( tInteger $doby , tInteger $dobm , tInteger $dobd , tString $gender , tInteger $location ) {
		global $user;
		
		$doby = $doby->Get();
		$dobm = $dobm->Get();
		$dobd = $dobd->Get();
		$gender = $gender->Get();
		$location = $location->Get();
		
		if ( ( $doby >= 1940 && $doby <= 2000 ) && ( $dobm >= 1 && $dobm <= 12 ) && ( $dobd >= 1 && $dobd <= 31 ) ) {
			?>alert( 'dob ok' );<?php
		}
		if( $gender == 'm' || $gender == 'f' ) {
			?>alert( 'gender ok' );<?php
		}
		$place = new Location( $location );
		if ( $place->Exists() ) {
			?>alert( 'location ok' );<?php
		}
	}
?>
