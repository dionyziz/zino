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
		$( 'div.sidebar div.basicinfo div a span.s_addfriend' ).parent().fadeOut( 400 , function() {
			$( this )
			.parent()
			.css( 'display' , 'hidden' )
			.append( document.createTextNode( 'Έγινε προσθήκη' ) )
			.fadeIn( 400 );
		} );
		Coala.Warm( 'user/relations/new' , { userid : userid } );
		return false;
	},
	DeleteFriend : function( relationid ) {
		$( 'div.sidebar div.basicinfo div a span.s_deletefriend' ).parent().fadeOut( 400 , function() {
			$( this )
			.parent()
			.css( 'display' , 'hidden' )
			.append( document.createTextNode( 'Έγινε διαγραφή' ) )
			.fadeIn( 400 );
		} );
		Coala.Warm( 'user/relations/delete' , { relationid : relationid } );		
		return false;
	},
    ShowFriendLinks : function( relationstatus , id ) {
        var text;
        if ( relationstatus ) {
            text = document.createTextNode( 'Προσθήκη στους φίλους' );
            $( 'div.sidebar div.basicinfo div.friendedit' )
            .addClass( 'common' )
            .removeClass( 'friendedit' )
            .find( 'a' ).click( function() {
                Profile.AddFriend( id );
                return false;
            } )
            .append( text )
			.find( 'span' ).addClass( 's_addfriend' );
        }
        else {
            text = document.createTextNode( 'Διαγραφή από τους φίλους' );
            $( 'div.sidebar div.basicinfo div.friendedit' )
            .addClass( 'common' )
            .removeClass( 'friendedit' )
            .find( 'a' ).click( function() {
                Profile.DeleteFriend( id );
                return false;
            } )
            .append( text )
			.find( 'span' ).addClass( 's_deletefriend' );
        }
        //if relationstatus is anything else don't do something, user views his own profile
    },
    ShowOnlineSince : function( lastonline ) {
        if ( lastonline ) {
            text = document.createTextNode( lastonline );
            $( 'div.sidebar div.basicinfo dl.online dd' ).append( text ); 
        }
        else {
            $( 'div.sidebar div.basicinfo dl.online' ).hide();
        }
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
    },
    CheckBirthday : function ( year, month, day ) {
        var Now = new Date();
        
        if ( Now.getDate() == day && Now.getMonth() == month - 1 ) {
            $( '#birthday' ).html( '<img src="' + ExcaliburSettings.imagesurl + 'cake.png" alt="Χρόνια πολλά!" title="Χρόνια πολλά!" /> <strong>Μόλις έγινε</strong>' );
            $( '#birthday + dd' ).html( Now.getFullYear() - year ); // real birthday year, based on user date settings, not on server date
        }
    }
};
