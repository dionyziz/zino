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
		$( 'div.sidebar div.basicinfo div.addfriend a' ).animate( { opacity : "0" } , 400 , function() {
			$( this )
			.css( 'display' , 'none' )
			.empty()
			.append( document.createTextNode( 'Διαγραφή από τους φίλους' ) )
			.click( function() {
				return false;
			} );
			$( $( this )[ 0 ].parentNode )
			.removeClass( 'addfriend' )
			.addClass( 'deletefriend' );	
		} );
		Coala.Warm( 'user/relations/new' , { userid : userid } );
	},
	DeleteFriend : function( relationid , theuserid ) {
		$( 'div.sidebar div.basicinfo div.deletefriend a' ).animate( { opacity : "0" } , 400 , function() {
			$( this )
			.css( 'display' , 'none' )
			.empty()
			.append( document.createTextNode( 'Προσθήκη στους φίλους' ) )
			.click( function() {
				return false;
			} );
			$( $( this )[ 0 ].parentNode )
			.removeClass( 'deletefriend' )
			.addClass( 'addfriend' );
		} );
		Coala.Warm( 'user/relations/delete' , { relationid : relationid , theuserid : theuserid } );
		
	}
};
/*
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
*/