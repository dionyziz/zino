var Comet = {
    Connected: false,
    ConnectionTimer: 0,
    Initialized: false,
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
        if ( ExcaliburSettings.Production ) {
            channel = 'P' + channel;
        }
        else {
            channel = 'S' + channel;
        }
        Meteor.joinChannel( channel, 0 );
    },
    Process: function ( json ) {
        var obj = eval( json );
        var channel = obj.shift(); // unused
        var code = obj.shift();
        
        eval( code );
    },
    Init: function ( uniq, domain ) {
        if ( Comet.Initialized ) {
            return;
        }

        Comet.Initialized = true;
        domain = domain || 'universe.' + location.hostname;
        Meteor.hostid = uniq;
        Meteor.host = domain;
        Meteor.registerEventCallback( "process", Comet.Process );
        Meteor.registerEventCallback( 'pollmode', Comet.ChangeMode );
        Meteor.mode = 'longpoll';
    },
    Load: function () {
        var iframe = document.createElements( 'iframe' );
        iframe.src = 'comet.html';
        document.body.appendChild( iframe );
    }
    ChangeMode: function ( mode ) {
        if ( mode == 'poll' ) { // don't allow polling
            Meteor.disconnect();
        }
    }
};
