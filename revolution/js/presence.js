var Presence = {
    ServerURL: "http://presence.zino.gr",
    Connect: function() {
        setTimeout( function(){
            var im = new Image();
            im.src = Presence.ServerURL;
        }, 1 );
    }
};
