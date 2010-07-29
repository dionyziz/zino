function ParseCookies( cookies ) {
    var querystring = require( 'querystring' );
	try {
		cookies = querystring.parse( cookies, '; ' )
		if ( typeof cookies[ 'zino_login_8' ] === 'undefined' ) {
			throw 'Cookie not found';
		}
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
		console.log( err );
		return false;
	}
	return { userid: cookies[ 0 ], authtoken: cookies[ 1 ] };
}

exports.ParseCookies = ParseCookies;
