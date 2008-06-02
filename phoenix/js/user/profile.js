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
	/*
	if ( $( 'div#profile' )[ 0 ] ) {
		$( 'div#profile div.main div.notifications div.list div.event' ).mouseover( function() {
			$( this ).css( "border" , "1px dotted #666" );
		} )
		.mouseout( function() {
			$( this ).css( "border" , "0" );
		} );
	}
	*/
} );