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
		$( 'div.usidebar div.basicinfo div.common' ).fadeOut( 150 , function() {
			// I KILL you! Write normal code! ...And there is no fucking "display:hidden".
			$( this ).empty().css( 'padding-left' , '6px' )
            .append( document.createTextNode( 'Έγινε προσθήκη' ) ).fadeIn( 150 );
		} );
		Coala.Warm( 'user/relations/new' , { userid: userid } );
		return false;
	},
	DeleteFriend : function( userid ) {
		$( 'div.usidebar div.basicinfo div.common' ).fadeOut( 150 , function() {
            $( this ).empty().css( 'padding-left' , '6px' )
            .append( document.createTextNode( 'Έγινε διαγραφή' ) ).fadeIn( 150 );
		} );
		Coala.Warm( 'user/relations/delete' , { userid: userid } );		
		return false;
	},
    ShowFriendLinks : function( isfriend, userid ) { // only called when viewing others' profiles
    	var text;
        
        if ( !isfriend ) {
            alert( 'Is not friend' );
            $( 'div.usidebar div.basicinfo div.friendedit' )
            .addClass( 'common' )
            .removeClass( 'friendedit' )
            .find( 'a' ).click( function() {
                Profile.AddFriend( userid );
                return false;
            } )
			.find( 'span' ).addClass( 's1_0050' );
        }
        else {
            $( 'div.usidebar div.basicinfo div.friendedit' )
            .addClass( 'common' )
            .removeClass( 'friendedit' )
            .find( 'a' ).click( function() {
                Profile.DeleteFriend( userid );
                return false;
            } )
			.find( 'span' ).addClass( 's1_0051' );
        }
    },
    ShowOnlineSince : function( lastonline ) {
        if ( lastonline ) {
            text = document.createTextNode( lastonline );
            $( 'div.usidebar > div.basicinfo > dl.online > dd' ).append( text ); 
        }
        else {
            $( 'div.usidebar > div.basicinfo > dl.online' ).hide();
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
        //chorvus edits here D:
        /*$( '#tweetedit' ).jqm( {
            trigger : 'div.tweetbox div.tweet div a',
            overlayClass : 'mdloverlay1'
        } );*/
        $( '#tweetedit' ).modal( 'div.tweetbox div.tweet div a' );
        $( '#easyphotoupload' ).jqm( {
            trigger : 'div#profile div.main div.photos ul li.addphoto a',
            overlayClass : 'mdloverlay1'
        } );
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
    }
    ,
    FetchContacts: function( subdomain ) {
        Coala.Cold( 'user/profile/fetchcontacts', { 'subdomain': subdomain, f: function ( html ) {
            $( 'div.contacts' )[ 0 ].innerHTML = html;
        } } );
    }
};
