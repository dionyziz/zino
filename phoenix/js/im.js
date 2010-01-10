var IM = {
    OnMessageArrival: function ( shoutid, text, who, channel ) {
        var li = document.createElement( 'li' );
        var strong = document.createElement( 'strong' );
        var div = document.createElement( 'div' );
        div.className = 'text';
        strong.appendChild( document.createTextNode( who.name ) );
        div.appendChild( document.createTextNode( text ) );
        li.appendChild( strong );
        li.appendChild( document.createTextNode( ' ' ) );
        li.appendChild( div );
        li.id = 's_' + shoutid;
        $( '#im_' + channel + ' ul' )[ 0 ].appendChild( li );
        li.scrollIntoView();
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

