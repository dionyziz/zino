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
        
        $( '#birthday + dd' ).html( Now.getFullYear() - year ); // real age, based on user date settings, not on server date (to avoid server date differences and server-side HTML chunk caching)
        if ( Now.getDate() == day && Now.getMonth() == month - 1 ) {
            $( '#birthday' ).html( '<img src="' + ExcaliburSettings.imagesurl + 'cake.png" alt="Χρόνια πολλά!" title="Χρόνια πολλά!" /> <strong>Μόλις έγινε</strong>' );
        }
    },
    Tweet: {
        Delete: function () {
            alert( 'deleting tweet' );
            Coala.Warm( 'status/new', { message: '' } );
            $( 'div.tweetactive' ).remove();
            $( '#tweetedit' ).jqmHide();
        },
        Renew: function ( message ) {
            if ( message === '' ) {
                return Tweet.Delete();
            }
            $( 'div.tweetactive div.tweet a span' ).empty()[ 0 ].appendChild( document.createTextNode( message ) );
            $( '#tweetedit form input' )[ 0 ].value = message;
            Coala.Warm( 'status/new', { message: message } );
            $( '#tweetedit' ).jqmHide();
        }
    },
    MyProfileOnLoad: function () {
        $( '#tweetedit' ).jqm( {
            trigger : 'div.tweetbox div.tweet div a',
            overlayClass : 'mdloverlay1'
        } );
        $( 'div.tweetactive div.tweet a' ).click( function () {
            var win = $( '#tweetedit' )[ 0 ];
            var links = $( win ).find( 'a' );
            $( links[ 0 ] ).click( function () { // save
                Profile.Tweet.Renew( $( win ).find( 'input' )[ 0 ].value );
                return false;
            } );
            $( links[ 2 ] ).click( function () { // delete
                Profile.Tweet.Delete();
                return false;
            } );
            $( win ).find( 'form' ).submit( function () {
                Profile.Tweet.Renew( $( win ).find( 'input' )[ 0 ].value );
                alert( 'value is ' + inp.value );
                return false;
            } );
            $( win ).find( 'input' ).keyup( function( event ) {
                if ( event.keyCode == 13 ) {
                    alert( 'enter hit' );
                    $( win ).find( 'form' ).submit();
                }
            } );
            var inp = $( win ).find( 'input' )[ 0 ];
            inp.select();
            inp.focus();
            return false;
        } );
        $( 'div.mood img' ).css( {
            'cursor': 'pointer'
        } ).click( function () {
            var self = this;
            window.document.body.style.cursor = 'wait';
            self.style.cursor = 'wait';
            Coala.Cold( 'user/settings/moodpicker', { 'func' : function ( html ) {
                $( self ).replaceWith( html );
                var f = MoodDropdown.Select;
                MoodDropdown.Select = function ( id, moodid, who ) {
                    f( id, moodid, who );
                    Settings.Save( false );
                };
                $( 'div.mood div.moodpicker div.view a' ).css( {
                    'padding': '19px 2px'
                } );
                $( 'div.mood div.moodpicker' ).css( {
                    'margin-left': '160px',
                    'z-index': '10'
                } );
                $( 'div.mood div.moodpicker div.view' ).css( {
                    'position': 'relative'
                } );
                $( 'div.mood div.moodpicker div.view img.selected' ).css( {
                    'position': 'relative',
                    'top': '-53px'
                } );
                var g = MoodDropdown.Push;
                document.title = 0;
                MoodDropdown.Push = function ( who ) {
                    $( 'div#profile div.sidebar' ).css( {
                        'overflow': 'visible'
                    } );
                    $( 'div.mood div.moodpicker div.view' ).css( {
                        'top': '-94px'
                    } );
                    g( who );
                };
                var h = MoodDropdown.Unpush;
                MoodDropdown.Unpush = function () {
                    h();
                    $( 'div#profile div.sidebar' ).css( {
                        'overflow': 'hidden'
                    } );
                    $( 'div.mood div.moodpicker div.view' ).css( {
                        'top': '0'
                    } );
                };
                window.document.body.style.cursor = 'default';
                MoodDropdown.Push( $( 'div.moodpicker' )[ 0 ] );
            } } );
        } );
    }
};

