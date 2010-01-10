var IM = {
    Cnt: 0,
    OnMessageArrival: function ( shoutid, text, who, channel ) {
        if ( who.name == User ) {
            return;
        }

        if ( $( '#im_' + channel ).length == 0 ) {
            var content = '<div class="imwindow" style="display:none">'
                + '<h3><a href="" class="close">&times;</a>'
                + who.name
                + '</h3><ul></ul>'
                + '<div class="typehere"><textarea></textarea></div>'
            + '</div>';

        }

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
    },
    CreateWindow: function ( id, x, y, w, h, content ) {
        var chatWindow = Puffin.create();

        chatWindow.move( x, y );
        chatWindow.resize( w, h );
        var content = chatWindow.setContent( content );
        content.id = 'im_' + id;
        Puffin.clickable( $( content ).find( 'textarea' )[ 0 ] );
        Puffin.clickable( $( content ).find( 'a' )[ 0 ] );
        Puffin.clickable( $( content ).find( 'ul' )[ 0 ] );
        $( content ).find( 'a' )[ 0 ].onclick = ( function ( me ) {
            return function () {
                me.hide();
                Coala.Warm( 'chat/window/update', {
                    channelid: id,
                    deactivate: true
                } );
                return false;
            };
        } )( chatWindow );
        chatWindow.onmove = function ( x, y ) {
            Coala.Warm( 'chat/window/update', {
                channelid: id,
                x: x, y: y
            } );
        };
        chatWindow.onresize = function ( w, h ) {
            Coala.Warm( 'chat/window/update', {
                channelid: id,
                w: w, h: h
            } );
        };
        $( content ).find( 'textarea' ).keyup( function( e ) {
            var code;

            if ( !e ) {
                var e = window.event;
            }
            if ( e.keyCode ) {
                code = e.keyCode; 
            }
            else if ( e.which ) {
                code = e.which;
            }
            else {
                return;
            }
            if ( code == 13 ) { // enter
                var li = document.createElement( 'li' );
                var text = document.createElement( 'div' );
                var strong = document.createElement( 'strong' );
                strong.appendChild( document.createTextNode( User );
                text.className = 'text';
                text.appendChild( document.createTextNode( this.value ) );
                li.appendChild( strong );
                li.appendChild( document.createTextNode( ' ' ) );
                li.appendChild( text );
                $( this.parentNode.parentNode ).find( 'ul' )[ 0 ].appendChild( li );
                Coala.Warm( 'shoutbox/new', {
                    text: this.value,
                    channel: id,
                    node: li
                } );
                this.value = '';
            }
        } );
        chatWindow.minSize( 139, 25 );
        chatWindow.show();
        ++IM.Cnt;
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

