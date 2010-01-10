<?php
    function UnitChatWindowNew( tText $target ) {
        global $libs;
        global $user;

        if ( !$user->Exists() ) {
            return;
        }

        $target = $target->Get();
        $finder = New UserFinder();
        $theuser = $finder->FindByName( $target );
        
        $libs->Load( 'chat/channel' );

        Chat_Create( $user->Id, $theuser->Id );
    }
?>
