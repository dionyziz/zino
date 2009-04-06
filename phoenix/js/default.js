function GetUsername() {
	var oldtime = new Date().getTime();
    var username = false;
	if ( $( 'a.profile' )[ 0 ] ) {
		if ( $( 'a.profile span.imageview img' )[ 0 ] ) {
			username = $( 'a.profile span.imageview img' ).attr( 'alt' ); // get the username of the logged in user from the banner
		}
		else {
			// for users without avatar
			username = $( 'a.profile' ).text();
		}
	}
	else {
		username = false;
	}
    var newtime = new Date().getTime();
    alert( newtime - oldtime );
	return username;
}
$( function() {
    /*if ( $.browser.mozilla ) {
	    $( "img" ).not( ".nolazy" ).lazyload( { 
			threshold : 200
		} );
	}
    */
	if ( $.browser.msie && $.browser.version < 7 ) {
		window.location.href = "ie.html";
	}
} );
