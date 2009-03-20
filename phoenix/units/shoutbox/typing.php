<?php
    function UnitShoutboxTyping() {
        global $user;
        
        if ( !$user->Exists() ) {
            return;
        }
        
        FireEvent( 'ShoutTyping', $user, true );
    }
?>
