var Comet = {
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
    Subscribe: function ( channel ) {
        Comet.Connect();
        Meteor.joinChannel( channel, 0 );
    },
    Process: function ( json ) {
        var obj = eval( json );
        var channel = obj.shift();
        
        alert( channel );
        alert( obj[ 0 ] );
        
        // eval( obj[ 0 ] );
    },
    Init: function ( uniq ) {
        Meteor.hostid = uniq;
        Meteor.host = "universe." + location.hostname;
        Meteor.registerEventCallback( "process", Comet.Process );
        Meteor.registerEventCallback( 'pollmode', Comet.ChangeMode );
        Meteor.mode = 'stream';
    },
    ChangeMode: function ( mode ) {
        if ( mode == 'poll' ) { // don't allow polling
            Meteor.disconnect();
        }
    }
};
