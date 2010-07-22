var http = require('http');
var server = http.createServer();

var ai_connection = 0;

var online = {};
var ronline = {};

server.on( 'request', function ( req, res ) {
    res.writeHead( 200, {
        'Content-Type': 'text/plain',
        'Set-Cookie': 'zino_login_8=5181:4891e98dy3289e30u9',
    } );
    if( typeof req.headers.cookie !== 'undefined' ){
        var cookies = req.headers.cookie.split( ';' );
        for( var i = 0; i < cookies.length; ++i ){
            cookies[ i ] = cookies[ i ].split( '=' );
            if( cookies[ i ][ 0 ] == 'zino_login_8' ){
                var credentials = cookies[ i ][ 1 ].split( '%3A' );
                break;
            }
        }
        if( typeof credentials === 'undefined' ){
            return;
        }
        var userid = credentials[ 0 ];
        req.connection_id = ai_connection++;

        console.log( 'User ' + credentials[ 0 ] + ' connected (Connection id = ' + req.connection_id + ')' );
        
        if( typeof( online[ userid ] ) === 'undefined' ){
            online[ userid ] = new Array();
        }

        online[ userid ].push( req.connection_id );

        req.connection.on( 'end', function(){ 
            setTimeout( function(){
                if( typeof( online[ userid ] ) !== 'undefined' && online[ userid ].length == 0 ) {
                    delete online[ userid ];
                    console.log( 'User ' + userid + ' went offline' );
                }
            }, 10000);
            online[ userid ].splice( online[ userid ].indexOf( req.connection_id ), 1 );
        });
    }
});

server.listen( 8124, "europa.kamibu.com" );
