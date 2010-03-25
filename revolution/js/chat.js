 var Chat = {
     Visible: false,
     Inited: false,
     GetOnline: function () {
        $( '#onlineusers' ).css( { opacity: 0.5 } );
        $.get( 'users/online', {}, function ( res ) {
            var users = $( res ).find( 'user' );
            var user;
            var online = $( '#onlineusers' );
            var name;
            online.css( { opacity: 1 } );
            online = online[ 0 ];
            online.innerHTML = '<li class="selected">Zino</li>';
            for ( i = 0; i < users.length; ++i ) {
                user = users[ i ];
                name = $( user ).find( 'name' ).text();
                online.innerHTML += '<li>' + name + '</li>';
            }
        }, 'xml' );
     },
     GetMessages: function ( channelid ) {
         $.get( 'chat/' + channelid + '/messages', {}, function ( res ) {
            var history = $( '#chatmessages_' + channelid )[ 0 ];
            var messages = $( res ).find( 'discussion comment' );
            var text;

            history.innerHTML = '';
            for ( i = 0; i < messages.length; ++i ) {
                text = $( messages[ i ] ).find( 'text' ).text();
                author = $( messages[ i ] ).find( 'author name' ).text();
                history.innerHTML += '<li><strong>' + author + '</strong>: ' + text + '</li>';
            }
         }, 'xml' );
     },
     Join: function ( channelid ) {
         $( '#chatmessages' )[ 0 ].innerHTML += '<ol style="list-style: none; padding: 0; margin: 0" id="chatmessages_' + channelid + '"></ol>';
         Chat.GetMessages( channelid );
     },
     Init: function () {
         $( '.col2' )[ 0 ].innerHTML +=
             '<div style="background-color: white; position: fixed; top: 5px; bottom: 5px; right: 5px; left: 5%; border: 1px solid black; -moz-border-radius: 5px 5px 5px 5px; z-index: 10;" id="chat">'
                 + '<div style="float: right; width: 10%; height: 100%; border-left: 1px solid black;">'
                     + '<ol id="onlineusers" style="padding: 5px; margin: 0pt; list-style: none outside none;">'
                     + '<li>Κοιτάμε ποιος είναι online...</li>'
                     + '</ol>'
                 + '</div>'
                 + '<div style="height: 100%; margin-right: 10%;">'
                     + '<div style="padding: 5px;" id="chatmessages"></div>'
                 + '</div>'
             + '</div>';
         Chat.Join( 0 );
         Chat.Inited = true;
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

