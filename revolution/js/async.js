var Async = {
    Go: function( href, callback ){
        Kamibu.Go( href );
        return false;
        var link = href.length ? href : './';
        if( typeof( User ) != 'string' || $.browser.msie ){
            window.location = href;
            return;
        }
        if( $( '#world:visible' ).length ){
            $( '#world' ).stop( 1 ).fadeTo( 100, 0.5 );
        }
        axslt( $.get( link ), 'call:html', function(){
            if( Notifications.TakenOver ){
                Notifications.Release();
            }
            $( '#world' ).stop( 1 ).fadeTo( 0, 1 );
            //Close Chat
            if( Chat.Visible ){
                Chat.Toggle();
            }
            // Run Unload Function from previous Master Template
            if( typeof( Routing[ window.MasterTemplate ].Unload ) == 'function' ){
                Routing[ window.MasterTemplate ].Unload();
            }
            var world = $( this ).find( '#world' ).andSelf().filter( '#world' );
            if( world.length == 0 ){ //could not transform the xml corectly ( IE? )
                window.location = href;
            }
            var title = $( this ).find( 'title' ).text();
            var MasterTemplate = world.attr( 'class' ).split( '-' );
            MasterTemplate = MasterTemplate[ 1 ] + '.' + MasterTemplate[ 2 ];

            // Replace the world
            $( '#world' ).empty().removeClass().addClass( world.attr( 'class' ) );
            world.children().appendTo( '#world' );
            //set new Title, html id and hash
            $( 'title' ).text( title );
            $( 'html' ).attr( 'id', MasterTemplate.split( '.' ).join( '-' ) );
            window.location.hash = href;
            Async.hash = window.location.hash.substr( 1 );
            //set new Master Template and run
            window.MasterTemplate = MasterTemplate;
            Routing[ MasterTemplate ].Init();
            Chat.BindClick();
            $( '.time:not(.processed)' ).load();
            window.scroll( 0, 0 );
            if( typeof( callback ) == 'function' ){
                callback();
            }
        } );
        return false;
    },
    Init: function(){
        Async.hash = window.location.hash.substr( 1 );
        setInterval( function(){
            if( window.location.hash.substr( 1 ) != Async.hash ){
                Async.hash = window.location.hash.substr( 1 );
                Async.Go( Async.hash );
            }
        }, 100 );
        
        if( window.location.hash.length > 0 ){
            Async.Go( window.location.hash.substr( 1 ), function(){
                $( 'body' ).show();        
            });
        }
        $( 'a:not(:data(events)):not([href^=http])' ).live( 'click', function( e ){
            if( typeof( $( this )[ 0 ].onclick ) == 'function' ){
                return;
            }
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
            return Async.Go( $( this ).attr( 'href' ) );
        });
    }
};
