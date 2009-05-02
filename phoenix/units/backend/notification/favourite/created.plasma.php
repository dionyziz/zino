<?php
    function UnitBackendNotificationFavouriteCreated( Favourite $favourite ) {
        global $libs;

        $libs->Load( 'event' );

        $event = New Event();
        $event->Typeid = EVENT_FAVOURITE_CREATED;
        $event->Itemid = $favourite->Id;
        $event->Userid = $favourite->Userid;
        $event->Save();

        return false;
    }
?>
