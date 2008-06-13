var Notification = {
	Visit : function( url , typeid , eventid ) {
		document.location.href = url;
		alert( 'typeid is ' + typeid + ' eventid is ' + eventid  );
	},
	Delete : function( eventid ) {
		$( 'div#' + eventid ).animate( { opacity : "0" , height : "0" } , function() {
			$( this ).remove();
		} );
		//coala
	}
};