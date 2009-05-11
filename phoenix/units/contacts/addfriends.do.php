<?php
    function UnitContactsInvite( tText $ids ) {
        global $libs;
        global $user;
        global $settings;
        $ids = $ids->Get();
        $userids = explode( " ", $ids );
        ?>alert('<?php
        echo sizeof( $ids );
        ?>');<?php 
        if ( $user->Exists() && sizeof( $ids ) != 0 ) {
            foreach( $userids as $userid ){
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
        window.location = <?php
        echo $settings[ 'webaddress' ];
        ?>;<?php
    }
?>
