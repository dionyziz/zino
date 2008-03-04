<?php
    function ElementUserURL( $theuser ) {
        global $xc_settings;
        
        if ( !is_object( $theuser ) ) {
            return;
        }
        if ( !( $theuser instanceof User ) ) {
            return;
        }
        
        if ( $theuser->Subdomain() != '' ) {
            echo str_replace( '*', urlencode( $theuser->Subdomain() ), $xc_settings[ 'usersubdomains' ] );
        }
        else {
            echo '/user/' . urlencode( $theuser->Username() );
        }
    }
?>
