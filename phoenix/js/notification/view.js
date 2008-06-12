var Notification = {
	Visit : function( url , typeid ) {
		//type can be either comment or relation
		alert( typeid );
		document.location.href = url;
	},
	Delete : function( eventid ) {
		alert( 'delete' );
		$( 'div#' + eventid ).fadeOut( 400 , function() {
			$( this ).remove();
		} );
	}
};