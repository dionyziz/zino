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
				var option = $( "#sex" )[ 0 ].value;
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
				text = document.createTextNode( "Στοχεύετε σε " + sex + " από οπουδήποτε" );
				a.appendChild( text );
			} );
			$( "#place" ).change( function() {
				var a = $( "#target" )[ 0 ];
				while ( a.firstChild ) {
					a.removeChild( a.firstChild );
				}
				var option = $( "#place" )[ 0 ].index;
				alert( option );
				var placelist = $( "#place" )[ 0 ].childNodes;
				var place = placelist[ option ].text;
				if ( option == 0 ) {
					place = "οπουδήποτε";
				}
				text = document.createTextNode( "Στοχεύετε σε " + sex + " από " + place );
				a.appendChild( text );
			} );
		}
	}
};
