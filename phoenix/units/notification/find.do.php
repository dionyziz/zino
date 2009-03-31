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
        $( notifnode ).mouseover( function() {
			$( this ).css( "border" , "1px dotted #666" ).css( "padding" , "4px" );
		} )
		.mouseout( function() {
			$( this ).css( "border" , "0" ).css( "padding" , "5px" );
		} );
        $( "#inotifs" ).append( notifnode );<?php
        if ( $notifs->TotalCount() > 10 ) {
            $count = '10+';
        }
        else {
            $count = $notifs->TotalCount();
        }
        ?>document.title = 'Zino (<?php
        echo $count;
        ?>)';<?php
    }
?>
