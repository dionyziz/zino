<?php
    function UnitUserProfileEasyupload() {
        ?>$( 'div#easyphotoupload div.modalcontent' ).html( <?php
        ob_start();
        Element( 'user/profile/easyupload' );
        echo w_json_encode( ob_get_clean() );
        ?> );
        $( 'div#easyphotoupload div.modalcontent div ul li' ).click( function() {
            /*
            var albumname = $( this ).find( 'span img' ).attr( 'alt' );
            var username = GetUsername();
            if ( albumname == username ) {
                albumname = "Ego";
            }
            */
            $( 'div#easyphotoupload div.modalcontent div b' ).empty().append( document.createTextNode( 'test' ) );
            alert( $( this ).attr( 'id' ).substr( 6 ) );
        } );
        <?php
    }
?>

