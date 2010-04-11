var Comet = {
    Channels: {},
    ChannelsLength: 0,
    HandshakeCompleted: false,
    Handshake: function () {
        channels = [];
        for ( channelid in Comet.Channels ) {
            channels.push( channelid );
        }
        $.post( 'tunnel/create', {
            channels: channels.join( "," )
        }, Comet.OnHandshakeCompleted, 'xml' );
    },
    OnHandshakeCompleted: function ( res ) {
        Comet.HandshakeCompleted = true;
        Comet.TunnelAuthtoken = $( res ).find( 'tunnel authtoken' ).text();
        Comet.TunnelId = $( res ).find( 'tunnel' ).attr( 'id' );
        Comet.Connect();
    },
    Init: function () {
        setTimeout( Comet.Handshake, 50 );
    },
    Connect: function () {
        $.get( '/subscribe', {
            id: Comet.TunnelId
        }, Comet.OnFishArrival, 'xml' );
    },
    OnFishArrival: function ( res ) {
        Comet.Connect(); // reconnect
        // TODO: verify Comet.Channels item exists
        var channelid = $( res ).find( 'channel' ).attr( 'id' );
        if ( typeof Comet.Channels[ channelid ] != 'undefined' ) {
            Comet.Channels[ channelid ]( res ); // fire callback
        }
    },
    Renew: function () {
        $.post( 'tunnel/update', {
            tunnelid: Comet.TunnelId,
            tunnelauthtoken: Comet.TunnelAuthtoken
        } );
    },
    Unsubscribe: function ( channelid ) {
        // TODO: remove channelid from Comet.Channels; call tunnel/update
    },
    Subscribe: function ( channelid, callback ) {
        Comet.Channels[ channelid ] = callback;
        // TODO: Call tunnel/update if we've already handshaked
    },
};
