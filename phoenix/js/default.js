function GetUsername() {
	var oldtime = new Date().getTime();
    var username = false;
	if ( $( 'div.banner a.profile' )[ 0 ] ) {
        username = $( 'a.profile' ).text();
	}
	else {
		username = false;
	}
    var newtime = new Date().getTime();
    alert( newtime - oldtime );
	alert( username );
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
