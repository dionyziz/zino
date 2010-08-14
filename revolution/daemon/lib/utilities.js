function ParseCookies( cookies ) {
    console.log( "Requiring querystring" );
    var querystring = require( 'querystring' );
	try {
        console.log( 'Parsing Cookies ' + cookies );
		cookies = querystring.parse( cookies, '; ' )
		if ( typeof cookies[ 'zino_login_8' ] === 'undefined' ) {
			throw 'Cookie not found';
		}
        console.log( 'Spliting Cookies' );
		cookies  = cookies[ 'zino_login_8' ].split( ':' );
		
		if ( cookies.length != 2 ) {
			throw 'Cookie not in appropriate format';
		}
		if ( cookies[0] - 0 < 1 ) {
			throw 'Wrong userid format';
		}
		if ( !cookies[1].match( /[a-zA-Z0-9]{32}/ ) ) {
			throw 'Wrong authtoken format';
		}
	}
	catch( err ) {
        console.log( 'Error in cookie parsing' );
		console.log( err );
		return false;
	}
	return { userid: cookies[ 0 ], authtoken: cookies[ 1 ] };
}

exports.ParseCookies = ParseCookies;
