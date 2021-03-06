<?php
    function UnitBackendNotificationFavouriteCreated( Favourite $favourite ) {
        global $libs;
		
        $libs->Load( 'notify/notify' );
		
		if( $favourite->Typeid == TYPE_STOREITEM ){
			return false;
		}
        $notification = New Notification();
        $notification->Typeid = EVENT_FAVOURITE_CREATED;
        $notification->Itemid = $favourite->Id;
        $notification->Fromuserid = $favourite->Userid;
        $notification->Save();

        return false;
    }
?>
