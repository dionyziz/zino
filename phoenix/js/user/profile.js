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
	},
    AntisocialAddFriend : function ( userid ) {
        setTimeout( function() {
            $( '#antisocial' ).slideUp( 'slow' );
        }, 1201 );
        $( '#antisocial div' ).animate( {
            opacity: 0
        }, 200, 'swing', function() {
            $( '#antisocial div' ).html( '<strong>Έγινε προσθήκη</strong>' ).animate( {
                opacity: 1
            }, 200 )
        } );
        this.AddFriend( userid );
    }
};
