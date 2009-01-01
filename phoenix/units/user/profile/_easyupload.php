<?php
    function UnitUserProfileEasyupload() {
        ?>$( 'div#easyphotoupload div.modalcontent' ).html( <?php
        ob_start();
        Element( 'user/profile/easyupload' );
        echo w_json_encode( ob_get_clean() );
        ?> );<?php
    }
?>
