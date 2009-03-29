function GetUsername() {
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
	return username;
}
$( function() {
    var old = new Date().getTime();    
    if ( $.browser.mozilla ) {
	    $( "img" ).not( ".nolazy" ).lazyload( { 
			threshold : 200
		} );
	}
    var new = new Date().getTime();
    alert( (new-old)/1000) );
	if ( $.browser.msie && $.browser.version < 7 ) {
		window.location.href = "ie.html";
	}
} );
