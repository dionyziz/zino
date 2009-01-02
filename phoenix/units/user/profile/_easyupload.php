<?php
    function UnitUserProfileEasyupload() {
        ?>$( 'div#easyphotoupload div.modalcontent' ).html( <?php
        ob_start();
        Element( 'user/profile/easyupload' );
        echo w_json_encode( ob_get_clean() );
        ?> );
        $( 'div#easyphotoupload div.modalcontent div ul li' ).click( function() {
            if ( !previousselection ) {
                var previousselection = $( 'div#easyphotoupload div.modalcontent div ul li' );
            }
            $( previousselection ).removeClass( 'selected' );
            $( this ).addClass( 'selected' );
            previousselection = $( this )[ 0 ];
            var albumname = $( this ).find( 'span img' ).attr( 'alt' );
            if ( albumname == GetUsername() ) {
                albumname = 'Εγώ';
            }
            $( 'div#easyphotoupload div.modalcontent div b' ).empty().append( document.createTextNode( albumname ) );
        } );
        <?php
    }
?>
