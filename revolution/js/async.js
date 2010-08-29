var Async = {
    Go: function( href ){
        var link = href.length ? href : './';
        if( typeof( User ) != 'string' ){
            window.location = href;
        }
        axslt( $.get( link ), 'call:html', function(){
            //Close Chat
            if( Chat.Visible ){
                Chat.Toggle();
            }
            // Run Unload Function from previous Master Template
            if( typeof( Routing[ window.MasterTemplate ].Unload ) == 'function' ){
                Routing[ window.MasterTemplate ].Unload();
            }
            // Replace the world
            $( '#world' ).empty(); 
            $( this ).find( '#world' ).children().appendTo( '#world' );
            //set new Title, html id and hash
            $( 'title' ).text( $( this ).find( 'title' ).text() );
            $( 'html' ).attr( 'id', $( this ).filter( 'html' ).attr( 'id' ) );
            window.location.hash = href;
            Async.hash = window.location.hash.substr( 1 );
            //set new Master Template and run
            window.MasterTemplate = $( this ).filter( 'html' ).attr( 'id' ).split( '-' ).join( '.' );
            Routing[ MasterTemplate ].Init();
            Chat.BindClick();
            $( '.time:not(.processed)' ).load();
        } );
    },
    Init: function(){
        Async.hash = window.location.hash.substr( 1 );
        setInterval( function(){
            if( window.location.hash.substr( 1 ) != Async.hash ){
                Async.hash = window.location.hash.substr( 1 );
                Async.Go( Async.hash );
            }
        }, 100 );
        
        if( window.location.hash.length ){
            Async.Go( window.location.hash.substr( 1 ) );
        }
        $( 'a:not(:data(events)):not([href^=http])' ).live( 'click', function( e ){
            if( e.ctrlKey || e.shiftKey ){
                return;
            }
            var path = window.location.href.split( '#' )[ 0 ];
            if( path[ path.length - 1 ] == '/' ){
                path = path.substr( 0, path.length - 1 );
            }
            if( path != Generator ){
                window.location = Generator + '#' + $( this ).attr( 'href' );
            }
            Async.Go( $( this ).attr( 'href' ) );
            return false;
        });
    }
};
