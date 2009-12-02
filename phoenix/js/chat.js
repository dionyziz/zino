( function() {
    var txt = $( 'textarea' );
    var lis = $( 'ol li' );
    lis[ lis.length - 1 ].scrollIntoView();
    
    if ( txt.length ) {
        Kamibu.ClickableTextbox( txt[ 0 ], true, '#111', '#999' );
        txt.keyup( function ( event ) {
            switch ( event.keyCode ) {
                case 13: // return
                    if ( txt[ 0 ].value.replace( /^\s+/, '' ).replace( /\s+$/, '' ) == '' ) {
                        txt[ 0 ].value = '';
                        return;
                    }
                    
                    // send message
                    var node = Frontpage.Shoutbox.OnMessageArrival( 0, txt[ 0 ].value, { 'name': User, 'self': true } );
                    Coala.Warm( 'shoutbox/new' , { 
						text: txt[ 0 ].value,
						channel: 0,
						node: node, 
						f: function () {
							var lis = $( 'ol li' );
							lis[ lis.length - 1 ].scrollIntoView();
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
	Init: function( channels ) {
		var f = function () {
			var h = $( window ).height();
			var t = 0;
			if ( $( 'textarea' ).length ) {
				t = $( 'textarea' ).height();
				if ( $( '#tabs' ).length ) {
					t += $( '#tabs' ).height();
				}
			}
			
			$( 'ol' ).height( h - t - 30 );
			
			var lis = $( 'ol li' );
			lis[ lis.length - 1 ].scrollIntoView();
			Frontpage.Shoutbox.BottomScroll = $( 'ol' ).scrollTop();
		};
		window.onresize = f;
		$( 'ol' ).scroll( function() {
			var scrll = $( this ).scrollTop();
			if ( Frontpage.Shoutbox.AutoScroll && scrll < Frontpage.Shoutbox.BottomScroll ) { // user scrolled up
				Frontpage.Shoutbox.AutoScroll = false; // disable AutoScrolling
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
		$( '#messages_0' )[ 0 ].style.display = '';
		f();
		$( '#tabs ul li a' ).show().click( function () {
			var channelid = this.id.split( '_' )[ 1 ];
			
			$( 'ol.channelmessages' ).hide();
			$( 'ol#messages_' + channelid ).show();
			$( '#tabs li' ).removeClass( 'focus' );
			$( this.parentNode ).addClass( 'focus' );
			$( 'textarea' ).focus();
			
			Frontpage.Shoutbox.ActiveChannel = channelid;
			
			return false;
		} );
	},
    OnMessageArrival: function( shoutid, shouttext, who, channel ) {
        Frontpage.Shoutbox.OnStopTyping( { 'name': who.name } );
        
        if ( $( '#s_' + shoutid ).length ) {
            return; // already received it
        }
        if ( who.name == User && typeof who.self == 'undefined' ) {
            return; // server sent back what we've already added preliminarily -- ignore
        }
        
        var lis = $( 'li.typing' );
        for ( var i = 0; i < lis.length; ++i ) {
            var li = lis[ i ];
            var name = li.id.substr( 7 );
            
            li.parentNode.removeChild( li );
        }
        
        var li = document.createElement( 'li' );
        li.id = 's_' + shoutid;
        var div = document.createElement( 'div' );
        
        div.className = 'text';
        div.innerHTML = shouttext;
        
        var strong = document.createElement( 'strong' );
        strong.appendChild( document.createTextNode( who.name ) );
        
        if ( typeof who.self != 'undefined' ) {
            strong.className = 'u';
        }
        
        li.innerHTML = '<span class="time"></span> ';
        li.appendChild( strong );
        li.appendChild( document.createTextNode( ' ' ) );
        li.appendChild( div );
        $( 'ol' )[ 0 ].appendChild( li );

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
                    Frontpage.Shoutbox.OnStopTyping( who );
                }, 20000 );
                return;
            }
        }
        who.timeout = setTimeout( function () {
            Frontpage.Shoutbox.OnStopTyping( who );
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
        var ol = $( 'ol' )[ 0 ];
        var processed = {};
        
        for ( var i = 0; i < Frontpage.Shoutbox.Typing.length; ++i ) {
            var typist = Frontpage.Shoutbox.Typing[ i ];
            if ( !$( '#typing_' + typist.name ).length ) {
                var li = document.createElement( 'li' );
                li.id = 'typing_' + typist.name;
                li.className = 'typing';
                li.innerHTML = '<strong>' + typist.name + '</strong> <div class="text"><em>πληκτρολογεί...</em></div>';
                ol.appendChild( li );
                if ( this.AutoScroll ) {
                    li.scrollIntoView();
                }
            }
            processed[ typist.name ] = true;
        }
        var lis = $( 'li.typing' );
        for ( var i = 0; i < lis.length; ++i ) {
            var li = lis[ i ];
            var name = li.id.substr( 7 );
            
            if ( typeof processed[ name ] == 'undefined' ) {
                // someone stopped typing
                li.parentNode.removeChild( li );
            }
        }
    }
};
