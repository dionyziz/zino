$( function() { 
    $( 'div#schview div.photos div.plist ul li a.uploadphoto' ).click( function() {
        var modal = $( '#uploadmodal' )[ 0 ].cloneNode( true );
        $( modal ).show();
        Modals.Create( modal , 600 , 500 );
    } );
} );
