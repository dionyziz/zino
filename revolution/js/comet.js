var Comet = {
    Channels: {},
    ChannelsLength: 0,
    HandshakeCompleted: false,
    BodyLoaded: false,
    ConnectPostponed: false,
    Renewing: false,
    Handshake: function () {
        //alert( 'Comet.Handshake' );
        channels = [];
        for ( var i = 0; i < Comet.Channels.length; ++i ){
            channels.push( i );
        }
        $.post( 'tunnel/create', {
            channels: channels.join( "," )
        }, Comet.OnHandshakeCompleted, 'xml' );
    },
    OnHandshakeCompleted: function ( res ) {
        //alert( 'Comet.OnHandshakeCompleted' );
        Comet.HandshakeCompleted = true;
        Comet.TunnelAuthtoken = $( res ).find( 'tunnel authtoken' ).text();
        Comet.TunnelId = $( res ).find( 'tunnel' ).attr( 'id' );
        if ( Comet.BodyLoaded ) {
            Comet.Connect();
        }
        else {
            Comet.ConnectPostponed = true;
        }
    },
    OnBodyLoaded: function () {
        Comet.BodyLoaded = true;
        if ( Comet.ConnectPostponed ) {
            setTimeout( Comet.Connect, 50 );
        }
    },
    Init: function () {
        setTimeout( Comet.Handshake, 50 );
    },
    Connect: function () {
        $.get( '/subscribe?id=' + Comet.TunnelId, {}, Comet.OnFishArrival, 'text' );
        if ( !Comet.Renewing ) {
            setInterval( Comet.Renew, 60000 );
            Comet.Renewing = true;
        }
    },
    OnFishArrival: function ( res ) {
        var xmlDoc;

        var a = res.split( "\n" );
        a.splice( 0, 3 );
        a.splice( a.length - 2, 2 );
        res = a.join( "\n" );

        if ( window.DOMParser ) {
            var parser = new DOMParser();
            xmlDoc = parser.parseFromString( res, "text/xml" );
        }
        else {
            xmlDoc = new ActiveXObject( "Microsoft.XMLDOM" );
            xmlDoc.async = 'false';
            xmlDoc.loadXML( res );
        }
        Comet.Connect(); // reconnect

        var channelid = $( xmlDoc ).find( 'channel' ).attr( 'id' );
        if ( typeof Comet.Channels[ channelid ] != 'undefined' ) {
            Comet.Channels[ channelid ]( $( xmlDoc ).find( 'channel' )[ 0 ] ); // fire callback
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
    }
};
