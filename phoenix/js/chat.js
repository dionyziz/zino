$( function() {
    var lis = $( 'ol li' );
    var txt = $( 'textarea' );
    
    lis[ lis.length - 1 ].scrollIntoView();
    
    if ( txt.length ) {
        Kamibu.ClickableTextbox( txt[ 0 ], true, '#111', '#999' );
        txt.keydown( function ( event ) {
            switch ( event.keyCode ) {
                case 13: // return
                    // send message
                    var node = Frontpage.Shoutbox.OnMessageArrival( 0, txt[ 0 ].value, { 'name': User } );
                    Coala.Warm( 'shoutbox/new' , { text: txt[ 0 ].value, node: node } );
                    txt[ 0 ].value = '';
                    break;
                case 27: // escape
                    txt[ 0 ].value = '';
                    break;
            }
        } );
    }
} );

Frontpage = {};
Frontpage.Shoutbox = { 
    OnMessageArrival: function( shoutid, shouttext, who ) {
        var li = document.createElement( 'li' );
        var div = document.createElement( 'div' );
        
        div.className = 'text';
        div.appendChild( document.createTextNode( shouttext ) );
        
        li.innerHTML = '<span class="time"></span> <strong>' + who.name + '</strong> ';
        li.appendChild( div );
        $( 'ol' )[ 0 ].appendChild( li );
        li.scrollIntoView();
        
        return li;
    },
    OnStartTyping: function ( gender, name ) {
    },
    OnStopTyping: function ( gender, name ) {
    }
};
