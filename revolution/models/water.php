<?php
    function w_assert( $condition, $description = '' ) {
        if ( !$condition ) {
            die( $description );
        }
    }
?>
