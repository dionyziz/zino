var http = require( 'http' );
var libxml = require( './libxmljs' );

var server = http.createServer();
var php = http.createClient( 500, 'zino.gr' );

var ai_connection = 0;

var online = {};
var timeouts = {};

server.on( 'request', function ( req, res ) {
    res.writeHead( 200, {
        'Content-Type': 'text/html'
    } );
    if( req.url == '/' ){
        console.log( 'Sending iframe data..' );
        res.end( '<html><head></head><body><script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script><script>setTimeout( function(){ $.get( "http://presence.zino.gr:8124/connect" );}, 20 );</script></body></html>' );
        return;
    }
    if( req.url == '/users/list' ){
        console.log( 'User list request' );
        for( user in online ){
            res.write( user + "\n" );
        }
        res.end();
        return;
    }
    if( req.url == '/connect' ){
        console.log( 'Persistent connection, checking for cookies' );
        if( typeof req.headers.cookie !== 'undefined' ){
            var cookies = req.headers.cookie.split( '; ' );
            for( var i = 0; i < cookies.length; ++i ){
                cookies[ i ] = cookies[ i ].split( '=' );
                if( cookies[ i ][ 0 ] == 'zino_login_8' ){
                    var credentials = cookies[ i ][ 1 ].split( '%3A' );
                    break;
                }
            }
            if( typeof credentials === 'undefined' ){
                console.log( 'Cookie zino_login_8 not found, closing connection' );
                res.end();
                return;
            }
            var userid = credentials[ 0 ];
            req.connection_id = ai_connection++;
            
            console.log( 'Checking authtoken validity. userid=' + credentials[ 0 ] +  ' authtoken=' + credentials[ 1 ] );
            var body = 'userid=' + credentials[ 0 ] + '&authtoken=' + credentials[ 1 ];
            var request = php.request( 'POST', '/dionyziz/?resource=presence&method=create', { 
                'Host': 'zino.gr', 
                'Content-Length': body.length 
            });
            request.end( body );
            request.on( 'response', function( response ){
                response.on( 'data', function( data ){
                    var result = libxml.parseXmlString( data.toString().substr( data.toString().indexOf( '<social' ), data.toString().length ));
                    result = result.get( '//result' );
                    if( result.text() == 'SUCCESS' ){

                        console.log( 'Authtoken valid' );
                        console.log( 'User ' + credentials[ 0 ] + ' connected (Connection id = ' + req.connection_id + ')' );
                        
                        if( typeof online[ userid ]  === 'undefined' ){
                            online[ userid ] = new Array();
                        }

                        online[ userid ].push( req.connection_id );
                    }
                    else {
                        console.log( 'Invalid authtoken, closing connection' );
                        res.end();
                    }
                });
            });
            

            req.connection.on( 'end', function(){
                console.log( 'Connection ended, waiting 10s for reconnect' );
                if( typeof timeouts[ userid ] !== 'undefined' ){
                    clearTimeout( timeouts[ userid ] );
                }
                timeouts[ userid ] = setTimeout( function(){
                    delete timeouts[ userid ];
                    if( online[ userid ].length == 0 ) {
                        delete online[ userid ];
                        
                        console.log( 'User ' + userid + ' disconnected for more than 10s. Making API call' );
                        var body = 'userid=' + credentials[ 0 ];
                        var request = php.request( 'POST', '/dionyziz/?resource=presence&method=delete', { 
                            'Host': 'zino.gr', 
                            'Content-Length': body.length 
                        });
                        request.end( body );

                        console.log( 'User ' + userid + ' went offline' );
                        return;
                    }

                    console.log( 'User ' + userid + ' reconnected.' );
                }, 10000);

                online[ userid ].splice( online[ userid ].indexOf( req.connection_id ), 1 );
            });
        }
        return;
    }
    res.end();
});

server.listen( 8124, "presence.zino.gr" );
