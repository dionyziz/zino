var Profile = {
    AntisocialCalled : false,
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
        if ( !this.AntisocialCalled ) {
            return this.AntisocialAddFriend( userid );
        }
		$( 'div.sidebar div.basicinfo div.addfriend a' ).hide( 400 , function() {
			$( this )
			.parent()
			.css( 'display' , 'hidden' )
			.append( document.createTextNode( 'Έγινε προσθήκη' ) )
			.show( 400 );
		} );
		Coala.Warm( 'user/relations/new' , { userid : userid } );
		return false;
	},
	DeleteFriend : function( relationid , theuserid ) {
		$( 'div.sidebar div.basicinfo div.deletefriend a' ).hide( 400 , function() {
			$( this )
			.parent()
			.css( 'display' , 'hidden' )
			.append( document.createTextNode( 'Έγινε διαγραφή' ) )
			.show( 400 );
		} );
		Coala.Warm( 'user/relations/delete' , { relationid : relationid , theuserid : theuserid } );		
		return false;
	},
    ShowFriendLinks : function( relationstatus , id ) {
        alert( 'showfriendlinks' );
        if ( relationstatus ) {
            var text = document.createTextNode( 'Προσθήκη στους φίλους' );
            $( 'div.sidebar div.basicinfo div.friendedit' )
            .addClass( 'addfriend' )
            .removeClass( 'friendedit' )
            .show()
            .find( 'a' )/*.onclick( function() {
                Profile.AddFriend( id );
                return false;
            } );*/
            .append( text );
        }
        if ( relationstatus ) {
            var text = document.createTextNode( 'Διαγραφή από τους φίλους' );
            $( 'div.sidebar div.basicinfo div.friendedit' )
            .addClass( 'deletefriend' )
            .removeClass( 'friendedit' )
            .show()
            .find( 'a' )/*.onclick( function() {
                Profile.DeleteFriend( id );
                return false;
            } );*/
            .append( text );
        }
        //if relationstatus is anything else don't do something, user views his own profile
    },
    AntisocialAddFriend : function ( userid ) {
        this.AntisocialCalled = true;
        if ( !$( '#antisocial' )[ 0 ] ) {
            this.AddFriend( userid );
            return;
        }
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
        return false;
    }
};
