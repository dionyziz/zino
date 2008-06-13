var Notification = {
	Visit : function( url , typeid ) {
		document.location.href = url;
	},
	Delete : function( eventid ) {
		$( 'div#' + eventid ).animate( { opacity : "0" , height : "0" } , function() {
			$( this ).remove();
		} );
		$( 'div#' + eventid ).click( function() {
			return false;
		} );
	}
};