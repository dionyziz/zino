var Notification = {
	Visit : function( url , typeid , eventid , commentid ) {
		if ( typeid == 3 ) {
			document.location.href = url;
		} 
		else {
			Coala.Warm( 'notification/delete' , { eventid : eventid , relationnotif : false } );
			document.location.href = url;
		}
	},
	Delete : function( eventid ) {
		$( 'div#' + eventid ).animate( { opacity : "0" , height : "0" } , 400 , function() {
			$( this ).remove();
			if ( $( 'div.notifications div.list div.event' ).length === 0 ) {
				$( 'div.notifications' ).remove();
			}
		} );
		Coala.Warm( 'notification/delete' , { eventid : eventid , relationnotif : false } );
        var count = document.title.split('(')[1].split(')')[0];
        --count;
        if ( count == 0 ) {
            document.title = 'Zino';
        }
        else {
            document.title = 'Zino (' + count + ')';
        }
		return false;
	},
	AddFriend : function( eventid , theuserid ) {
		$( 'div#addfriend_' + theuserid  + ' a' )
		.fadeOut( 400 , function() {
			$( this )
			.parent()
			.empty()
			.append( document.createTextNode( 'Έγινε προσθήκη' ) );
		} );
		Coala.Warm( 'notification/addfriend' , { userid : theuserid } );
		Coala.Warm( 'notification/delete' , { eventid : eventid , relationnotif : false } );
		return false;
	}
};

