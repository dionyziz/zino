var School = {
    OnLoad : function() {
        $( 'div#schview div.photos div.plist ul li a.s_bigadd' ).click( function() {
            var modal = $( '#schooluploadmodal' )[ 0 ].cloneNode( true );
            $( modal ).show();
            $( modal ).find( 'a.close' ).click( function() {
                Modals.Destroy();
                return false;
            } );
            Modals.Create( modal , 350 , 250 );
            return false;
        } );
    }
}
