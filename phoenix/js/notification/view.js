var Notification = {
	Visit : function( url , typeid , eventid , commentid ) {
		if ( typeid == 3 ) {
			if ( $( '#comment_' + commentid )[ 0 ] ) {
				window.location.hash = 'comment_' + commentid;
				Notification.Delete( eventid );
			}
			else {
				//alert( url );
				document.location.href = url;
			}
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