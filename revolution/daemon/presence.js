var fs = require( 'fs' );
var http = require( 'http' );
var UserManager = require( './lib/usermanager' ).UserManager;
var ParseCookies = require( './lib/utilities' ).ParseCookies;

var server = http.createServer();

var HTML = {
    iframe: ''
};

console.log( 'Zino Presence Server' );
console.log( '(c) Kamibu 2010 by Petros <petros@kamibu.com> and Chorvus <chorvus@kamibu.com>' );
console.log( '' );

fs.open( '/var/run/presence.pid', 'w+', 0666, function( err, fd ) {
    if ( err != null ) {
        console.log( 'Failed to write PID file' );
        console.log( err );
    }
    fs.write( fd, process.pid );
} );

fs.readFile( './html/iframe.html', function ( err, data ) {
    if ( err ) {
        throw err;
    }
    HTML.iframe = data;
} );

server.on( 'request', function ( req, res ) {
    res.writeHead( 200, {
        'Content-Type': 'text/html'
    } );

    //Iframe request
    if ( req.url == '/' ) {
        console.log( 'Seding iframe' );
        res.end( HTML.iframe );
        return;
    }

    //User list request
    if ( req.url == '/users/list' ) {
        console.log( 'Listing Users' );
        for ( user in UserManager.Users ) {
            res.write( user + "\n" );
        }
        res.end();
        return;
    }

    //Persistent Connection
    if ( req.url == '/connect' ) {
        console.log( 'Parsing Cookies' );
        var credentials = typeof req.headers.cookie === 'undefined' ? false : ParseCookies( req.headers.cookie );
        
        if ( !credentials ) {
            console.log( 'No cookies, ending' );
            res.end();
            return;
        }
        var userid = credentials.userid;
        var authtoken = credentials.authtoken;
		
        console.log( 'Checking authtoken validity. userid=' + userid +  ' authtoken=' + authtoken );
		
		req.connection_id = UserManager.NewConnectionHandler( userid, authtoken );
        console.log( 'Connection ID = ' + req.connection_id );
        
        req.connection.on( 'end', function () {
            console.log( 'End Event, calling Disconnect Handler. Connectionid = ' + req.connection_id );
            UserManager.DisconnectHandler( req.connection_id );
        } );
        req.connection.on( 'close', function () {
            console.log( 'Close Event, calling Disconnect Handler. Connectionid = ' + req.connection_id );
            UserManager.DisconnectHandler( req.connection_id );
        } );
        return;
    }
    //Invalid request. Close connection.
    console.log( 'Invalid request. ' + req.url );
    res.end();
} );

console.log( 'Listening on presence.zino.gr:8124' );
//Start the server.
server.listen( 8124, "presence.zino.gr" );
//aconsole.log( process.pid );
