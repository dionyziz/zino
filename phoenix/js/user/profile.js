var Profile = {
	AddAvatar : function( imageid ) {
		Coala.Warm( 'user/avatar' , { imageid : imageid } );
		$( 'div.main div.ybubble' ).animate( { height: "0" , 800 , function() {
			$( this ).remove():
		} );
	}
};