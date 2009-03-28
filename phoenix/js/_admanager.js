var AdManager = {
    Create: {
        OnLoad: function() {
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
    },
	Demographics: {
		OnLoad: function() {
			$( "#sex" ).change( function() {
				var a = $( "#target" )[ 0 ];
				while ( a.firstChild ) {
					a.removeChild( a.firstChild );
				}
				var option = document.createTextNode( $( "#sex" )[ 0 ].value );
				switch( option ) {
					case 1:
						var sex = "άνδρες";
						break;    
					case 2:
						var sex = "γυναίκες";
						break;
					default:
						var sex = "άτομα";
				}
				a.appendChild( "Στοχεύετε σε" + sex + "από οπουδήποτε" );
			} );
		}
	}
};
