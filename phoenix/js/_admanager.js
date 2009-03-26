var AdManager = {
    Create: {
        OnLoad: function() {
            $( $( 'div.buyad a.start' )[ 0 ] ).click( function () {
                $( 'div.buyad form' ).submit();
            } );
            $( "#adtitle" ).keyup( function () {
                var a = $( "div.adspreview div.ad h4 a" )[ 0 ];
				while ( a.firstChild ) {
					a.removeChild( a.firstChild );
				}
				var text = document.createTextNode( $( "#adtitle" )[ 0 ].value );
				a.appendChild( text );
            } );
			$( "#adbody" ).keyup( function () {
                var a = $( "div.adspreview div.ad p a" )[ 0 ];
				while ( a.firstChild ) {
					a.removeChild( a.firstChild );
				}
				var text = document.createTextNode( $( "#adbody" )[ 0 ].value );
				a.appendChild( text );
            } );
        }
    }
};
