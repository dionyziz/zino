<?php
    function UnitShoutboxTyping( tBoolean $typing ) {
        global $user;
        global $libs;
        
        if ( !$user->Exists() ) {
            return;
        }
        
        $typing = $typing->Get();
        
        $libs->Load( 'rabbit/event' );
        
        FireEvent( 'ShoutTyping', $user, $typing );
    }
?>
