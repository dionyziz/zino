var Notification = {
	Visit : function( url , typeid , eventid , commentid ) {
		if ( typeid == 3 ) {
			if ( $( '#comment_' + commentid )[ 0 ] ) {
				window.location.hash = 'comment_' + commentid;
				Notification.Delete( eventid );
			}
			else {
				document.location.href = url;
			}
		} 
		else {
			Coala.Warm( 'notification/delete' , { eventid : eventid , relationnotif : true } );
		}
	},
	Delete : function( eventid ) {
		$( 'div#' + eventid ).animate( { opacity : "0" , height : "0" } , 400 , function() {
			$( this ).remove();
			alert( $( 'div.notifications div.list div.event' ).length );
			if ( $( 'div.notifications div.list div.event' ).length == 0 ) {
				$( 'div.notifications' ).remove();
			}
		} );
		Coala.Warm( 'notification/delete' , { eventid : eventid , relationnotif : false } );
	}
};