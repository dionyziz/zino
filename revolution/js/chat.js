function innerxml( node ) {
    return (node.xml || (new XMLSerializer()).serializeToString(node) || "").replace(
        new RegExp("(^<\\w*" + node.tagName + "[^>]*>)|(<\\w*\\/\\w*" + node.tagName + "[^>]*>$)", "gi"), "");
}
var ExcaliburSettings = {
    Production: true
};

var Chat = {
     Visible: false,
     Loaded: false,
     ChannelsLoaded: {},
     ChannelByUserId: {},
     CurrentChannel: 0,
     Loading: false,
     UserId: 0,
     Authtoken: '',
     PreviousPageSelected: 0,
     NameClick: function () {
         $( '#onlineusers li' ).removeClass( 'selected' );
         $( this ).addClass( 'selected' );
         Chat.Unflash( this.id.substr( 1 ) );
         var userid = this.id.split( 'u' )[ 1 ];
         if ( userid === 0 ) {
             Chat.Show( 0 );
         }
         else {
             Chat.ShowPrivate( userid );
         }
     },
     GetOnline: function () {
        $( '#onlineusers' ).css( { opacity: 0.5 } );
        $.get( 'users/online', {}, function ( res ) {
            var users = $( res ).find( 'user' );
            var user;
            var online = $( '#onlineusers' );
            var name;

            online.css( { opacity: 1 } );
            online = online[ 0 ];
            for ( i = 0; i < users.length; ++i ) {
                user = users[ i ];
                name = $( user ).find( 'name' ).text();
                Chat.OnUserOnline( $( user ).attr( 'id' ), name );
            }
            $( '#onlineusers li' ).click( Chat.NameClick );
        }, 'xml' );
     },
     HistoryFromXML: function ( res ) {
        var channelid = $( res ).find( 'chatchannel' ).attr( 'id' );
        if ( $( '#chatmessages_' + channelid ).length === 0 ) {
            $( '#chatmessages' )[ 0 ].innerHTML += '<ol style="" class="chatchannel" id="chatmessages_' + channelid + '" style="display:none"></ol>';
        }
        var history = $( '#chatmessages_' + channelid )[ 0 ];
        var messages = $( res ).find( 'discussion comment' );
        var text;
        var html = '';
        var shoutid;
        
        for ( var i = 0; i < messages.length; ++i ) {
            text = innerxml( $( messages[ i ] ).find( 'text' )[ 0 ] );
            author = $( messages[ i ] ).find( 'author name' ).text();
            shoutid = $( messages[ i ] ).attr( 'id' );

            html += '<li id="' + shoutid + '"><strong';
            if ( author == User ) {
                html += ' class="self"';
            }
            html += '>';
            html += author;
            html += '</strong> <span class="text">' + text + '</span></li>';
        }
        history.innerHTML = html;
     },
     GetMessages: function ( channelid, callback ) {
         $.get( 'chat/messages', { channelid: channelid }, function ( res ) {
             Chat.HistoryFromXML( res );
             callback( res );
         }, 'xml' );
     },
     LoadHistory: function ( channelid, callback ) {
         Chat.GetMessages( channelid, callback );
     },
     Load: function () {
         if ( typeof User == 'undefined' ) {
             window.location.href = 'login';
             return;
         }
         Chat.Show( 0 );
         $( '#chat textarea' ).keydown( function ( e ) {
             switch ( e.keyCode ) {
                case 27: // ESC
                    this.value = '';
                    $( this ).blur();
                    break;
                case 13: // enter
                    Chat.SendMessage( Chat.CurrentChannel, this.value );
                    this.value = '';
                    $( this ).blur();
                    $( this ).focus();
             }
         } ).keyup( function ( e ) {
             if ( e.keyCode == 13 ) { // enter
                this.value = '';
             }
         } );
         Kamibu.ClickableTextbox( $( '#chat textarea' )[ 0 ], 'Γράψε ένα μήνυμα', 'black', '#ccc' );
         Chat.Loaded = true;
     },
     Sound: {
         Ready: false,
         Ding: function () {
             if ( !Chat.Sound.Ready ) {
                 $( document.body ).prepend( '<div id="jquery_jplayer"></div>' );
                 $( '#jquery_jplayer' ).ready( function () {
                     Chat.Sound.Ready = true;
                     this.element.jPlayer( "setFile", "http://static.zino.gr/revolution/sound/glass.mp3" );
                     Chat.Sound.Ding();
                     return;
                 } );
             }
             $( '#jquery_jplayer' ).jPlayer( 'play' );
         }
     },
     Init: function () {
        $( '#chatbutton' ).click( function () {
             Chat.Toggle();
             return false;
        } );
        document.domain = 'zino.gr';
        var bigNumber = 123456789;
        $.get( 'session', function ( res ) {
            Chat.UserId = $( res ).find( 'user' ).attr( 'id' );
            Chat.Authtoken = $( res ).find( 'authtoken' ).text();
            Comet.Init( Math.random() * bigNumber, 'universe.alpha.zino.gr' );
            Chat.Join( '0' );
            Chat.Join( Chat.UserId + ':' + Chat.Authtoken );
            Comet.Subscribe( 'presence', Chat.OnPresenceChange );
        } );
        $( document.body ).append(
             '<div style="display:none" id="chat">'
                 // + '<div class="xbutton">&laquo; Πίσω</div>'
                 + '<div class="userlist">'
                     + '<ol id="onlineusers"><li class="selected world" id="u0">Zino</li></ol>'
                 + '</div>'
                 + '<div class="textmessages">'
                     + '<div class="loading" style="display:none">Λίγα δευτερόλεπτα υπομονή...</div>'
                     + '<div id="chatmessages"></div>'
                     + '<div id="outgoing"><div><textarea style="color:#ccc">Στείλε ένα μήνυμα</textarea></div></div>'
                 + '</div>'
             + '</div>' );
        $( '#onlineusers li' ).click( Chat.NameClick );
     },
     SendMessage: function ( channelid, text ) {
         if ( text.replace( /^\s+/, '' ).replace( /\s+$/, '' ).length === 0 ) {
             // empty message
             return;
         }

         var li = document.createElement( 'li' );
         li.innerHTML = '<strong class="self">' + User + '</strong> <span class="text">' + text + '</span>';
         $( '#chatmessages_' + channelid )[ 0 ].appendChild( li );
         $( '#chatmessages_' + channelid )[ 0 ].lastChild.scrollIntoView();
         var lastChild = $( '#chatmessages_' + channelid )[ 0 ].lastChild;

         $.post( 'chat/message/create', {
            channelid: channelid,
            text: text
         }, function ( res ) {
             var shoutid = $( res ).find( 'comment' ).attr( 'id' );
                
            if ( document.getElementById( shoutid ) ) {
                // already received this message through comet
                $( lastChild ).remove(); // remove duplicate
            }
            // didn't receive it through comet yet; update the innerHTML and ids
            // when it's received through comet, it'll be ignored
            $( lastChild ).find( 'span' )[ 0 ].innerHTML = innerxml( $( res ).find( 'text' )[ 0 ] );
            $( lastChild )[ 0 ].id = shoutid;
         }, 'xml' );
     },
     OnMessageArrival: function ( res ) {
         var channelid = $( res ).find( 'chatchannel' ).attr( 'id' );
         if ( $( '#chatmessages_' + channelid ).length === 0 ) {
             // if there is no chat history placeholder for the particular channel
             // then create one
             $( '#chatmessages' )[ 0 ].innerHTML += '<ol style="display:none" class="chatchannel" id="chatmessages_' + channelid + '"></ol>';
         }
         var history = $( '#chatmessages_' + channelid )[ 0 ];
         var messages = $( res ).find( 'discussion comment' );
         var text;
         var html = '';
         var li;
         var shoutid;
         var author;

         for ( var i = 0; i < messages.length; ++i ) {
             shoutid = $( messages[ i ] ).attr( 'id' );
             author = $( messages[ i ] ).find( 'author name' ).text();
             if ( document.getElementById( shoutid ) ) {
                 // message has already been received
                 continue;
             }
             if ( author == User ) {
                 continue; // don't display my own messages; they've already been added by the SendMessage function
             }
             text = innerxml( $( messages[ i ] ).find( 'text' )[ 0 ] );
             li = document.createElement( 'li' );
             li.id = shoutid;
             li.innerHTML = '<strong>' + author + '</strong> <span class="text">' + text + '</span></li>'; 
             history.appendChild( li );
         }
         if ( Chat.CurrentChannel == channelid ) {
             li.scrollIntoView();
         }
         else {
             var userid, cid, found, username;

             found = false;
             for ( userid in Chat.ChannelByUserId ) {
                 cid = Chat.ChannelByUserId[ userid ];
                 if ( cid == channelid ) {
                     found = true;
                     Chat.Flash( userid, text );
                     if ( $( '#u' + userid ).hasClass( 'flash' ) ) {
                         username = $( '#u' + userid ).find( 'span.username' ).text();
                     }
                     else {
                         username = $( '#u' + userid ).text();
                     }
                     Chat.PopBubble( userid, username, text, channelid );
                 }
             }
             if ( !found ) {
                 $.get( 'chat/' + channelid, {}, function ( res ) { 
                     var users = $( res ).find( 'user' );
                     for ( var i = 0; i < users.length; ++i ) {
                         userid = $( users[ i ] ).attr( 'id' );
                         username = $( users[ i ] ).find( 'name' ).text();
                         if ( userid != Chat.UserId ) {
                             Chat.ChannelByUserId[ userid ] = channelid;
                             if ( !$( '#u' + userid ).length ) {
                                 Chat.OnUserOnline( userid, username );
                             }
                             Chat.Flash( userid, text );
                             break;
                         }
                     }
                     Chat.PopBubble( userid, username, text, channelid );
                 } );
             }
         }
     },
     PopBubble: function ( userid, username, text, channelid ) {
         if ( !$( '#chatbubbles' ).length ) {
             $( document.body ).append( '<div id="chatbubbles"></div>' );
         }
         if ( !$( '#popbubble_' + userid ).length ) {
             $( '#chatbubbles' ).append( '<div class="chatbubble" id="popbubble_' + userid + '"><img src="" alt="' + username + '" /><div class="text"><span><strong>' + username + '</strong> λέει:</span></div></div>' );
             $( '#popbubble_' + userid + ' .text' )[ 0 ].appendChild( document.createTextNode( text ) );
             $( '#popbubble_' + userid ).click( function () {
                 Chat.Toggle();
                 Chat.Show( channelid );
                 $( '#popbubble_' + userid ).remove();
             } );
             $.get( 'users/' + username, { verbose: 0 }, function ( res ) {
                 $( '#popbubble_' + userid + ' img' )[ 0 ].src = $( res ).find( 'media' ).attr( 'url' );
             } );
             var pos = 0;

             ( function () {
                 pos += 0.1;
                 if ( pos > Math.PI ) {
                     pos -= Math.PI;
                 }
                 if ( $( '#popbubble_' + userid ).length ) {
                     $( '#popbubble_' + userid ).css( { opacity: 0.5 + 0.5 * Math.sin( pos ) } );
                     setTimeout( arguments.callee, 50 );
                 }
             } )();
         }
     },
     OnUserOnline: function ( userid, username ) {
         var lis = $( '#onlineusers li' );
         var li;
         var compare;

         
         if ( $( '#u' + userid ).length ) {
             return;
         }
         username = username.toLowerCase();

         for ( var i = 1; i < lis.length; ++i ) {
             li = lis[ i ];
             if ( $( li ).hasClass( 'flash' ) ) {
                 // username of person to compare with 
                 compare = $( li ).find( 'span.username' ).text();
             }
             else {
                 compare = $( li ).text();
             }
             if ( username < compare.toLowerCase() ) {
                 break;
             }
         }
         var newuser = document.createElement( 'li' );
         newuser.appendChild( document.createTextNode( username ) );
         newuser.id = 'u' + userid;
         newuser.onclick = Chat.NameClick;

         if ( i == lis.length ) {
             $( '#onlineusers' ).append( newuser );
         }
         else {
             $( '#onlineusers' )[ 0 ].insertBefore( newuser, lis[ i ] );
         }
     },
     OnUserOffline: function ( userid, username ) {
         $( '#u' + userid ).remove();
     },
     Flash: function ( userid, message ) {
         // TODO: Multiple participants
         if ( $( '#u' + userid ).hasClass( 'flash' ) ) {
             return;
         }
         $( '#u' + userid ).addClass( 'flash' ).html(
            '<span class="username">' + $( '#u' + userid ).text() + '</span>'
            + '<span class="text">' + message + '</span>'
         );
     },
     Unflash: function ( userid ) {
         if ( !$( '#u' + userid ).hasClass( 'flash' ) ) {
             return;
         }
         $( '#u' + userid ).removeClass( 'flash' );
         var uname = $( '#u' + userid + ' .username' ).text();
         $( '#u' + userid ).text( uname );
     },
     Join: function ( channelid ) {
         // Listen to push messages here
         Comet.Subscribe( 'chat/messages/list/' + channelid, Chat.OnMessageArrival );
         Comet.Subscribe( 'chat/typing/list/' + channelid, Chat.OnMessageArrival );
     },
     OnPresenceChange: function ( res ) {
         var method = $( res ).find( 'operation' ).attr( 'method' );
         if ( method == 'create' ) {
             Chat.OnUserOnline( $( res ).find( 'user' ).attr( 'id' ), $( res ).find( 'user name' ).text() );
         }
         else { // method == 'delete'
             Chat.OnUserOffline( $( res ).find( 'user' ).attr( 'id' ), $( res ).find( 'user name' ).text() );
         }
     },
     NowLoading: function () {
         document.body.style.cursor = 'wait';
         $( '.chatchannel' ).hide();
         $( '.textmessages .loading' ).show();
     },
     DoneLoading: function () {
         document.body.style.cursor = 'default';
         $( '.textmessages .loading' ).hide();
     },
     // switch to a channel given a userid; if not loaded, it will load it
     ShowPrivate: function ( userid ) {
         var channelid;
         if ( typeof Chat.ChannelByUserId[ userid ] == 'undefined' ) {
             Chat.NowLoading();
             $.get(
                'chat/messages', {
                    channelid: 0,
                    userid: userid
                },
                function ( res ) {
                    channelid = $( res ).find( 'chatchannel' ).attr( 'id' );
                    Chat.ChannelByUserId[ userid ] = channelid;
                    Chat.HistoryFromXML( res );
                    Chat.ChannelsLoaded[ channelid ] = true;
                    Chat.DisplayChannel( channelid );
                    Chat.DoneLoading();
                }, 'xml'
             );
         }
         else {
             channelid = Chat.ChannelByUserId[ userid ];
             Chat.DisplayChannel( channelid );
         }
     },
     // switches to given channel; loads it if not yet loaded
     Show: function ( channelid ) {
         if ( typeof Chat.ChannelsLoaded[ channelid ] == 'undefined' ) {
             Chat.NowLoading();
             Chat.LoadHistory( channelid, function () {
                 Chat.ChannelsLoaded[ channelid ] = true;
                 Chat.DisplayChannel( channelid );
                 Chat.DoneLoading();
             } );
         }
         else {
             Chat.DisplayChannel( channelid );
         }
     },
     // switch to an already loaded channel
     DisplayChannel: function ( channelid ) {
         $( '.chatchannel' ).hide();
         $( '#chatmessages_' + channelid ).show();
         if ( $(' #chatmessages_' + channelid + ' li' ).length ) {
             $( '#chatmessages_' + channelid )[ 0 ].lastChild.scrollIntoView();
         }
         Chat.CurrentChannel = channelid;
     },
     // hide/show the chat application
     Toggle: function () {
         if ( !Chat.Loaded ) {
             Chat.Load();
         }
         if ( Chat.Visible ) {
             $( '#chat' ).hide();
             $( '#content' ).show();
             $( $( 'div.bar ul li' )[ Chat.PreviousPageSelected ] ).addClass( 'selected' );
             $( '#chatbutton' ).parent().removeClass( 'selected' );
         }
         else {
             $( '#chat' ).show();
             $( '#content' ).hide();
             var menu = $( 'div.bar ul li' );
             for ( var i = 0; i < menu.length; ++i ) {
                 if ( menu[ i ].className == 'selected' ) {
                     Chat.PreviousPageSelected = i;
                     $( menu[ i ] ).removeClass( 'selected' );
                     break;
                 }
             }
             $( '#chatbutton' ).parent().addClass( 'selected' );
             Chat.GetOnline();
         }
         Chat.Visible = !Chat.Visible;
     }
};
