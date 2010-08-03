var Favourite = {
    Init: function () {
        var $items = $( '.itemstream ul li' );
        var $item, url;

        for ( var i = 0; i < $items.length; ++i ) {
            $item = $( $items[ i ] );
            url = $item.find( 'a' ).attr( 'href' );
            $item.click( ( function ( url ) {
                return function() {
                    location.href = url;
                };
            } )( url ) );
            id = url.substring( url.indexOf( '/' ) + 1 );
            type = url.substring( 0, url.indexOf( '/' ) - 1 );
            $del = $item.find( '.deleteicon' );
            $del.click( function( id, type ) { return function() {
                Favourite.Remove( id, type );
                return false;
            }; }( id, type ) );
        }
    },
    Remove: function( id, type ) {
        $.post( 'index.php?resource=favourite&method=delete', { 'itemid': id, 'type': type } );
        $( '#' + type + '_' + id ).fadeOut();
    }
};
