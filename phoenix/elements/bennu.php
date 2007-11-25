<?php

    function ElementBennu() {
        global $user;
        global $libs;

        $libs->Load( "bennu" );
        $bennu = New Bennu();
        
        $age = New BennuRuleAge();
        $age->Value = $user->Age();
        $age->Score = 5;
        $age->Sigma = 2;

        $sex = New BennuRuleSex();
        $sex->Value = 'female';
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
