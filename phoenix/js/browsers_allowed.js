$( document ).ready( function() {
	if ( $.browser.msie && $.version < 7 ) {
		window.location.href = "?p=ie";
	}
} );
