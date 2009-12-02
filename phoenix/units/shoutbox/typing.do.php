<?php
    function UnitShoutboxTyping( tBoolean $typing, tInteger $channel ) {
        global $user;
        global $libs;
        
        if ( !$user->Exists() ) {
            return;
        }
        
        $typing = $typing->Get();
		$channel = $channel->Get();
        
        $libs->Load( 'rabbit/event' );
        
        FireEvent( 'ShoutTyping', $user, $typing, $channel );
    }
?>
