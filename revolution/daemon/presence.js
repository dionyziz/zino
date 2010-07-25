var http = require( 'http' );
var querystring = require( 'querystring' );

var server = http.createServer();
var php = http.createClient( 500, 'zino.gr' );

var ai_connection = 0;
var online = {};
var timeouts = {};

console.log( 'Zino Presence Server' );
console.log( '(c) Kamibu 2010 by Petros <petros@kamibu.com> and Chorvus <chorvus@kamibu.com>' );
console.log( '' );

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
            try {
                cookies = querystring.parse( req.headers.cookie, '; ' )
                if( typeof cookies[ 'zino_login_8' ] === 'undefined' ){
                    throw 'Cookie not found';
                }
                cookies  = cookies[ 'zino_login_8' ].split( ':' );
                
                if( cookies.length != 2 ) {
                    throw 'Cookie not in appropriate format';
                }
                if( cookies[0] - 0 < 1 ) {
                    throw 'Wrong userid format';
                }
                if( !cookies[1].match( /[a-zA-Z0-9]{32}/ ) ){
                    throw 'Wrong authtoken format';
                }
            }
            catch( err ) {
                console.log( err );
                res.end();
                return;
            }
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
            request.end( body );
            request.on( 'response', function( response ){
                var data = '';
                response.on( 'data', function( chunk ){
                    data += chunk.toString();
                });

                response.on( 'end', function( ){
                    if( req.connection.readyState == 'closed' ){
                        return; //Connection has already ended. Don't bother.
                    }
                    var result = data.search( '<result>SUCCESS</result>' );
                    if( result != -1 ){

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
                });
            });

            req.connection.on( 'end', function(){
                if( typeof online[ userid ] !== 'undefined' ){
                    return; //Connection ended before we validate
                }
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

console.log( 'Listening on presence.zino.gr:8124' );
//Start the server.
server.listen( 8124, "presence.zino.gr" );
