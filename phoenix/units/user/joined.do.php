<?php
	function UnitUserJoined( tInteger $doby , tInteger $dobm , tInteger $dobd , tString $gender , tInteger $location ) {
		global $user;

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
				?>alert( 'doby <?php echo $doby; ?> dobm <?php echo $dobm; ?> dobd <?php echo $dobd; ?>' );<?php
			}
		}
		if( $gender == 'm' || $gender == 'f' ) {
			$user->Gender = $gender;
			?>alert( 'gender <?php echo $gender; ?>' );<?php
		}
		
		if ( $location != 0 ) {
			?>alert( 'location <?php echo $location; ?>' );<?php
			if ( $location == -1 ) {
				$user->Profile->Placeid = 0;
			}
			else {
				$place = New Place( $location );
				if ( $place->Exists() && !$place->IsDeleted() ) {
					$user->Profile->Placeid = $place->Id;
				}
			}
		}
		?>alert( 'saving' );<?php
		$user->Save();
		$user->Profile->Save();
		?>location.href = '<?php
		echo $rabbit_settings[ 'webaddress' ];
		?>?newuser=true';<?php
	}
?>
