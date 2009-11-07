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
                    Coala.Warm( 'shoutbox/new' , { text: txt[ 0 ].value, node: node } );
                    txt[ 0 ].value = '';
                    break;
                case 27: // escape
                    txt[ 0 ].value = '';
                    break;
            }
        } );
    }
} )();

$( function () {
    f = function () {
        var h = 0;
        if ( typeof window.innerHeight != 'undefined' && window.innerHeight ) {
            h = window.innerHeight;
        }
        else if ( typeof document.documentElement.clientHeight != 'undefined' && document.documentElement.clientHeight ) {
            h = document.documentElement.clientHeight;
        }
        else {
            h = document.body.clientHeight;
        }
        $( 'ol' )[ 0 ].style.height = h - $( 'textarea' )[ 0 ].offsetHeight - 20 + 'px';
        
        var lis = $( 'ol li' );
        lis[ lis.length - 1 ].scrollIntoView();
    };
    f();
    window.onresize = f;
} );

Frontpage = {};
Frontpage.Shoutbox = {
    Typing: [], // people who are currently typing (not including yourself)
    OnMessageArrival: function( shoutid, shouttext, who ) {
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
        li.scrollIntoView();
        
        Frontpage.Shoutbox.UpdateTyping();
        
        return li;
    },
    OnStartTyping: function ( who ) { // received when someone starts typing
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
    OnStopTyping: function ( who ) { // received when someone stops typing
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
                li.innerHTML = '<strong>' + typist.name + '</strong> <em>πληκτρολογεί...</em>';
                ol.appendChild( li );
                li.scrollIntoView();
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
