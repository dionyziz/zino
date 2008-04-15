<?php
    function UnitTrustConfirm( tString $hash ) {
        if ( $_SESSION[ 'trusted' ] ) { // already trusted
            return;
        }
        $hash = $hash->Get();
        if ( $_SESSION[ 'trusthash' ] != $hash ) {
            return;
        }
        $_SESSION[ 'trusted' ] = true;
        Trust_Confirm( $hash );
    }
?>
