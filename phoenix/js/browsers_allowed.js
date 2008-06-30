$( document ).ready( function() {
	alert( "Trexo" );
	if ( $.browser.msie && $.version < 7 ) {
		window.location.href = "?p=ie";
	}
} );
