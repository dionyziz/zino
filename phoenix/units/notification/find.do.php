<?php

    function UnitNotificationFind( tInteger $notifid , tInteger $limit ) {
        global $user;
        global $libs;

        $libs->Load( 'notify' );

        $notifid = $notifid->Get();
        $limit = $limit->Get();
        $finder = New NotificationFinder();
        $notifs = $finder->FindByUserAfterId( $user , $notifid , 0, $limit );
        ?>var notifnode = $( <?php
        ob_start();
        Element( "notification/list" , $notifs );
        echo w_json_encode( ob_get_clean() );
        ?> );
        var counted = <?php
        echo count( $notifs );
        ?>;
        Notification.INotifs += counted;
        if ( counted < <?php
        echo $limit;
        ?> ) {
            Notification.TraversedAll = true;

        }
        $( "#inotifs" ).append( notifnode );<?php
    }
?>
