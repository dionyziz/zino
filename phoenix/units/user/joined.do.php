<?php
    function UnitUserJoined( tInteger $doby , tInteger $dobm , tInteger $dobd , tText $gender , tInteger $location ) {
        global $user;
        global $rabbit_settings;

        $doby = $doby->Get();
        $dobm = $dobm->Get();
        $dobd = $dobd->Get();
        $gender = $gender->Get();
        $location = $location->Get();
		$validdob = true;
        if ( checkdate( $dobm , $dobd , $doby ) ) {
			$user->Profile->BirthMonth = $dobm;
			$user->Profile->BirthDay = $dobd;
			$user->Profile->BirthYear = $doby;
        }
		else {
			$validdob = false;
			?>$( 'div.profinfo form div span.invaliddob' ).css( 'opacity' , '0' ).removeClass( 'invisible' ).animate( { opacity : "1" } , 200 );<?php
		}
        if( $gender == 'm' || $gender == 'f' ) {
            $user->Gender = $gender;
        }
        if ( $location != 0 ) {
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
        $user->Save();
        $user->Profile->Save();
		if ( $validdob ) {
	        ?>location.href = '<?php
	        echo $rabbit_settings[ 'webaddress' ];
	        ?>?newuser=true';<?php
		}
    }
?>
