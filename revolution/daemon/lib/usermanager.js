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
		UserManager.Users[ userid ] = { authtoken: authtoken, connections: 1 };
		UserManager.Connections[ connectionid ] = userid;
	},
	DeleteUser: function( userid ) {
		delete UserManager.Users[ userid ];
		for( connection in UserManager.Connections ) {
			if( UserManager.Connections[ connection ] == userid ) {
				delete UserManager.Connections[ connection ];
			}
		}
	},
	AddUserConnection: function( userid, connectionid ) {
		UserManager.Connections[ connectionid ] = userid;
        ++UserManager.Users[ userid ].connections;
	},
	DeleteUserConnection: function( connectionid ) {
        var userid = UserManager.Connections[ connectionid ];
		delete UserManager.Connections[ connectionid ];
        --UserManager.Users[ userid ].connections;
	},
	NewConnectionHandler: function( userid, authtoken ) {
		var id = UserManager.UniqueID++;
		console.log( 'New connection. ' + [ userid, authtoken, id ].join( ' ' ) );
		if ( UserManager.IsOnline( userid ) && UserManager.Users[ userid ].authtoken == authtoken ) {
            console.log( 'User was online before' );
			UserManager.AddUserConnection( userid, id )
			return id;
		}
		
		console.log( 'Unknown authtoken' );
		UserManager.ZinoAPI.Call( 'presence', 'create', { userid: userid, authtoken: authtoken }, function( data ){
			if ( data.search( '<result>SUCCESS</result>' ) != -1 ) {
                console.log( 'Authtoken Valid' );
                if ( UserManager.IsOnline( userid ) && UserManager.Users[ userid ].authtoken != authtoken ) {
                    console.log( 'User changed authtoken, deleting his connections' );
                    UserManager.DeleteUser( userid );
                }
                console.log( 'Register new user ' + [ userid, id, authtoken ].join( ' ' ) );
				UserManager.NewUser( userid, id, authtoken );
			}
            else {
                console.log( 'Got invalid authtoken XML response: ' + data );
            }
		} );
		
		return id;
	},
	DisconnectHandler: function( connectionid ) {
		if( typeof UserManager.Connections[ connectionid ] === 'undefined' ){
			return;
		}
		var userid = UserManager.Connections[ connectionid ];

		UserManager.DeleteUserConnection( connectionid );
		
		if ( typeof UserManager.Timeouts[ userid ] !== 'undefined' ) {
			clearTimeout( UserManager.Timeouts[ userid ] );
		}
		UserManager.Timeouts[ userid ] = setTimeout( function() {
			delete UserManager.Timeouts[ userid ];
			if ( UserManager.Users[ userid ].connections == 0 ) {
				UserManager.DeleteUser( userid );
				UserManager.ZinoAPI.Call( 'presence', 'delete', { userid: userid } );
			}
		}, 10000);
		
	}
}

exports.UserManager = UserManager;
