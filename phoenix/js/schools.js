$( function() { 
    $( 'div#schview div.photos div.plist ul li a.uploadphoto' ).click( function() {
        var modal = $( '#uploadmodal' )[ 0 ].cloneNode( true );
        $( modal ).show();
        $( modal ).find( 'a.close' ).click( function() {
            Modals.Destroy();
        } );
        Modals.Create( modal , 600 , 500 );
        return false;
    } );
} );
