$( document ).ready( function() {
	alert( window.location.href.substr( 30 ) );
	if ( window.location.href.substr( 30 ) != "p=ie" ) {
		return;
	}
	if ( $.browser.msie && $.browser.version < 7 ) {
		window.location.href = "?p=ie";
	}
} );
