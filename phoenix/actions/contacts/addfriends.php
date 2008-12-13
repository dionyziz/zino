<?php
    function ActionContactsAddfriends( tTextArray $approved, tText $email ) {
        global $user;
        global $libs;
        
        $libs->Load( 'relation/relation' );
              
        foreach ( $approved as $userid ) {
            $userid = $userid->Get();            
            $theuser = new User( $userid );
            if ( $theuser->Exists() ) {
                $relation = New FriendRelation();
                $relation->Userid = $user->Id;
                $relation->Friendid = $theuser->Id;
                $relation->Typeid = 3;
                $relation->Save();
            }
        }
           
        return Redirect( 'contactfinder&email='.$email.'&step=2');
    }
?>
