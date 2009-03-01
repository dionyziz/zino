document.title = 'Φόρτωση...';
Coala.Cold( 'recent/get', { f: function ( events, now ) {
    document.title = now; // 'Πρόσφατα στο Zino';
    var par = document.getElementById( 'recentevents' );
    for ( i = 0; i < events.length; ++i ) {
        var event = events[ i ];
        var div = document.createElement( 'div' );
        div.innerHTML += event.created;
        div.className = 'event';
        par.appendChild( div );
    }
    
} } );
