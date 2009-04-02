<?php

    function UnitUserSettingsGenderupdate( tText $gender , tText $sex , tText $relationship, tText $religion , tText $politics ) {
        $gender = $gender->Get();
        $sex = $sex->Get();
        $relationship = $relationship->Get();
        $religion = $religion->Get();
        $politics = $politics->Get();
        ?>$( '#sex' ).html( <?php
            ob_start();
            Element( 'user/settings/personal/sex' , $sex , $gender );
            echo w_json_encode( ob_get_clean() );
        ?> );
        $( '#sex select' ).change( function() {
            Settings.Enqueue( 'sex' , this.value , 3000 );
        });
        $( '#relationship' ).html( <?php
            ob_start();
            Element( 'user/settings/personal/relationship' , $relationship, $gender );
            echo w_json_encode( ob_get_clean() );
        ?> );
        $( '#relationship select' ).change( function() {
            Settings.Enqueue( 'relationship' , this.value , 3000 );
        });
        $( '#religion' ).html( <?php
            ob_start();
            Element( 'user/settings/personal/religion' , $religion , $gender );
            echo w_json_encode( ob_get_clean() );
        ?> );
        $( '#religion select' ).change( function() {
            Settings.Enqueue( 'religion' , this.value , 3000 );
        });
        $( '#politics' ).html( <?php
            ob_start();
            Element( 'user/settings/personal/politics' , $politics , $gender );
            echo w_json_encode( ob_get_clean() );
        ?> );
        $( '#politics select' ).change( function() {
            Settings.Enqueue( 'politics' , this.value , 3000 );
        });<?php
    }
?>
