$( document ).ready( function() {
	alert( $.browser.msie + " " + $.version );
	if ( $.browser.msie && $.version < 7 ) {
		window.location.href = "?p=ie";
	}
} );
