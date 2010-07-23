var Presence = {
    ServerURL: "/presence",
    Connect: function() {
        $.post( Presence.ServerURL, {}, function() {
            // this should not be called
            // reconnect!
            setTimeout( Presence.Connect, 50 );
        } );
    }
};
