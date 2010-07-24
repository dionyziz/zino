var Presence = {
    ServerURL: "http://presence.zino.gr:8124/",
    Init: function() {
        setTimeout( function() {
            var frame = document.createElement( 'iframe' );
            frame.src = Presence.ServerURL;
            document.body.appendChild( frame );
        }, 1 );
    }
};
