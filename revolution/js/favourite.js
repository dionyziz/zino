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
        }
    }
};
