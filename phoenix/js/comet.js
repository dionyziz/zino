var Comet = {
    SubcriptionCallbacks: {},
    Connected: false,
    ConnectionTimer: 0,
    Connect: function () {
        if ( Comet.Connected ) {
            return;
        }
        if ( Comet.ConnectionTimer !== 0 ) {
            clearTimeout( Comet.ConnectionTimer );
        }
        Comet.ConnectionTimer = setTimeout( function () {
            Comet.Connected = true;
            Meteor.connect();
        }, 100 );
    },
    Subscribe: function ( channel, callback ) {
        Comet.Connect();
        Comet.SubcriptionCallbacks[ channel ] = callback;
        Meteor.joinChannel( channel, 0 );
    },
    Process: function ( json ) {
        var obj = eval( json );
        var channel = obj.shift();
        
        if ( typeof Comet.SubcriptionCallbacks[ channel ] == 'function' ) {
            Comet.SubcriptionCallbacks[ channel ].apply( {
                'Channel': channel
            }, obj );
        }
    },
    Init: function ( userid ) {
        Meteor.hostid = userid;
        Meteor.host = "universe." + location.hostname;
        Meteor.registerEventCallback( "process", Comet.Process );
        Meteor.registerEventCallback( 'pollmode', Comet.ChangeMode );
        Meteor.mode = 'stream';
    },
    ChangeMode: function ( mode ) {
        switch ( mode ) {
            case 'poll': // don't allow polling
                Meteor.disconnect();
                break;
        }
    }
};
