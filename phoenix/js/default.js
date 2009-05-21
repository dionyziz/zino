function GetUsername() {
    return $( "img.banneravatar" ).attr( "alt" );
}
$( function() {
    /*if ( $.browser.mozilla ) {
	    $( "img" ).not( ".nolazy" ).lazyload( { 
			threshold : 200
		} );
	}
    */
    if ( ExcaliburSettings.AllowIE6 ) {
        return;
    }
	if ( $.browser.msie && $.browser.version < 7 ) {
		window.location.href = "ie.html";
	}
} );
