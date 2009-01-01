<?php
    function UnitUserProfileEasyupload() {
        ?>$( 'div#easyphotoupload div.modalcontent' ).html( <?php
        ob_start();
        Element( 'user/profile/easyupload' );
        echo w_json_encode( ob_get_clean() );
        ?> );
        $( 'div#easyphotoupload div.modalcontent div ul li' ).click( function() {

            $( 'div#easyphotoupload div.modalcontent div b' ).empty().append( document.createTextNode( 'test' ) );
            
            alert( $( this ).attr( 'id' ).substr( 6 ) );
            var albumid = $( this ).attr( 'id' ).substr( 6 );
            var newurl = '?p=upload&albumid=' + albumid + 'typeid=3&color=eef5f9';
            alert( newurl );
            if ( $.browser.msie ) {

            }
            else {

            }
        } );
        
        <?php
    }
?>
