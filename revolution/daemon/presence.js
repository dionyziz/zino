var http = require( 'http' );
var libxml = require( './libxmljs' );
var querystring = require( 'querystring' );

var server = http.createServer();
var php = http.createClient( 500, 'zino.gr' );

var ai_connection = 0;
var online = {};
var timeouts = {};

server.on( 'request', function ( req, res ) {
    res.writeHead( 200, {
        'Content-Type': 'text/html'
    } );

    //Iframe request
    if( req.url == '/' ){
        res.end( '<html><head></head><body><script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script><script>setTimeout( function(){ $.get( "http://presence.zino.gr:8124/connect" );}, 20 );</script></body></html>' );
        return;
    }

    //User list request
    if( req.url == '/users/list' ){
        for( user in online ){
            res.write( user + "\n" );
        }
        res.end();
        return;
    }

    //Persistent Connection
    if( req.url == '/connect' ){
        if( typeof req.headers.cookie !== 'undefined' ){
            cookies = querystring.parse( req.headers.cookie, '; ' )
            if( typeof cookies[ 'zino_login_8' ] === 'undefined' ){
                res.end();
                return;
            }
            cookies  = cookies[ 'zino_login_8' ].split( ':' );
            
            var userid = cookies[ 0 ];
            var authtoken = cookies[ 1 ];
            req.connection_id = ai_connection++;
            
            console.log( 'Checking authtoken validity. userid=' + userid +  ' authtoken=' + authtoken );
            var body = 'userid=' + userid + '&authtoken=' + authtoken;
            var request = php.request( 'POST', '/petros/?resource=presence&method=create', { 
                'Host': 'zino.gr', 
                'Content-Type': 'application/x-www-form-urlencoded',
                'Content-Length': body.length 
            });
            request.write( body )
            request.close( body );
            request.on( 'response', function( response ){
                var data = '';
                response.on( 'data', function( chunk ){
                    data += chunk.toString();
                });

                response.on( 'end', function( ){
                    //libxml doesn't like proccessing instructions. Strip them from the XML and parse.
                    var xml = data.substr( data.indexOf( '<social' ), data.length );
                    try {
                        var result = libxml.parseXmlString( xml );
                        result = result.get( '//result' );
                        if( result.text() == 'SUCCESS' ){

                            console.log( 'Authtoken valid' );
                            console.log( 'User ' + userid + ' connected (Connection id = ' + req.connection_id + ')' );
                            
                            //User was previously offline
                            if( typeof online[ userid ]  === 'undefined' ){
                                online[ userid ] = new Array();
                            }
                            
                            //Add the current connetion id to the user's array of active connections.
                            online[ userid ].push( req.connection_id );
                        }
                        else {
                            console.log( 'Invalid authtoken, closing connection' );
                            res.end();
                        }
                    }
                    catch ( e ){
                        console.log( 'libxml error. argument: ' + data.toString() );
                    }
                });
            });
            
            req.connection.on( 'end', function(){
                console.log( 'Connection ended, waiting 10s for reconnect' );
                //If a previus timeout is on cancel it
                if( typeof timeouts[ userid ] !== 'undefined' ){
                    clearTimeout( timeouts[ userid ] );
                }
                
                //Same the current timeout id in case we want to cancel it
                timeouts[ userid ] = setTimeout( function(){
                    //Timeout was not canceled, delete it's id.
                    delete timeouts[ userid ];

                    //If user didn't reconnect the last 10s he should be deleted him from the online list
                    if( online[ userid ].length == 0 ) {
                        delete online[ userid ];
                        
                        console.log( 'User ' + userid + ' disconnected for more than 10s. Making API call' );
                        var body = 'userid=' + userid;
                        var request = php.request( 'POST', '/petros/?resource=presence&method=delete', { 
                            'Host': 'zino.gr', 
                            'Content-Type': 'application/x-www-form-urlencoded',
                            'Content-Length': body.length 
                        });
                        request.end( body );

                        console.log( 'User ' + userid + ' went offline' );
                        return;
                    }
                    //User has made a new connection in the last 10s. He stays on the list.
                    console.log( 'User ' + userid + ' reconnected.' );
                }, 10000);

                //Remove the connection id from the user's active connections array.
                online[ userid ].splice( online[ userid ].indexOf( req.connection_id ), 1 );
            });
        }
        return;
    }
    //Invalid request. Close connection.
    res.end();
});

//Start the server.
server.listen( 8124, "presence.zino.gr" );
