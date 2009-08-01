var Favourites = {
    Delete: function( favid ) {
        Coala.Warm( 'favourites/delete', { 'favid': favid } );
        fav = $( "ul.events > li#favourite_" + favid )
        fav.fadeTo( 300, 0 ).slideUp( 500, function() { fav.remove(); } );
        return false;
    }
}