var ZinoAPI = {
	RequestHandler: require( 'http' ).createClient( 500, 'zino.gr' ),
	QueryHelper: require( 'querystring' ),
	Methods: {
		view: 'GET', 
		list: 'GET',
		create: 'POST',
		update: 'POST',
		'delete': 'POST' 
	},
	Call: function( resource, method, parameters, callback ) {
		if ( typeof ZinoAPI.Methods[ method ] === 'undefined' ) {
			return; //Invalid Method
		}
		
		if ( ZinoAPI.Methods[ method ] == 'GET' ) {
			parameters.resource = resource;
			parameters.method = method;
			var GETParams = ZinoAPI.QueryHelper.stringify( parameters, '&', '=', false );
			var POSTParams = '';
        }
		else {
			var GETParams = ZinoAPI.QueryHelper.stringify( { resource: resource, method: method }, '&', '=', false );
			var POSTParams = ZinoAPI.QueryHelper.stringify( parameters, '&', '=', false );
		}
		
		var request = ZinoAPI.RequestHandler.request( ZinoAPI.Methods[ method ], '/petros/?' + GETParams, { 
			'Host': 'zino.gr', 
			'Content-Type': 'application/x-www-form-urlencoded',
			'Content-Length': POSTParams.length 
		} );
		request.end( POSTParams );
		
		if ( typeof callback == 'function' ) {
			request.on( 'response', function( response ) {
				var data = '';
				response.on( 'data', function( chunk ) {
					data += chunk.toString();
				} );

				response.on( 'end', function() {
					callback( data );
				} );
			} );
		}
	}
}

exports.ZinoAPI = ZinoAPI;
