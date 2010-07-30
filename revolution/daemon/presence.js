var http = require( 'http' );
var UserManager = require( './lib/usermanager' ).UserManager;
var ParseCookies = require( './lib/utilities' ).ParseCookies;

var server = http.createServer();

console.log( 'Zino Presence Server' );
console.log( '(c) Kamibu 2010 by Petros <petros@kamibu.com> and Chorvus <chorvus@kamibu.com>' );
console.log( '' );

server.on( 'request', function ( req, res ) {
    res.writeHead( 200, {
        'Content-Type': 'text/html'
    } );

    //Iframe request
    if ( req.url == '/' ) {
        res.end( '<html><head></head><body><script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script><script>var Connect = function() { setTimeout( function(){ $.get( "http://presence.zino.gr:8124/connect", null, Connect );}, 20 ); }</script></body></html>' );
        return;
    }

    //User list request
    if ( req.url == '/users/list' ) {
        for( user in UserManager.Users ) {
            res.write( user + "\n" );
        }
        res.end();
        return;
    }

    //Persistent Connection
    if ( req.url == '/connect' ) {
        var credentials = typeof req.headers.cookie === 'undefined' ? false : ParseCookies( req.headers.cookie );
        
        if ( !credentials ) {
            res.end();
            return;
        }
        var userid = credentials.userid;
        var authtoken = credentials.authtoken;
		
        console.log( 'Checking authtoken validity. userid=' + userid +  ' authtoken=' + authtoken );
		
		req.connection_id = UserManager.NewConnectionHandler( userid, authtoken );
        
        req.connection.on( 'end', function () {
            UserManager.DisconnectHandler( req.connection_id );
        } );
        req.connection.on( 'close', function () {
            UserManager.DisconnectHandler( req.connection_id );
        } );
        return;
    }
    //Invalid request. Close connection.
    res.end();
} );

console.log( 'Listening on presence.zino.gr:8124' );
//Start the server.
server.listen( 8124, "presence.zino.gr" );
