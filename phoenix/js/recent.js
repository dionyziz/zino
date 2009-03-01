var Recent = {
    Events: [],
    Loading: true,
    Now: 0,
    Interval: 20,
    Resolution: 1,
    OnLoad: function () {
        document.title = 'Φόρτωση...';
        document.title = 'OnLoad';
        setInterval( Recent.GetEvents, Recent.Interval * 1000 );
        Recent.GetEvents();
    },
    OnFirstDownload: function ( now ) {
        document.title = 'Πρόσφατα στο Zino';
        $( 'div#recentevents img.loader' ).remove();
        Recent.Now = now;
        setInterval( Recent.Process, Recent.Resolution * 1000 );
    },
    GetEvents: function () {
        document.title = 'GetEvents';
        Coala.Cold( 'recent/get', { f: Recent.GotEvents } );
    },
    GotEvents: function ( events, now ) {
        if ( Recent.Loading ) {
            Recent.Loading = false;
            Recent.OnFirstDownload( now );
        }
        if ( events.length ) {
            document.title = 'GotEvents: ' + events.length + ' ( ' + events[ 0 ].created + ' / ' + Recent.Now + ' )';
        }
        for ( i = 0; i < events.length; ++i ) {
            if ( event.created < Recent.Now - Recent.Interval ) {
                continue;
            }
            Recent.Events.push( event );
        }
    },
    DisplayEvent: function ( event ) {
        document.title = 'DisplayEvent';
        var par = document.getElementById( 'recentevents' );
        var div = document.createElement( 'div' );

        div.innerHTML += event.created;
        div.className = 'event';
        par.appendChild( div );
    },
    Process: function () {
        // document.title = 'Process ' + Recent.Events.length;
        var newArray = [];
        
        for ( i = 0; i < Recent.Events.length; ++i ) {
            alert( 'Processing this' );
            var event = Recent.Events[ i ];
            if ( event.created > Recent.Now - Recent.Interval ) {
                Recent.DisplayEvent( event );
            }
            else {
                newArray.push( event );
            }
        }
        
        Recent.Events = newArray;
    }
};
Recent.OnLoad();
