<?php
    function ActionWYSIWYG( tString $lookatme ) {
        header( 'Content-type: text/plain' );
        echo $lookatme->Get();
        die();
    }
?>
