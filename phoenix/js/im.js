var IM = {
    OnMessageArrival: function ( shoutid, text, who, channel ) {
        window.title = 'Message received from ' + who.name + ': ' + text;
    }
};

if ( typeof Frontpage == 'undefined' ) {
    var Frontpage = {
        Shoutbox: {
            OnMessageArrival: IM.OnMessageArrival
        }
    };
}
else {
    Frontpage.Shoutbox.OnMessageArrival = ( function ( old ) {
        return function ( shoutid, text, who, channel ) {
            old( shoutid, text, who, channel );
            IM.OnMessageArrival( shoutid, text, who, channel );
        };
    } )( Frontpage.Shoutbox.OnMessageArrival );
}

