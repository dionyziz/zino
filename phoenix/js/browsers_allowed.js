$( document ).ready( function() {
	if ( $.browser.msie && $.browser.version < 7 ) {
		window.location.href = "?p=ie";
	}
} );
