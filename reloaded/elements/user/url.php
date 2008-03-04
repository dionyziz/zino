<?php
    function ElementUserURL( $theuser ) {
        global $xc_settings;
        
        echo str_replace( '*', $theuser->Username(), $xc_settings[ 'usersubdomains' ] );
    }
?>
