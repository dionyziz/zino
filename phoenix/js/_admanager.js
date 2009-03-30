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
		},
		TargetGroup: function( minage, maxage, sex, places ) {
			var age = '';
			if ( minage > 0 && maxage == 0 ) {
				age = ' τουλάχιστον ' + minage + ' ετών';
			}
			else if ( minage == 0 && maxage > 0 ) {
				age = ' το πολύ ' + maxage + ' ετών ';
			}
			else if ( minage > 0 && maxage > 0 ) {
				age = ' ' + minage + ' - ' + maxage + ' ετών';
			}
			
			switch ( sex ) {
				case 1:
					sex = ' άντρες';
					if ( maxage != 0 && maxage < 18 ) {
						sex = ' αγόρια ';
					}
					break;
				case 2:
					sex = ' γυναίκες';
					if ( maxage != 0 && maxage < 18 ) {
						sex = ' κορίτσια';
					}
					break;
				default:
					sex = ' άτομα';
					break;
			}
		
			var location = ' από οπουδήποτε';
			if ( places.length > 1 ) {
				location = '';
				for ( var i = 1; i < places.length - 1; ++i ) {
					location += ', ' + places[ i ];
				}
				location = ' από ' + places[ 0 ] + location + ' και ' + places[ places.length - 1 ];
			}
			else if ( places.length == 1 ) {
				location = ' από ' + places[ 0 ];
			}
			
			return 'Στοχεύετε σε' + age + sex + location;
		}
	}
};
