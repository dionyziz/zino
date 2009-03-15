<?php
    function UnitAdManagerShowAd( tCoalaPointer $f ) {
        ob_start();
        Element( 'admanager/showad' );
        $html = ob_get_clean();
        echo $f;
        ?>(<?php
        echo w_json_encode( $html );
        ?>)<?php
    }
?>
