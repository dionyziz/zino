var prevW = -1;
var prewH = -1;

var Dashboard = {
    HandleResize: function() {
        var h = 0;
        var w = 0;
        
        if ( typeof window.innerHeight != 'undefined' && window.innerHeight ) {
            h = window.innerHeight;
            w = window.innerWidth;
        }
        else if ( typeof document.documentElement.clientHeight != 'undefined' && document.documentElement.clientHeight ) {
            h = document.documentElement.clientHeight;
            w = document.documentElement.clientWidth;
        }
        else {
            h = document.body.clientHeight;
            w = document.body.clientWidth;
        }
        
        if ( w == prevW && h == prevH ) {
            return;
        }
        
        prevW = w;
        prevH = h;
        
        var notifications = $( '#notifications' )[ 0 ];
        var frontpage = $( '#frontpage' )[ 0 ];
        
        $( '#nowbar' )[ 0 ].style.height = h - 5 + 'px';
        $( '#chat' )[ 0 ].style.height = Math.floor( ( h - 18 ) / 2 ) + 'px';
        $( '#friends' )[ 0 ].style.height = Math.floor( ( h - 18 ) / 2 ) + 'px';
        $( '#friends ol' )[ 0 ].style.height = Math.floor( ( h - 220 ) / 2 ) + 'px';
        $( '#chat ol' )[ 0 ].style.height = Math.floor( ( h - 220 ) / 2 ) + 'px';
        $( '#frontpage' )[ 0 ].style.width = document.body.offsetWidth - $( '#nowbar' )[ 0 ].offsetWidth + 'px';
        $( '#frontpage' )[ 0 ].style.height = h + 'px';
        
        notifications.style.marginLeft = Math.round( frontpage.offsetWidth / 2 - notifications.offsetWidth / 2 ) - 20 + 'px';

        var lis = $( '#chat ol li' );

        if ( lis.length ) {
            lis[ lis.length - 1 ].scrollIntoView();
        }
    },
    OnLoad: function() {
        Kamibu.ClickableTextbox( $( 'textarea' )[ 0 ], true, 'black', '#aaa' );
        Kamibu.ClickableTextbox( $( 'input' )[ 0 ], true, 'black', '#aaa' );
        Dashboard.HandleResize();
    },
    Notifications: {
        Visible: true,
        Toggle: function () {
            Dashboard.Notifications.Visible = !Dashboard.Notifications.Visible;
            $( '#notifybox border > *' ).each( function ( i ) {
                if ( Notifications.Visible ) {
                    this.style.display = '';
                }
                else {
                    this.style.display = 'none';
                }
            } );
        }
    }
};

window.onresize = Dashboard.HandleResize;

( function() {
    $( '#notifybox .minimize' ).click( 
        function () {
            Dashboard.Notifications.Toggle();
            return false;
        }
    );
    var txt = $( '#chat textarea' );
    var lis = $( '#chat ol li' );

    if ( lis.length ) {
        lis[ lis.length - 1 ].scrollIntoView();
    }
    
    if ( txt.length ) {
        Kamibu.ClickableTextbox( txt[ 0 ], true, '#111', '#999' );
        txt.keyup( function ( event ) {
            switch ( event.keyCode ) {
                case 13: // return
                    if ( txt[ 0 ].value.replace( /^\s+/, '' ).replace( /\s+$/, '' ) == '' ) {
                        txt[ 0 ].value = '';
                        return;
                    }
                    
					var div = document.createElement( 'div' );
					div.appendChild( document.createTextNode( txt[ 0 ].value ) );
					
                    // send message
                    var node = Frontpage.Shoutbox.OnMessageArrival( 0, div.innerHTML, { 'name': User, 'self': true } );
                    Coala.Warm( 'shoutbox/new' , { 
						text: txt[ 0 ].value,
						channel: 0,
						node: node, 
						f: function () {
							var lis = $( 'ol li' );
                            if ( lis.length ) {
                                lis[ lis.length - 1 ].scrollIntoView();
                            }
							Frontpage.Shoutbox.AutoScroll = true;
						}
					} );
                    txt[ 0 ].value = '';
                    break;
                case 27: // escape
                    txt[ 0 ].value = '';
                    break;
                default:
                    // send an "I'm typing" request
                    if ( Frontpage.Shoutbox.TypingCancelTimeout !== 0 ) { // if we were about to send a "I've stopped typing" request...
                        clearTimeout( Frontpage.Shoutbox.TypingCancelTimeout ); // delay it for a while
                    }
                    Frontpage.Shoutbox.TypingCancelTimeout = setTimeout( function () {
                        Coala.Warm( 'shoutbox/typing', { 'typing': false, 'channel': 0 } ); // OK send the actual "I've stopped typing" request
                    }, 10000 ); // send an "I've stopped typing" request if I haven't touched the keyboard for 10 seconds
                    if ( Frontpage.Shoutbox.TypingUpdated ) { // We've already sent an "I'm typing" request recently; don't do it again for every keystroke!
                        return;
                    }
                    Frontpage.Shoutbox.TypingUpdated = true; // OK we're about to send an "I'm typing" request now; make sure we don't send one again very soon
                    setTimeout( function () { // After we've sent an "I'm typing" request, we don't want to send more. But only for 10 seconds; we'll send another "I'm typing" request if I'm still typing by then.
                        Frontpage.Shoutbox.TypingUpdated = false;
                    }, 10000 );
                    Coala.Warm( 'shoutbox/typing', { 'typing': true, 'channel': 0 } ); // OK send the actual request            }
            }
        } );
    }
} )();

