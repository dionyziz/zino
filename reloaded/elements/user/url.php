<?php
    function ElementUserURL( $theuser ) {
        global $xc_settings;
        
        if ( !is_object( $theuser ) ) {
            return;
        }
        if ( !( $theuser instanceof User ) ) {
            return;
        }
        
        echo str_replace( '*', $theuser->Username(), $xc_settings[ 'usersubdomains' ] );
    }
?>
