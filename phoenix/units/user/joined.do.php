<?php
    function UnitUserJoined( tInteger $doby , tInteger $dobm , tInteger $dobd , tText $gender , tInteger $location ) {
        global $user;
        global $rabbit_settings;

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
        ?>location.href = '<?php
        echo $rabbit_settings[ 'webaddress' ];
        ?>?newuser=true';<?php
    }
?>