Frontpage = {};
Frontpage.Shoutbox = {
    Typing: [], // people who are currently typing (not including yourself)
    TypingUpdated: false, // whether "I am typing" has been sent recently (we don't want to send it for every keystroke!)
    TypingCancelTimeout: 0, // this timeout is used to send a "I have stopped typing" request
    BottomScroll: 0, // number got from scrollTop() last time we AutoScroll'ed to bottom
    AutoScroll: true, // if user scrolls AutoScroll will be false and we won't scroll down on new message until user scrolls to BottomScroll or lower
	ActiveChannel: 0,
    LoadHistory: function () {
        Coala.Cold(
            'chat/history', {
                channelid: channelid,
                offset: $( '#chat li.text' ).length,
                f: function ( data ) {
                    document.body.style.cursor = 'default';

                    if ( data.length < 50 ) {
                        $( '#messages_' + channelid + ' li.history' ).hide();
                    }
                    else {
                        $( '#messages_' + channelid + ' li.history a' ).show();
                    }

                    var ol = $( '#messages_' + channelid + ' ol' )[ 0 ];
                    var at = $( ol ).find( 'li.text' )[ 0 ];
                    var i;

                    for ( i in data ) {
                        var item = data[ i ];
                        var li = document.createElement( 'li' );
                        li.id = 's_' + item.id;
                        var div = document.createElement( 'div' );
        
                        div.className = 'text';
                        div.innerHTML = item.text + '';
                        // alert( item.text );
        
                        var strong = document.createElement( 'strong' );
                        strong.appendChild( document.createTextNode( item.who.name ) );
        
                        if ( item.who.name == User ) {
                            strong.className = 'u';
                        }
        
                        li.innerHTML = '<span class="time"></span> ';
                        li.appendChild( strong );
                        li.appendChild( document.createTextNode( ' ' ) );
                        li.appendChild( div );
                        li.className = 'text';

                        ol.insertBefore( li, at );
                    }
                }
            }
        );
    },
	Init: function() {
		var f = function () {
			var lis = $( 'div#chat li' );

            if ( lis.length ) {
                lis[ lis.length - 1 ].scrollIntoView();
            }

			Frontpage.Shoutbox.BottomScroll = $( '#chat ol' ).scrollTop();
		};
		window.onresize = f;
		$( '#chat ol' ).scroll( function() {
			var scrll = $( this ).scrollTop();
			if ( Frontpage.Shoutbox.AutoScroll && scrll < Frontpage.Shoutbox.BottomScroll ) { // user scrolled up
				// turn this off until we establish a better system
				// Frontpage.Shoutbox.AutoScroll = false; // disable AutoScrolling
				return;
			}
			if ( !Frontpage.Shoutbox.AutoScroll && scrll >= Frontpage.Shoutbox.BottomScroll ) { // user scrolled to last known bottom
				Frontpage.Shoutbox.BottomScroll = scrll; // update last known bottom
				Frontpage.Shoutbox.AutoScroll = true;
				return;
			}
			if ( scrll > Frontpage.Shoutbox.BottomScroll ) {
				Frontpage.Shoutbox.BottomScroll = scrll;
			}
		} );
		f();
        $( 'li.history a' ).click( function () {
            var div = this.parentNode.parentNode.parentNode;

            $( this ).hide();

            document.body.style.cursor = 'wait';
            Frontpage.Shoutbox.LoadHistory();

            return false;
        } );
	},
    OnMessageArrival: function( shoutid, shouttext, who, channel ) {
        Frontpage.Shoutbox.OnStopTyping( { 'name': who.name }, channel );
        
        if ( $( '#s_' + shoutid ).length ) {
            return; // already received it
        }
        if ( who.name == User && typeof who.self == 'undefined' ) {
            return; // server sent back what we've already added preliminarily -- ignore
        }
        
        var lis = $( 'li.typing' );
        for ( var i = 0; i < lis.length; ++i ) {
            var li = lis[ i ];
            li.parentNode.removeChild( li );
        }
        
        var li = document.createElement( 'li' );
        li.id = 's_' + shoutid;
        var div = document.createElement( 'div' );
        
        div.className = 'text';
        div.innerHTML = shouttext + '';
        
        var strong = document.createElement( 'strong' );
        strong.appendChild( document.createTextNode( who.name ) );
        
        if ( typeof who.self != 'undefined' ) {
            strong.className = 'u';
        }
        
        li.appendChild( strong );
        li.appendChild( document.createTextNode( ' ' ) );
        li.appendChild( div );
        li.className = 'text';
        $( '#chat ol' )[ 0 ].appendChild( li );

        if ( this.AutoScroll ) { 
            li.scrollIntoView();
            this.BottomScroll = $( 'ol' ).scrollTop();
        }
        
        Frontpage.Shoutbox.UpdateTyping();
        
        return li;
    },
    OnStartTyping: function ( who, channel ) { // received when someone starts typing
        if ( who.name == User ) { // don't show it when you're typing
            return;
        }
        for ( var i = 0; i < Frontpage.Shoutbox.Typing.length; ++i ) {
            var typist = Frontpage.Shoutbox.Typing[ i ];
            if ( typist.name == who.name ) {
                clearTimeout( typist.timeout );
                // in case the typing user gets disconnected and is unable to send us a 
                // "stopped typing" comet request, time it out after 20,000 milliseconds
                // of no "started typing" comet requests
                // (also in case we receive the asynchronous "I'm typing" and "I've stopped typing"
                // requests in the wrong order -- very improbable but possible)
                Frontpage.Shoutbox.Typing[ i ].timeout = setTimeout( function () {
                    Frontpage.Shoutbox.OnStopTyping( who, 0 );
                }, 20000 );
                return;
            }
        }
        who.timeout = setTimeout( function () {
            Frontpage.Shoutbox.OnStopTyping( who, 0 );
        }, 20000 ); // in case the remote party gets disconnected
        Frontpage.Shoutbox.Typing.push( who );
        Frontpage.Shoutbox.UpdateTyping();
    },
    OnStopTyping: function ( who, channel ) { // received when someone stops typing
        var found = false;
        
        for ( var i = 0; i < Frontpage.Shoutbox.Typing.length; ++i ) {
            var typist = Frontpage.Shoutbox.Typing[ i ];
            if ( typist.name == who.name ) {
                Frontpage.Shoutbox.Typing.splice( i, 1 );
                found = true;
                break;
            }
        }
        if ( !found ) {
            return;
        }
        Frontpage.Shoutbox.UpdateTyping();
    },
    UpdateTyping: function() {
        var processed = {};
        
        for ( var i = 0; i < Frontpage.Shoutbox.Typing.length; ++i ) {
            var typist = Frontpage.Shoutbox.Typing[ i ];
            if ( !$( '#typing_' + typist.name ).length ) {
                var li = document.createElement( 'li' );
                li.id = 'typing_' + typist.channel + '_' + typist.name;
                li.className = 'typing';
                li.innerHTML = '<strong>' + typist.name + '</strong> <div class="text"><em>πληκτρολογεί...</em></div>';
                $( '#chat ol' )[ 0 ].appendChild( li );
                if ( this.AutoScroll ) {
                    li.scrollIntoView();
                }
            }
            processed[ typist.channel + '_' + typist.name ] = true;
        }
        var lis = $( 'li.typing' );
        for ( var i = 0; i < lis.length; ++i ) {
            var li = lis[ i ];
            var data = li.id.split( '_' );
            var channel = data[ 1 ];
			var name = data[ 2 ];
			
            if ( typeof processed[ channel + '_' + name ] == 'undefined' ) {
                // someone stopped typing
                li.parentNode.removeChild( li );
            }
        }
    }
};
