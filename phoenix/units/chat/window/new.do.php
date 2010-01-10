<?php
    function UnitChatWindowNew( tText $target, tCoalaPointer $f ) {
        global $libs;
        global $user;

        if ( !$user->Exists() ) {
            return;
        }

        $target = $target->Get();
        $finder = New UserFinder();
        $theuser = $finder->FindByName( $target );
        
        $libs->Load( 'chat/channel' );

        $channelid = Chat_Create( $user->Id, $theuser->Id );

        echo $f;
        ?>(<?php
        echo $channelid;
        ?>);<?php
    }
?>
