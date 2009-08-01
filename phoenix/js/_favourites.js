var Favourites = {
    Delete: function( favid ) {
        Coala.Warm( 'favourites/delete', { 'favid': favid } );
        
        return false;
    }
}