function GetUsername() {
	var username = false;
	if ( $( 'a.profile' )[ 0 ] ) {
		if ( $( 'a.profile span.imageview img' )[ 0 ] ) {
			username = $( 'a.profile span.imageview img' ).attr( 'alt' ); //get the username of the logged in user from the banner
		}
		else {
			//for users without avatar
			username = $( 'a.profile' ).text();
		}
	}
	else {
		username = false;
	}
	return username;
}
$( function() {
	$( "img" ).lazyload( { 
		threshold : 2000,
		placeholder : "http://static.zino.gr/phoenix/anonymous100.jpg"
	} ); 
} );