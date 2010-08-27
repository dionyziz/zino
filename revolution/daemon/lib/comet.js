var server = require( 'http' ).createServer( 8125, 'presence.zino.gr' );

server.on( 'request', function( req, res ) {
    if ( req.url == '/subscribe' ) {
        Comet.Subscribe( req, res );
        return;
    }
    if ( req.url == '/publish' ) {
        Comet.Publish( req, res );
        return;
    }
} );

Comet = {
    Channels: {},
    MessagePool: {},
    Subscribers: {},
    Publish: function( request, response ) {
        if ( request.method != 'POST' ) {
            response.writeHead( 405 ); //Method not allowed
            response.end();
            return;
        }
        var message = '';
        request.on( 'data', function ( chunk ) {
            message += chunk;
        } );
        request.on( 'end', function () {
            var channel = '';
            try {
                channel = require( 'url' ).parse( request.url, true ).query.channel;
            }
            catch ( e ) {
                console.log( e ); 
            }

            if ( typeof Comet.Channels[ channel ] === 'undefined' ) {
                response.writeHead( 202 );
                response.end();
                return;
            }
            
            for ( subscriber in Comet.Channels[ channel ] ) {
                
                if ( typeof request.headers[ 'content-type' ] === 'undefined' ) {
                    Comet.Subscribers[ subscriber ].Tunnel.writeHead( 200 );
                    Comet.Subscribers[ subscriber ].Tunnel.end( message );
                }

                Comet.Subscribers[ subscriber ].Tunnel.writeHead( 200, { 'Content-Type': request.headers[ 'content-type' ] } );
                Comet.Subscribers[ subscriber ].Tunnel.end( message );

            }
        } );
    },
    Subscribe: function( request, response ) {
        if( request.method != 'GET' ) {
            response.writeHead( 405 );
            response.end();
            return;
        }

        var channels = [];
        var sessionid = '';
        try {
            channels = require( 'url' ).parse( request.url, true ).query.channels.split( ',' );
            sessionid = require( 'querystring' ).parse( request.headers.cookie, '; ' ).sessionid;
        }
        catch ( e ) {
            console.log( e );
            return;
        }

        for ( var i = 0; i < channels.length; ++i ) {
            if ( typeof Comet.Channels[ channels[ i ] ] === 'undefined' ) {
                Comet.Channels[ channels[ i ] ] = {;
            }
            Comet.Channels[ channels[ i ] ][ sessionid ] = true; //Value doesn't matter
        }
        Comet.Subscribers[ sessionid ] = {
            Tunnel: response,
            Channels: channels
        }
    }

}
