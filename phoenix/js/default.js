$( function() {
	if ( $( 'a.profile' )[ 0 ] ) {
		var username;
		if ( $( 'a.profile span.imageview img' )[ 0 ] ) {
			username = $( 'a.profile span.imageview img' ).attr( 'alt' ); //get the username of the logged in user from the banner
		}
		else {
			//for users without avatar
			username = $( 'a.profile' ).text();
		}
	}
	else {
		var username = false;
	}
} );