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
	if ( $.browser.mozilla ) {
		$("img").lazyload( { 
			threshold : 200
		} );
	}
    alert( 'test2' );
} );
