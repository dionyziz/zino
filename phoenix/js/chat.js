function() {
    var lis = $( 'ol li' );
    var txt = $( 'textarea' );
    
    lis[ lis.length - 1 ].scrollIntoView();
    
    if ( txt.length ) {
        Kamibu.ClickableTextbox( txt[ 0 ], true, '#111', '#999' );
        txt.keyup( function ( event ) {
            switch ( event.keyCode ) {
                case 13: // return
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
}();

Frontpage = {};
Frontpage.Shoutbox = { 
    OnMessageArrival: function( shoutid, shouttext, who ) {
        if ( $( '#s_' + shoutid ).length ) {
            return; // already received it
        }
        if ( who.name == User && typeof who.self == 'undefined' ) {
            return; // server sent back what we've already added preliminarily -- ignore
        }
        
        var li = document.createElement( 'li' );
        li.id = 's_' + shoutid;
        var div = document.createElement( 'div' );
        
        div.className = 'text';
        div.appendChild( document.createTextNode( shouttext ) );
        
        li.innerHTML = '<span class="time"></span> <strong>' + who.name + '</strong> ';
        li.appendChild( div );
        $( 'ol' )[ 0 ].appendChild( li );
        li.scrollIntoView();
        
        var txt = $( 'textarea' );
        if ( txt.length ) {
            txt.scrollIntoView();
        }
        
        return li;
    },
    OnStartTyping: function ( gender, name ) {
    },
    OnStopTyping: function ( gender, name ) {
    }
};
