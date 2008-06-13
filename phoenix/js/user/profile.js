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
	},
	AddFriend : function( userid ) {
		$( 'div.sidebar div.basicinfo div.addfriend a' ).fadeOut( 400 , function() {
			$( this ).empty().append( document.createTextnode( 'Διαγραφή από τους φίλους' ) ).click( function() {
				Profile.DeleteFriend( 0 );
			} );
			$( this )
			.parent()
			.removeClass( 'addfriend' )
			.addClass( 'deletefriend' )
			.end()
			.fadeIn( 400 )		
		} );
		//Coala.Warm( 'user/relations/new' , { userid : userid } );
	},
	DeleteFriend : function( relationid , theuserid ) {
		$( 'div.sidebar div.basicinfo div.deletefriend a' ).fadeOut( 400 , function() {
			$( this ).empty().append( document.createTextNode( 'Προσθήκη στους φίλους' ) ).click( function() {
				return false;
			} );
			$( this )
			.parent()
			.removeClass( 'deletefriend' )
			.addClass( 'addfriend' )
			.end()
			.fadeIn( 400 );
		} );
		
	}
};
$( document ).ready( function() {
	if ( $( 'div#profile' )[ 0 ] ) {
		var notiflist = $( 'div#profile div.main div.notifications div.list' )[ 0 ] ? $( 'div#profile div.main div.notifications div.list' )[ 0 ] : false;
		var notiflistheight = $( notiflist )[ 0 ].offsetHeight;
		$( 'div#profile div.main div.notifications div.list div.event' ).mouseover( function() {
			$( this ).css( "border" , "1px dotted #666" ).css( "padding" , "4px" );
		} )
		.mouseout( function() {
			$( this ).css( "border" , "0" ).css( "padding" , "5px" );
		} );
		$( 'div#profile div.main div.notifications div.expand a' ).click( function() {
			if ( $( notiflist ).hasClass( 'invisible' ) ) {
				$( 'div#profile div.main div.notifications div.expand a' )
				.css( "background-image" , 'url( "' + ExcaliburSettings.imagesurl + 'arrow_up.png" )' )
				.attr( {
					title : 'Απόκρυψη'
				} );
				$( notiflist ).removeClass( 'invisible' ).animate( { height : notiflistheight } , 400 );
			}
			else {
				$( 'div#profile div.main div.notifications div.expand a' )
				.css( "background-image" , 'url( "' + ExcaliburSettings.imagesurl + 'arrow_down.png" )' )
				.attr( {
					title : 'Εμφάνιση'
				} );
				$( notiflist ).animate( { height : "0" } , 400 , function() {
					$( notiflist ).addClass( 'invisible' );
				} );
			}
			return false;
		} );
	}
} );