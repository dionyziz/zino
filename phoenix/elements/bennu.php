<?php

    function ElementBennu() {
        global $user;
        global $libs;

        $libs->Load( "bennu" );

        $bennu = New Bennu();
        
        $age = New BennuRuleSex();
        $age->Value = $user->Age();
        $age->Score = 5;
        $age->Sigma = 2;

        $sex = New BennuRuleSex();
        $sex->Value = 'f';
        $sex->Score = 5;

        $bennu->AddRule( $age );
        $bennu->AddRule( $sex );

        $users = $bennu->Get( 20 );

        ?><ul style="list-style-type: none;"><?php
        foreach ( $users as $user ) {
            ?><li><?php
                Element( "user/display", $user );
            ?></li><?php
        }

        ?></ul><?php
    }

?>
