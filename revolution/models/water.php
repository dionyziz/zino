<?php
    function w_assert( $condition, $description = '' ) {
        if ( !$condition ) {
            throw New Exception( $description );
        }
    }
?>
