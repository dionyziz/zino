<?php

    function UnitNotificationFind( tInteger $notifid , tInteger $limit ) {
        global $user;
        global $libs;

        $libs->Load( 'notify' );
?>        alert( 'test' );<?php
        $limit = $limit->Get();
        $finder = New NotificationFinder();
        $notifs = $finder->FindByUserAfterId( $user , $notifid->Get() , $limit );
        ?>
        alert( $( <?php
        ob_start();
        Element( "notification/list" , $notifs );
        echo w_json_encode( ob_get_clean() );
        ?> ) );<?php
    }
?>
