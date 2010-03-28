 function innerxml( node ) {
     return (node.xml || (new XMLSerializer()).serializeToString(node) || "").replace(
     new RegExp("(^<\\w*" + node.tagName + "[^>]*>)|(<\\w*\\/\\w*" + node.tagName + "[^>]*>$)", "gi"), "");
 }

 var Chat = {
     Visible: false,
     Inited: false,
     ChannelsLoaded: {},
     ChannelByUserid: {},
     CurrentChannel: 0,
     GetOnline: function () {
        $( '#onlineusers' ).css( { opacity: 0.5 } );
        $.get( 'users/online', {}, function ( res ) {
            var users = $( res ).find( 'user' );
            var user;
            var online = $( '#onlineusers' );
            var name;
            online.css( { opacity: 1 } );
            online = online[ 0 ];
            online.innerHTML = '<li class="selected world">Zino</li>';
            for ( i = 0; i < users.length; ++i ) {
                user = users[ i ];
                name = $( user ).find( 'name' ).text();
                online.innerHTML += '<li id="u' + $( user ).attr( 'id' ) + '">' + name + '</li>';
            }
            $( '#onlineusers li' ).click( function () {
                $( '#onlineusers li' ).removeClass( 'selected' );
                $( this ).addClass( 'selected' );
            } );
        }, 'xml' );
     },
     GetMessages: function ( channelid ) {
         $.get( 'chat/' + channelid + '/messages', {}, function ( res ) {
            var history = $( '#chatmessages_' + channelid )[ 0 ];
            var messages = $( res ).find( 'discussion comment' );
            var text;

            history.innerHTML = '';
            for ( i = 0; i < messages.length; ++i ) {
                text = innerxml( $( messages[ i ] ).find( 'text' )[ 0 ] );
                author = $( messages[ i ] ).find( 'author name' ).text();
                history.innerHTML += '<li><strong>' + author + '</strong> <span class="text">' + text + '</span></li>';
            }
         }, 'xml' );
     },
     LoadHistory: function ( channelid ) {
         $( '#chatmessages' )[ 0 ].innerHTML += '<ol style="" class="chatchannel" id="chatmessages_' + channelid + '" style="display:none"></ol>';
         Chat.GetMessages( channelid );
     },
     Init: function () {
         $( '.col2' )[ 0 ].innerHTML +=
             '<div style="" id="chat">'
                 + '<div class="userlist">'
                     + '<ol id="onlineusers"></ol>'
                 + '</div>'
                 + '<div class="textmessages">'
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
                    $.post( 'chat/message/create', {
                        chanelid: Chat.CurrentChannel,
                        text: this.value
                    } );
                    this.value = '';
             }
         } );
         Kamibu.ClickableTextbox( $( '#chat textarea' )[ 0 ], true, 'black', '#ccc' );
         // Chat.Join( User );
         Chat.Inited = true;
     },
     Join: function ( channelid ) {
         // Listen to push messages here
     },
     ShowPrivate: function ( userid ) {
         if ( typeof Chat.ChannelByUserId[ userid ] == 'undefined' ) {
         }
     },
     Show: function ( channelid ) {
         if ( typeof Chat.ChannelsLoaded[ channelid ] == 'undefined' ) {
             Chat.CurrentChannel = channelid;
             Chat.LoadHistory( channelid );
         }
         $( '.chatchannel' ).hide();
         $( '#chatmessages_' + channelid ).show();
     },
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
     },
 };

