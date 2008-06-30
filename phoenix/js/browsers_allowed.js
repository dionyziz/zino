$( document ).ready( function() {
	if ( window.location.href.substr( 27 ) === "?p=ie" || window.location.href.substr( 28 ) === "?p=ie" ) {
		return;
	}
	if ( $.browser.msie && $.browser.version < 7 ) {
		window.location.href = "?p=ie";
	}
} );
