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
        return function ( id, name, avatar, subdomain ) {
            old();
            IM.OnMessageArrival();
        };
    } )( Frontpage.Shoutbox.OnMessageArrival );
}

