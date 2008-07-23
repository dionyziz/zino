<?php
    
    function UnitNotificationAddfriend( tInteger $userid ) {
        global $user;
        global $libs;
        
        $libs->Load( 'relation/relation' );
        
        if ( $user->Exists() ) {
            $theuser = New User( $userid->Get() );
            if ( $theuser->Exists() ) {
                $relation = New FriendRelation();
                $relation->Userid = $user->Id;
                $relation->Friendid = $theuser->Id;
                $relation->Typeid = 3;
                $relation->Save();
            }
        }
    }
?>