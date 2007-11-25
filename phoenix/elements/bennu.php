<?php

    function ElementBennu() {
        global $user;
        global $libs;

        $libs->Load( "bennu" );
        $bennu = New Bennu();
        
        $age = New BennuRuleAge();
        $age->Value = $user->Age();
        $age->Score = 10;
        $age->Sigma = 2;

        $sex = New BennuRuleSex();
        $sex->Value = ( $user->Gender() == 'male' ) ? 'female' : 'male';
        if ( $user->Gender() != 'male' && $user->Gender() != 'female' && $user->Gender() != '-' ) {
            die( "." . $user->Gender() . "." );
        }
        $sex->Score = 5;

        $bennu->AddRule( $age );
        $bennu->AddRule( $sex );

        $bennu->Exclude( $user );
        $users = $bennu->Get( 20 );
        ?><ul style="list-style-type: none;"><?php
        foreach ( $users as $buser ) {
            ?><li><?php
                Element( "user/display", $buser );
            ?></li><?php
        }

        ?></ul><?php
    }

?>
