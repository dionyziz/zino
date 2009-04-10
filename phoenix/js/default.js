function GetUsername() {
    var username = false;
	if ( $( '#banner a.profile' )[ 0 ] ) {
        username = $( 'a.profile' ).text();
	}
	else {
		username = false;
	}
    var newtime = new Date().getTime();

    return username;
}
function JSExec() {
    var newtimer = new Date().getTime();
    alert( newtimer - oldtime );
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
