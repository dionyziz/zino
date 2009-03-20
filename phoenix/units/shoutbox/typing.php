<?php
    function UnitShoutboxTyping() {
        global $user;
        global $libs;
        
        if ( !$user->Exists() ) {
            return;
        }
        
        $libs->Load( 'rabbit/event' );
        
        FireEvent( 'ShoutTyping', $user, true );
    }
?>
