<?php
    function ElementTrustConfirm() {
        global $page;
        
        if ( isset( $_SESSION[ 'trusted' ] ) || !isset( $_SESSION[ 'trusthash' ] ) ) {
            return;
        }
        $page->AttachScript( 'js/coala.js' );
        $page->AttachInlineScript( 'Coala.Warm( "trust/confirm", { "hash": "' . $_SESSION[ 'trusthash' ] . '" } );' );
    }
?>
