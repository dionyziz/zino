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
     vts: [], //visible timestamps
     TimestampsToggle: function( items ){
        $( items ).each( function(){
            if( !Chat.vts.length ){
                Chat.vts.push( $( this ) );
                $( this ).show();
                return true;
            }
            if( $( this ).children( '.timestamp' ).text() - 5 * 60 * 1000 > Chat.vts[ Chat.vts.length - 1 ].children( '.timestamp' ).text()
                && $( this ).children( '.friendly' ).text() != Chat.vts[ Chat.vts.length - 1 ].children( '.friendly' ).text() ){
                Chat.vts.push( $( this ) );
                $( this ).show();
            }
        });
     },
     TimestampCheck: function( node ){ //not in use, for now
        var next = $( node ).closest( 'li' ).next().children( '.when' );
        var prev = $( node ).closest( 'li' ).prev().children( '.when' );
        if( next.length ){
            if( next.children( '.friendly' ).text() == $( node ).children( '.friendly' ).text() ) {
                $( node ).hide();
            }
            else{
                $( node ).show();
            }
        }
        if( prev.length ){
            if( prev.children( '.friendly' ).text() == $( node ).children( '.friendly' ).text() ) {
                prev.hide();
            }
            else{
                prev.show();
            }
        }
     },
     NameClick: function () {
         var userid = this.id.split( 'u' )[ 1 ];
         if ( userid == Chat.UserId ) {
             return;
         }
         $( '#onlineusers li' ).removeClass( 'selected' );
         $( this ).addClass( 'selected' );
         Chat.Unflash( this.id.substr( 1 ) );
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
            Chat.CreateChannelHTML( channelid );
        }
        var history = $( '#chatmessages_' + channelid + ' ol' )[ 0 ];
        var messages = $( res ).find( 'discussion comment' );
        var text;
        var html = '';
        var shoutid;
        
        for ( var i = 0; i < messages.length; ++i ) {
            text = innerxml( $( messages[ i ] ).find( 'text' )[ 0 ] );
            author = $( messages[ i ] ).find( 'author name' ).text();
            shoutid = $( messages[ i ] ).attr( 'id' );

            html += '<li id="' + shoutid + '"><span class="when time">' + $( messages[ i ] ).find( 'date' ).text()  + '</span><strong';
            if ( author == User ) {
                html += ' class="self"';
            }
            html += '>';
            html += author;
            html += '</strong> <span class="text">' + text + '</span></li>';
        }
        history.innerHTML = html;
        $( '.when:not(.processedtime)' ).load();
        Chat.TimestampsToggle( $( '.when' ) );
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
         var typingSent = false;
         var typingTimeout = 0;

         $( '#onlineusers li' ).click( Chat.NameClick );
         Kamibu.ClickableTextbox( $( '#chat .search input' )[ 0 ], 'Αναζήτηση', 'black', '#aaa' );
         if ( typeof User == 'undefined' ) {
             Kamibu.Go( 'login' );
             return false;
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
         $( '#chat textarea' ).keydown( function () {
             clearTimeout( typingTimeout );
             typingTimeout = setTimeout( function () { // if user has not touched the keyboard for 4 seconds, show them as not typing explicitly
                $.post( 'chat/typing', {
                    typing: 0
                } );
                typingSent = false;
             }, 4000 );
             if ( !typingSent ) { // sent the fact that we are still typing every 10 seconds; just so that remote client doesn't "timeout" and thinks we've stopped
                 $.post( 'chat/typing', {
                     channelid: Chat.CurrentChannel,
                     typing: 1
                 } ); 
                 typingSent = true;
                 setTimeout( function () {
                     typingSent = false;
                 }, 10000 );
             }
         } );
         Chat.Loaded = true;
         return true;
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
         // keep this function short
         // these things run on every page load
         // if you just want some initialization operation that only needs to
         // run when the chat is opened
         // use the Chat.Load() function, not this.
         // Chat.Load() is only called once.
        $( '#chatbutton' ).click( function () {
             Chat.Toggle();
             return false;
        } );
        document.domain = 'zino.gr';
        $.get( 'session', function ( res ) {
            Chat.UserId = $( res ).find( 'user' ).attr( 'id' );
            Chat.Authtoken = $( res ).find( 'authtoken' ).text();
            Comet.Init();
            Chat.Join( '0' );
            Chat.Join( Chat.UserId + ':' + Chat.Authtoken );
            Comet.Subscribe( 'presence', Chat.OnPresenceChange );
        } );
        $( document.body ).append(
             '<div style="display:none" id="chat">'
                 // + '<div class="xbutton">&laquo; Πίσω</div>'
                 + '<div class="userlist">'
                     + '<div class="search"><input type="text" value="Αναζήτηση"></div>'
                     + '<ol id="onlineusers"><li class="selected world" id="u0">Zino</li></ol>'
                 + '</div>'
                 + '<div class="textmessages">'
                     + '<div class="loading" style="display:none">Λίγα δευτερόλεπτα υπομονή...</div>'
                     + '<div id="chatmessages"></div>'
                     + '<div id="outgoing"><div><textarea style="color:#ccc">Στείλε ένα μήνυμα</textarea></div></div>'
                 + '</div>'
             + '</div>' );
     },
     SendMessage: function ( channelid, text ) {
         if ( text.replace( /^\s+/, '' ).replace( /\s+$/, '' ).length === 0 ) {
             // empty message
             return;
         }

         var li = document.createElement( 'li' );
         li.innerHTML = '<strong class="self"></strong> <span class="text"></span>';
         $( li )
            .children( 'strong' ).text( User ).end()
            .children( 'span.text' ).text( text );

         $( '#chatmessages_' + channelid + ' ol' )[ 0 ].appendChild( li );
         $( '#chatmessages_' + channelid + ' ol' )[ 0 ].lastChild.scrollIntoView();
         var lastChild = $( '#chatmessages_' + channelid + ' ol' )[ 0 ].lastChild;

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
            $( lastChild ).find( 'span.text' )[ 0 ].innerHTML = innerxml( $( res ).find( 'text' )[ 0 ] );
            $( lastChild )[ 0 ].id = shoutid;
            $( lastChild ).prepend( '<span class="when time">' + $( res ).find( 'date' ).text()  + '</span>' ).children( '.when' ).load();
            Chat.TimestampsToggle( $( lastChild ).find( '.when' ) );
         }, 'xml' );
     },
     OnMessageArrival: function ( res ) {
         var channelid = $( res ).find( 'chatchannel' ).attr( 'id' );
         Chat.CreateChannelHTML( channelid );
         var history = $( '#chatmessages_' + channelid + ' ol' )[ 0 ];
         var messages = $( res ).find( 'discussion comment' );
         var text, newmessage = false;
         var html = '';
         var li, shoutid, author;
         var container = $( '#chatmessages_' + channelid + ' div.scrollcontainer' )[ 0 ];
         var atEnd = container.offsetHeight + container.scrollTop > history.offsetHeight - 50;

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
             newmessage = true;
             text = innerxml( $( messages[ i ] ).find( 'text' )[ 0 ] );
             li = document.createElement( 'li' );
             li.id = shoutid;
             li.innerHTML = '<span class="when time">' + $( messages[ i ] ).find( 'date' ).text()  + '</span><strong>' + author + '</strong> <span class="text">' + text + '</span></li>'; 
             history.appendChild( li );
             Chat.TimestampsToggle( $( '.time:not(.processedtime)' ).load() );
            

         }
         if ( Chat.CurrentChannel == channelid ) {
             if ( typeof li != 'undefined' ) {
                 if ( atEnd ) { // if the user has already scrolled to the end show new message
                     li.scrollIntoView();
                 } // else, don't scroll them down if they're browsing the history
             }
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
             if ( !found && newmessage ) {
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
             $( '#popbubble_' + userid + ' .text' )[ 0 ].innerHTML += text;
             $( '#popbubble_' + userid ).click( function () {
                 Notifications.Hide();
                 Chat.Toggle();
                 Chat.Show( channelid );
                 $( '#popbubble_' + userid ).remove();
             } );
             $.get( 'users/' + username, { verbose: 0 }, function ( res ) {
                 if ( $( res ).find( 'avatar' ) ) {
                     $( '#popbubble_' + userid + ' img' )[ 0 ].src = $( res ).find( 'media' ).attr( 'url' );
                 }
                 else {
                     $( '#popbubble_' + userid + ' img' )[ 0 ].src = 'http://static.zino.gr/phoenix/anonymous100.jpg'; 
                 }
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

         if ( typeof User != 'undefined' && username == User ) {
             return;
         }
         
         if ( $( '#u' + userid ).length ) {
             return;
         }
         var origname = username;
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
         newuser.appendChild( document.createTextNode( origname ) );
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
         Comet.Subscribe( 'chat/typing/list/' + channelid, Chat.OnTypingStateChange );
     },
     OnTypingStateChange: function ( res ) {
         if ( User == 'dionyziz' ) {
             var channelid = $( res ).find( 'chatchannel' ).attr( 'id' );
             var username = $( res ).find( 'chatchannel user name' ).text();
             var typing = $( res ).find( 'chatchannel user' ).attr( 'typing' );

             if ( username != User ) {
                 if ( typing ) {
                     document.title = username + ' typing';
                 }
                 else {
                     document.title = username + ' stopped typing';
                 }
                 // window.title = username + ' typing in channel ' + channelid;
             }
         }
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
     CreateChannelHTML: function ( channelid ) {
         if ( $( '#chatmessages_' + channelid ).length === 0 ) {
             $( '#chatmessages' )[ 0 ].innerHTML += '<div class="chatchannel" id="chatmessages_' + channelid + '" style="display:none"><div class="scrollcontainer"><ol></ol></div></div>';
             if ( channelid == 0 ) {
                 return;
             }
             // create the user pane
             var $chatmessages = $( '#chatmessages_' + channelid );
             var $panel = $( '<div>'
                            + '<div class="userinfo">'
                            + '  <div><h3></h3><ul></ul></div>'
                            + '</div>'
                           + '</div>' );

             $chatmessages.find( '.scrollcontainer' ).css( { top: '50px' } );
             $chatmessages.prepend( $panel );
             $.get( 'chat/' + channelid, function ( res ) {
                 var users = $( res ).find( 'user' );
                 for ( var i = 0; i < users.length; ++i ) {
                     userid = $( users[ i ] ).attr( 'id' );
                     username = $( users[ i ] ).find( 'name' ).text();
                     if ( userid != Chat.UserId ) {
                         Chat.ChannelByUserId[ userid ] = channelid;
                         break;
                     }
                 }
                 $panel.find( 'h3' ).text( username );
                 $.get( 'users/' + username + '?verbose=2', function ( res ) {
                     var avatar = 'http://static.zino.gr/phoenix/anonymous100.jpg'; 
                     if ( $( res ).find( 'user avatar' ).length ) {
                         avatar = $( res ).find( 'user avatar media' ).attr( 'url' );
                     }
                     var img = '<a href="users/' + username + '"><img src="' + avatar + '" alt="' + username + '" title="Προβολή προφίλ" /></a>';
                     var lis = [];
                     if ( $( res ).find( 'gender' ).length ) {
                         if ( $( res ).find( 'gender' ).text() == 'f' ) {
                             lis.push( 'Κορίτσι' );
                         }
                         else {
                             lis.push( 'Αγόρι' );
                         }
                     }
                     if ( $( res ).find( 'age' ).length ) {
                         lis.push( $( res ).find( 'age' ).text() );
                     }
                     if ( $( res ).find( 'location' ).length ) {
                         lis.push( $( res ).find( 'location' ).text() );
                     }
                     var lihtml = '';
                     for ( var i = 0; i < lis.length; ++i ) {
                         if ( i == lis.length - 1 ) {
                             lihtml += '<li class="last">';
                         }
                         else {
                             lihtml += '<li>';
                         }
                         lihtml += lis[ i ] + '</li>';
                     }
                     $panel.find( '.userinfo' ).prepend( img );
                     $panel.find( 'ul' ).prepend( lihtml );
                 } );
             } );
         }
     },
     // switch to an already loaded channel
     DisplayChannel: function ( channelid, userid ) {
         $( '.chatchannel' ).hide();
         $( '#chatmessages_' + channelid ).show();
         if ( $(' #chatmessages_' + channelid + ' li' ).length ) {
             var messages = $( '#chatmessages_' + channelid + ' li' );
             messages[ messages.length - 1 ].scrollIntoView();
         }
         Chat.CurrentChannel = channelid;
     },
     // hide/show the chat application
     Toggle: function () {
         if ( !Chat.Loaded ) {
             if ( !Chat.Load() ) {
                 return;
             }
         }
         if ( Chat.Visible ) {
             document.title = Chat.OriginalTitle;
             $( '#chat' ).hide();
             $( '#content' ).show();
             if ( Chat.PreviousPageSelected != -1 ) {
                 $( $( 'div.bar ul li' )[ Chat.PreviousPageSelected ] ).addClass( 'selected' );
             }
             $( '#chatbutton' ).parent().removeClass( 'selected' );
         }
         else {
             Chat.OriginalTitle = document.title;
             document.title = 'Chat στο zino';
             $( '#chat' ).show();
             $( '#content' ).hide();
             var menu = $( 'div.bar ul li' );
             Chat.PreviousPageSelected = -1;
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
