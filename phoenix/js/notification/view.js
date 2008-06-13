var Notification = {
	Visit : function( url , typeid , eventid , commentid ) {
		if ( typeid == 3 ) {
		
		} 
		else {
			document.location.href = url;
		}
	},
	Delete : function( eventid ) {
		$( 'div#' + eventid ).animate( { opacity : "0" , height : "0" } , function() {
			$( this ).remove();
		} );
		Coala.Warm( 'notification/delete' , { eventid : eventid } );
	}
};