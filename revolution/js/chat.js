function innerxml( node ) {
    return (node.xml || (new XMLSerializer()).serializeToString(node) || "").replace(
        new RegExp("(^<\\w*" + node.tagName + "[^>]*>)|(<\\w*\\/\\w*" + node.tagName + "[^>]*>$)", "gi"), "");
}
var ExcaliburSettings = {
    Production: true
};

var Chat = {
     Visible: false,
     Inited: false,
     ChannelsLoaded: {},
     ChannelByUserId: {},
     CurrentChannel: 0,
     Loading: false,
     GetOnline: function () {
        $( '#onlineusers' ).css( { opacity: 0.5 } );
        $.get( 'users/online', {}, function ( res ) {
            var users = $( res ).find( 'user' );
            var user;
            var online = $( '#onlineusers' );
            var name;
            var html = '<li class="selected world" id="u0">Zino</li>';
            online.css( { opacity: 1 } );
            online = online[ 0 ];
            for ( i = 0; i < users.length; ++i ) {
                user = users[ i ];
                name = $( user ).find( 'name' ).text();
                html += '<li id="u' + $( user ).attr( 'id' ) + '">' + name + '</li>';
            }
            online.innerHTML = html;
            $( '#onlineusers li' ).click( function () {
                $( '#onlineusers li' ).removeClass( 'selected' );
                $( this ).addClass( 'selected' );
                var userid = this.id.split( 'u' )[ 1 ];
                if ( userid == 0 ) {
                    Chat.Show( 0 );
                }
                else {
                    Chat.ShowPrivate( userid );
                }
            } );
        }, 'xml' );
     },
     HistoryFromXML: function ( res ) {
        var channelid = $( res ).find( 'channel' ).attr( 'id' );
        if ( $( '#chatmessages_' + channelid ).length == 0 ) {
            $( '#chatmessages' )[ 0 ].innerHTML += '<ol style="" class="chatchannel" id="chatmessages_' + channelid + '" style="display:none"></ol>';
        }
        var history = $( '#chatmessages_' + channelid )[ 0 ];
        var messages = $( res ).find( 'discussion comment' );
        var text;
        var html = '';
        
        for ( i = 0; i < messages.length; ++i ) {
            text = innerxml( $( messages[ i ] ).find( 'text' )[ 0 ] );
            author = $( messages[ i ] ).find( 'author name' ).text();
            html += '<li><strong>' + author + '</strong> <span class="text">' + text + '</span></li>';
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
     Init: function () {
         $( '.col2' )[ 0 ].innerHTML +=
             '<div style="" id="chat">'
                 + '<div class="userlist">'
                     + '<ol id="onlineusers"></ol>'
                 + '</div>'
                 + '<div class="textmessages">'
                     + '<div class="loading" style="display:none">Λίγα δευτερόλεπτα υπομονή...</div>'
                     + '<div id="chatmessages"></div>'
                     + '<div id="outgoing"><div><textarea style="color:#ccc">Στείλε ένα μήνυμα</textarea></div></div>'
                 + '</div>'
             + '</div>';
         Chat.Show( 0 );
         $( '#chat textarea' ).keydown( function ( e ) {
             switch ( e.keyCode ) {
                case 27: // ESC
                    this.value = '';
                    $( this ).blur();
                case 13: // enter
                    Chat.SendMessage( Chat.CurrentChannel, this.value );
                    this.value = '';
             }
         } ).keyup( function ( e ) {
             switch ( e.keyCode ) {
                 case 13: // enter
                    this.value = '';
             }
         } );
         Kamibu.ClickableTextbox( $( '#chat textarea' )[ 0 ], true, 'black', '#ccc' );
         document.domain = 'zino.gr';
         Comet.Init( Math.random() * bigNumber, 'universe.alpha.zino.gr' );
         Chat.Join( 'zino' );
         Chat.Join( User );
         Chat.Inited = true;
         var bigNumber = 123456789;
     },
     SendMessage: function ( channelid, text ) {
         var li = document.createElement( 'li' );
         li.innerHTML = '<strong>' + User + '</strong> <span class="text">' + text + '</span>';
         $( '#chatmessages_' + channelid )[ 0 ].appendChild( li );
         $( '#chatmessages_' + channelid )[ 0 ].lastChild.scrollIntoView();
         var lastChild = $( '#chatmessages_' + channelid )[ 0 ].lastChild;
         $.post( 'chat/message/create', {
            channelid: channelid,
            text: text
         }, function ( res ) {
            $( lastChild ).find( 'span' )[ 0 ].innerHTML = innerxml( $( res ).find( 'text' )[ 0 ] );
         }, 'xml' );
     },
     Join: function ( channelid ) {
         // Listen to push messages here
         Comet.Subscribe( 'chat/messages/list/' + channelid );
         Comet.Subscribe( 'chat/typing/list/' + channelid );
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
                    channelid = $( res ).find( 'channel' ).attr( 'id' );
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
     // switches to given channel; loads it if not yet lo
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
         if ( !Chat.Inited ) {
             Chat.Init();
         }
         if ( Chat.Visible ) {
             $( '.col2 > div' ).show(); 
             $( '#chat' ).hide();
         }
         else {
             $( '.col2 > div' ).hide();
             $( '#chat' ).show();
             Chat.GetOnline();
         }
         Chat.Visible = !Chat.Visible;
     }
};

