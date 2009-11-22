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
			$( '#mplayersearchmodal input[type=image]' ).removeClass( 'loading' );
			
			$( '.sidebar .mplayer .player, .sidebar .mplayer .addsong' ).remove();
			var div = document.createElement( 'div' );
			$( div ).html( content ).children().prependTo( '.sidebar .mplayer' );
			$( '#mplayersearchmodal' ).jqmHide().find( '.input' );
			Profile.Player.Initialize();
		},
		SelectSong: function( songid ){
			$( '#mplayersearchmodal input[type=image]' ).addClass( 'loading' );
			Coala.Warm( 'user/profile/selectsong', { songid: songid } );
		},
		RemoveWidget: function(){
			Coala.Warm( 'user/profile/removewidget', {} );
		},
		SubmitSearch: function(){
			$( '#mplayersearchmodal input[type=image]' ).addClass( 'loading' );
			$( '#mplayersearchmodal' ).animate( {
				top: "15%"
			}, 'normal' ).css({
				MozBorderRadiusBottomright: 4,
				MozBorderRadiusBottomleft: 4,
			}).find( '.list' ).slideDown( 'normal' );
			
			Coala.Cold( 'user/profile/searchsongs', { query: $( '#mplayersearchmodal .input input:first' ).val() } );
		},
		Addsongs: function( songs ){
			$( '#mplayersearchmodal input[type=image]' ).removeClass( 'loading' );
			$( '#mplayersearchmodal table tr:not(.head)' ).remove();
			var results = songs.result.Return;
			if( results.length == 0 ){
				$( '#mplayersearchmodal .list' )
					.prepend( $( document.createElement( 'div' ) ).text( 'Δε βρέθηκαν αποτελέσματα στην αναζήτησή σου. Δοκίμασε ξανά.' ) )
					.css({
						marginTop: 0,
						paddingTop: 0
					});
					$( '#mplayersearchmodal table tr.head' ).hide();
			}
			else{
				$( '#mplayersearchmodal .list div' ).remove();
				$( '#mplayersearchmodal table' ).css({
					marginTop: 10,
					paddingTop: 6
				});
				$( '#mplayersearchmodal table tr.head' ).show();
			}
			for( var i in results ){
				var song = results[ i ];
				
				var tr = document.createElement( 'tr' );
				var td = document.createElement( 'td' );
				$( td ).clone().text( song.SongName ).appendTo( tr );
				$( td ).clone().text( song.ArtistName ).appendTo( tr );
				$( td ).clone().text( song.AlbumName ).appendTo( tr );
				
				$( tr ).attr( 'id', 'song_' + song.SongID ).appendTo( '#mplayersearchmodal table tbody' );
			}
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
			Profile.Player.Initialize();
			$( '#mplayersearchmodal .input input:first' ).attr( 'default', $( '#mplayersearchmodal .input input:first' ).val() )
				.keydown( function(){
					if( $( this ).val() == $( this ).attr( 'default' ) ){
						$( this ).val( '' );
					}
				}).blur( function(){
					if( $( this ).val() == '' ){
						$( this ).val( $( this ).attr( 'default' ) );
					}
				}).keypress( function( e ){
					if( e.which == 13 ){
						Profile.Player.SubmitSearch();
						return false;
					}
				}).siblings( '[type=image]' ).click( function(){
					if( !$( this ).hasClass( 'loading' ) ){
						Profile.Player.SubmitSearch();
					}
					return false;
				});
			
			$( '#mplayersearchmodal table tr:not(.head)' ).live( 'click', function(){
				Profile.Player.SelectSong( $( this ).attr( 'id' ).split( '_' )[ 1 ] );
			});
			$( '#mplayersearchmodal' ).mousedown( function(){ return false; });
		}
    },
    Easyuploadadd : function ( imageid ) {
        var uplalbid = $( '#easyphotoupload div.modalcontent div ul li.selected' ).attr( 'id' ).substr( 6 );
        Coala.Warm( 'user/profile/easyuploadadd' , { imageid : imageid , albumid : uplalbid } );
    },
    OnLoad: function () {
        Coala.Cold( 'admanager/showad', { f: function ( html ) {
            $( 'div.ads' )[ 0 ].innerHTML = html;
        } } );
    },
    MyProfileOnLoad: function () {
        $( '#reportabusemodal' ).jqm( {
            trigger : '#reportabuse a.report',
            overlayClass : 'mdloverlay1'
        } );
        $( '#tweetedit' ).jqm( {
            trigger : 'div.tweetbox div.tweet div a',
            overlayClass : 'mdloverlay1'
        } );
        $( '#easyphotoupload' ).jqm( {
            trigger : 'div#profile div.main div.photos ul li.addphoto a',
            overlayClass : 'mdloverlay1'
        } );
		$( '#mplayersearchmodal' ).jqm({
			trigger: '.sidebar .mplayer .toolbox .search, .sidebar .mplayer .addsong',
			overlayClass: 'mdloverlay1'
		});
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
