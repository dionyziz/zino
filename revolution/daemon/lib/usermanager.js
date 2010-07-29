var UserManager = {
	UniqueID: 0,
	Users: {},
	Connections: {},
	Timeouts: {},
	ZinoAPI: require( './zinoapi' ).ZinoAPI,
	IsOnline: function( userid ){
		return typeof UserManager.Users[ userid ] !== 'undefined' ? true : false;
	},
	NewUser: function( userid, connectionid, authtoken ){
		UserManager.Users[ userid ] = { authtoken: authtoken, Connections: [ connectionid ] };
		Connections[ connectionid ] = userid;
	},
	DeleteUser: function( userid ) {
		delete UserManger.Users[ userid ];
		for( connection in UserManager.Connections ) {
			if( UserManager.Connections[ connection ] == userid ) {
				UserManager.Connections[ connection ];
			}
		}
	},
	AddUserConnection: function( userid, connectionid ) {
		Users[ userid ].Connections.push( connectionid );
		UserManager.Connections[ id ] = userid;
	},
	DeleteUserConnection: function( connectionid ) {
		UserManager.Users[ userid ].Connections.splice( UserManager.Users[ userid ].Connections.indexOf( connectionid ), 1 );
		delete UserManager.Connections[ connectionid ];
	}
	NewConnectionHandler: function( userid, authtoken ) {
		var id = UserManager.UniqueID++;
		
		if ( UserManager.IsOnline( userid ) && UserManager.Users[ userid ].authtoken == authtoken ) {
			UserManager.AddUserConnection( userid, id )
			return id;
		}
		
		if ( UserManager.IsOnline( userid ) && UserManager.Users[ userid ].authtoken != authtoken ) {
			UserManager.DeleteUser( userid );
		}
		
		UserManager.ZinoAPI.Call( 'presence', 'create', { userid: userid, authtoken: authtoken }, function( data ){
			if ( typeof UserManager.Connections[ id ] === 'undefined' ) {
				return;
			}
			if ( data.search( '<result>SUCCESS</result>' ) != -1 ) {
				UserManager.NewUser( userid, id, authtoken );
			}
		} );
		
		return id;
	},
	DisconnectHandler: function( connectionid ) {
		if( typeof UserManager.Connections[ connectionid ] === 'undefined' ){
			return;
		}
		
		var userid = UserManager.Connections[ connectionid ];
		UserManager.DeleteUserConnection[ connectionid ];
		
		if ( typeof UserManager.Timeouts[ userid ] !== 'undefined' ) {
			clearTimeout( UserManager.Timeouts[ userid ] );
		}
		
		UserManager.Timeouts[ userid ] = setTimeout( function() {
			delete UserManager.Timeouts[ userid ];

			if ( UserManager.Users[ userid ].Connections.length == 0 ) {
				UserManager.DeleteUser( userid );
				UserManager.ZinoAPI.Call( 'presence', 'delete', { userid: userid } );
			}
		}, 10000);
		
	}
}

exports.UserManager = UserManager;