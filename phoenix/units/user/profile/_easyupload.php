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
            $( this ).addClass( 'selected' );
            previousselection = $( this )[ 0 ];
            $( 'div#easyphotoupload div.modalcontent div b' ).empty().append( document.createTextNode( 'test' ) );
            var albumid = $( this ).attr( 'id' ).substr( 6 );
            var newurl = '?p=upload&albumid=' + albumid + '&typeid=3&color=eef5f9';
            alert( newurl );
            if ( $.browser.msie ) {
                $( 'div#easyphotoupload div.modalcontent div.uploaddiv iframe' ).attr( 'src' , newurl ); 
            }
            else {
                $( 'div#easyphotoupload div.modalcontent div.uploaddiv object' ).attr( 'src' , newurl );
            }
        } );
        
        <?php
    }
?>
