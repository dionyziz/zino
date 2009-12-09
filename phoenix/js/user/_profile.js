var Profile = {
	Mine: false,
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
			// I KILL you! Write normal code! ...And there is no fucking "display:hidden".
			$( this ).parent().css( 'display' , 'none' ).append( document.createTextNode( 'Έγινε προσθήκη' ) ).fadeIn( 400 );
		} );
		Coala.Warm( 'user/relations/new' , { userid: userid } );
		return false;
	},
	DeleteFriend : function( userid ) {
		$( 'div.sidebar div.basicinfo div a span.s_deletefriend' ).parent().fadeOut( 400 , function() {
			$( this ).parent().css( 'display' , 'none' ).append( document.createTextNode( 'Έγινε διαγραφή' ) ).fadeIn( 400 );
		} );
		Coala.Warm( 'user/relations/delete' , { userid: userid } );		
		return false;
	},
    ShowFriendLinks : function( isfriend, userid ) { // only called when viewing others' profiles
    	var text;
        
        if ( !isfriend ) {
            text = document.createTextNode( 'Προσθήκη στους φίλους' );
            $( 'div.sidebar div.basicinfo div.friendedit' )
            .addClass( 'common' )
            .removeClass( 'friendedit' )
            .find( 'a' ).click( function() {
                Profile.AddFriend( userid );
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
                Profile.DeleteFriend( userid );
                return false;
            } )
            .append( text )
			.find( 'span' ).addClass( 's_deletefriend' );
        }
    },
    ShowOnlineSince : function( lastonline ) {
        if ( lastonline ) {
            text = document.createTextNode( lastonline );
            $( 'div.sidebar > div.basicinfo > dl.online > dd' ).append( text ); 
        }
        else {
            $( 'div.sidebar > div.basicinfo > dl.online' ).hide();
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
            }, 200 );
        } );
        this.AddFriend( userid );
        return false;
    },
    CheckBirthday : function ( year, month, day ) {
        var Now = new Date();
        var age = Now.getFullYear() - year;
        if ( Now.getMonth() < month - 1
             || (  Now.getMonth() == month - 1
                && Now.getDate() < day ) ) {
            --age;
        }
        $( '#birthday + dd' ).html( age ); // real age, based on user date settings, not on server date (to avoid server date differences and server-side HTML chunk caching)
        if ( Now.getDate() == day && Now.getMonth() == month - 1 ) {
            $( '#birthday' ).html( '<img src="' + ExcaliburSettings.imagesurl + 'cake.png" alt="Χρόνια πολλά!" title="Χρόνια πολλά!" /> <strong>Μόλις έγινε</strong>' );
        }
    },
    Tweet: {
        Delete : function () {
            Coala.Warm( 'status/new', { message: '' } );
            $( 'div.tweetactive' ).remove();
            $( '#tweetedit' ).jqmHide();
        },
        Renew: function ( message ) {
            if ( message === '' ) {
                return Profile.Tweet.Delete();
            }
            $( 'div.tweetactive div.tweet a span' ).empty()[ 0 ].appendChild( document.createTextNode( message ) );
            $( '#tweetedit form input' )[ 0 ].value = message;
            Coala.Warm( 'status/new', { message: message } );
            $( '#tweetedit' ).jqmHide();
        }
    },
    Player: {
		Setsong: function( content ){
			$( '#mplayersearchmodal div.search' ).removeClass( 'loading' );
			
			$( '.sidebar .mplayer .player, .sidebar .mplayer .addsong' ).remove();
			var div = document.createElement( 'div' );
			$( div ).html( content ).children().prependTo( '.sidebar .mplayer' );
			$( '#mplayersearchmodal' ).jqmHide().find( '.input' );
			Profile.Player.Initialize();
		},
		SelectSong: function( songid ){
			$( '#mplayersearchmodal div.search' ).addClass( 'loading' );
			$( '#mplayersearchmodal' ).jqmHide();
			$( '#profile .mplayer div:first' ).text( 'Αποθήκευση προφίλ...' );
			Coala.Warm( 'user/profile/selectsong', { songid: songid } );
		},
		RemoveWidget: function(){
			$( '#profile .mplayer div:first' ).text( 'Αποθήκευση προφίλ...' );
			Coala.Warm( 'user/profile/removewidget', {} );
		},
		SubmitSearch: function(){
			$( '#mplayersearchmodal div.search' ).addClass( 'loading' );
			$( '#mplayersearchmodal' ).animate( {
				top: "15%"
			}, 'normal' ).css({
				MozBorderRadiusBottomright: 4,
				MozBorderRadiusBottomleft: 4,
			}).find( '.list' ).slideDown( 'normal' );
			
			Coala.Cold( 'user/profile/searchsongs', { query: $( '#mplayersearchmodal .input input:first' ).val() } );
		},
		Addsongs: function( songs ){
			$( '#mplayersearchmodal div.search' ).removeClass( 'loading' );
			$( '#mplayersearchmodal table tbody tr' ).remove();
			var results = songs.result.Return;
			if( !results.length ){
				if( $( '#mplayersearchmodal .list div' ).length ){
					return false;
				}
				$( '#mplayersearchmodal .list' ).prepend( 
					$( document.createElement( 'div' ) )
						.text( 'Δε βρέθηκαν αποτελέσματα στην αναζήτησή σου. Δοκίμασε ξανά.' )
						.css({
							position: 'absolute',
							top: 25,
							left: 10
						})
					);
					$( '#mplayersearchmodal table thead tr' ).addClass( 'hidden' );
				return false;
			}
			$( '#mplayersearchmodal .list div' ).remove();
			$( '#mplayersearchmodal table thead tr' ).removeClass( 'hidden' );

			for( var i in results ){
				var song = results[ i ];
				
				var tr = document.createElement( 'tr' );
				var td = $( document.createElement( 'td' ) )
					.append( document.createElement( 'div' ) ).children()
						.append( $( document.createElement( 'div' ) ).addClass( 'text' ) )
						.append( $( document.createElement( 'div' ) ).addClass( 'fade' ) ).end();
					
				$( td ).clone().find( 'div.text' ).addClass( 'name' ).text( song.SongName ).attr( 'title', song.SongName ).end().appendTo( tr );
				$( td ).clone().find( 'div.text' ).addClass( 'artist' ).text( song.ArtistName ).attr( 'title', song.ArtistName ).end().appendTo( tr );
				$( td ).clone().find( 'div.text' ).addClass( 'album' ).text( song.AlbumName ).attr( 'title', song.AlbumName ).end().appendTo( tr );
				
				$( tr ).attr( 'id', 'song_' + song.SongID ).appendTo( '#mplayersearchmodal table tbody' );
			}
		},
		CorrectMidPosition: function(){
			var left = ( $( window ).width() - $( '#mplayersearchmodal' ).width() ) / 2;
			if( left < 0 ){
				left = 0;
			}
			$( '#mplayersearchmodal' ).css( 'left', left );
		},
		Initialize: function(){
			$( '.sidebar .mplayer .player' ).hover( function(){
				$( this ).children( '.toolbox' ).stop( 1, 1 ).fadeIn( 'fast' );
			}, function(){
				$( this ).children( '.toolbox' ).stop( 1, 1 ).fadeOut( 'fast' );
			});
			$( '.mplayer .toolbox .delete' ).one( 'click', function(){
				Profile.Player.RemoveWidget();
			} );
			$( '#mplayersearchmodal' ).jqmAddTrigger( '.sidebar .mplayer .toolbox .search, .sidebar .mplayer .addsong' );
			
			$( '.sidebar .mplayer .addsong' ).show();
		},
		MyProfileOnLoad: function(){
			//to avoid code running without reason
			if( !$( '#mplayersearchmodal' ).length ){
				return false;
			}
			
			Profile.Player.Initialize();
			$( '#mplayersearchmodal .input input:first' ).attr( 'default', $( '#mplayersearchmodal .input input:first' ).val() )
				.focus( function(){
					if( $( this ).val() == $( this ).attr( 'default' ) ){
						$( this ).val( '' );
					}
				}).blur( function(){
					if( $( this ).val() == '' ){
						$( this ).val( $( this ).attr( 'default' ) );
					}
				}).keypress( function( e ){
					if( e.keyCode == 13 ){
						Profile.Player.SubmitSearch();
						return false;
					}
				}).siblings( '.search' ).click( function(){
					if( !$( this ).hasClass( 'loading' ) ){
						Profile.Player.SubmitSearch();
					}
					return false;
				}).mousedown( function(){
					$( this ).addClass( 'active' );
				}).mouseup( function(){
					$( this ).removeClass( 'active' );
				}).mouseout( function(){
					$( this ).removeClass( 'active' );
				});
			
			$( '#mplayersearchmodal table tbody tr' ).live( 'click', function(){
				Profile.Player.SelectSong( $( this ).attr( 'id' ).split( '_' )[ 1 ] );
			});
			$( '#mplayersearchmodal .list' ).mousedown( function(){ return false; });
			
			$( '#mplayersearchmodal' ).keypress( function( e ){
				if( e.keyCode == 27 ){
					$( this ).jqmHide();
				}
			}).find( '.toolbar .exit' ).click( function(){
				$( '#mplayersearchmodal' ).jqmHide();
			});
			//preloading images
			var loader = new Image( 15, 15 );
			loader.src = "http://static.zino.gr/phoenix/ajax-loader.gif";
			var loader2 = new Image( 15, 15 );
			loader2.src = "http://static.zino.gr/phoenix/search-button.png";
			Profile.Player.CorrectMidPosition();
			$( window ).resize( Profile.Player.CorrectMidPosition );
		}
    },
    Easyuploadadd : function ( imageid ) {
        var uplalbid = $( '#easyphotoupload div.modalcontent div ul li.selected' ).attr( 'id' ).substr( 6 );
        Coala.Warm( 'user/profile/easyuploadadd' , { imageid : imageid , albumid : uplalbid } );
    },
	Abuse: {
		Init: function ( username ) {
			if ( !Profile.Mine ) {
				//$( '#reportabusemodal' ).modal( '#reportabuse a.report' );
				$( $( '#reportabusemodal div.buttons a' )[ 1 ] ).click( function () {
					Profile.Abuse.Hide();
					return false;
				} );
				$( $( '#reportabusemodal div.buttons a' )[ 0 ] ).click( function () {
					$( '#reportabuse a.report' ).hide();
					Coala.Warm( 'about/contact', {
						reason: $( '#reportreason' )[ 0 ].value,
						comments: $( '#reportcomments' )[ 0 ].value,
						abuseusername: username
					} );
					setTimeout( Profile.Abuse.Hide, 2000 );
					$( '#reportabuse form' )[ 0 ].innerHTML = '<strong>Η αναφορά σας αποθηκεύτηκε.</strong><br /><br />Θα την εξετάσουμε το συντομότερο δυνατό.';
					$( '#reportabuse form' )[ 0 ].style.textAlign = 'center';
					return false;
				} );
				$( '#reportabusemodal' )[ 0 ].style.left = Math.round( ( $( window ).width() - $( '#reportabusemodal' ).width() ) / 2 ) + 'px';
				$( '#reportabuse a.report' ).show();
			}
		},
		Hide: function () {
			$( '#reportabusemodal' ).jqmHide();
		}
	},
    OnLoad: function ( username ) {
        Coala.Cold( 'admanager/showad', { f: function ( html ) {
            $( 'div.ads' )[ 0 ].innerHTML = html;
        } } );
		Profile.Abuse.Init( username );
    },
    MyProfileOnLoad: function () {
		Profile.Mine = true;
		
        $( '#tweetedit' ).modal( $( 'div.tweetbox div.tweet div a' ) );
        //$( '#easyphotoupload' ).modal( 'div#profile div.main div.photos ul li.addphoto a' );
		//$( '#mplayersearchmodal' ).modal( '.sidebar .mplayer .toolbox .search, .sidebar .mplayer .addsong' );

        Profile.Player.MyProfileOnLoad();
		$( 'div#profile div.main div.photos ul li.addphoto a' ).click( function() {
            if ( !$( '#easyphotoupload div.modalcontent div.uploaddiv' )[ 0 ] ) {
                Coala.Cold( 'user/profile/easyupload' , {} );
            }
            return false;
        } );
        $( 'div.tweetactive div.tweet a' ).click( function () {
            var win = $( '#tweetedit' )[ 0 ];
            var links = $( win ).find( 'a' );
            $( links[ 0 ] ).click( function () { // save
                Profile.Tweet.Renew( $( win ).find( 'input' )[ 0 ].value );
                return false;
            } );
            $( links[ 1 ] ).click( function () { // delete
                Profile.Tweet.Delete();
                return false;
            } );
            $( win ).find( 'form' ).submit( function () {
                Profile.Tweet.Renew( $( win ).find( 'input' )[ 0 ].value );
                return false;
            } );
            var inp = $( win ).find( 'input' )[ 0 ];
            inp.select();
            inp.focus();
            return false;
        } );
    },
    FetchContacts: function( subdomain ) {
        Coala.Cold( 'user/profile/fetchcontacts', { 'subdomain': subdomain, f: function ( html ) {
            $( 'div.contacts' )[ 0 ].innerHTML = html;
        } } );
    }
};
