var Profile = {
	AddAvatar : function( imageid ) {
		var li = document.createElement( 'li' );
		var link = document.createElement( 'a' );
		$( li ).append( link );
		$( 'div.main div.photos ul' ).prepend( li );
		Coala.Warm( 'user/avatar' , { imageid : imageid } );
		$( 'div.main div.ybubble' ).animate( { height: "0" } , 400 , function() {
			$( this ).remove();
		} );
	}
};
$( document ).ready( function() {
	if ( $( 'div#profile' )[ 0 ] ) {
		$( 'div#profile div.main div.notifications div.list div.event' ).mouseover( function() {
			$( this ).css( "border" , "1px dotted #666" ).css( "padding" , "4px" );
		} )
		.mouseout( function() {
			$( this ).css( "border" , "0" ).css( "padding" , "5px" );
		} );
		$( 'div#profile div.main div.notifications div.expand a' ).click( function() {
			var notiflist = $( 'div#profile div.main div.notifications div.list' )[ 0 ] ? $( 'div#profile div.main div.notifications div.list' )[ 0 ] : false;
			if ( $( notiflist ).hasClass( 'invisible' ) ) {
				$( 'div#profile div.main div.notifications div.expand a' ).css( "background-image" , 'url( "' + ExcaliburSettings.imagesurl + 'arrow_up.png" )' );
				$( notiflist ).removeClass( 'invisible' ).animate( { height : '100%' } , 400 );
			}
			else {
				$( 'div#profile div.main div.notifications div.expand a' ).css( "background-image" , 'url( "' + ExcaliburSettings.imagesurl + 'arrow_down.png" )' );
				$( notiflist ).animate( { height : "0" } , 400 , function() {
					$( notiflist ).addClass( 'invisible' );
				} );
			}
			return false;
		} );
	}
} );