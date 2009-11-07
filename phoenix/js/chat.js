$( function() {
    var lis = $( 'ol li' );
    
    lis[ lis.length - 1 ].scrollIntoView();
} );

Frontpage = {};
Frontpage.Shoutbox = { 
    OnMessageArrival: function( shoutid, shouttext, who ) {
        var li = document.createElement( 'li' );
        var span = document.createElement( 'span' );
        
        span.appendChild( document.createTextNode( shouttext ) );
        
        li.innerHTML = '<span class="time"></span> <strong>' + who.name + '</strong> ';
        li.appendChild( span );
        $( 'ol' )[ 0 ].appendChild( li );
        li.scrollIntoView();
    },
    OnStartTyping: function ( gender, name ) {
    },
    OnStopTyping: function ( gender, name ) {
    }
};
