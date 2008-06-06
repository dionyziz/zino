<?php
    function ElementUserSubdomainmatch() {
        global $page;

        ob_start();
        ?>alert( <?php
        echo w_json_encode( $_SERVER[ 'HTTP_HOST' ] . $_SERVER[ 'REQUEST_URI' ] );
        ?> );<?php
        $page->AttachInlineScript( ob_get_clean() );
    }
?>
