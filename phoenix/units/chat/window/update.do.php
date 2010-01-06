<?php
    function UnitChatWindowUpdate(
        tInteger $channelid, tBoolean $deactivate,
        tInteger $x, tInteger $y,
        tInteger $w, tInteger $h ) {
        global $user;
        global $libs;

        if ( !$user->Exists() ) {
            return;
        }
        $userid = $user->Id;
        $channelid = $channelid->Get();
        if ( $channelid <= 0 ) {
            return;
        }
        $deactivate = $deactivate->Get();
        $x = $x->Get();
        $y = $y->Get();
        $w = $w->Get();
        $h = $h->Get();
        if ( $x < 0 || $y < 0 || $w < 0 || $h < 0 ) {
            return;
        }
        if ( $x + $y + $w + $h == 0 && $deactivate === false ) { // everything is zero, no attributes to change
            return;
        }
        
        $libs->Load( 'chat/channel' );

        Chat_UpdateParticipant( $channelid, $userid, $x, $y, $w, $h, $deactivate );
    }
?>
