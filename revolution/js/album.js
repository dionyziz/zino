var AlbumListing = {
    Initialized: false,
    Init: function() {
        $( '.useralbums' ).click( function() {
            if ( $( this ).hasClass( 'expanded' ) ) {
                $( this ).removeClass( 'expanded' ).animate( {
                    height: '22px'
                } );
                return;
            }
            $( this ).addClass( 'expanded' );
            $( this ).animate( {
                height: '210px'
            } );
            if( AlbumListing.Initialized ){
                return;
            }
            AlbumListing.Initialized = true;
            var albums = $.get( '?resource=album&method=listing', { username: $( '.useralbums .user' ).text() } );
            axslt( albums, '/', function() {
                $( '.useralbums' ).append( $( this ).find( 'ol' ) );
                $( '.useralbums a' ).click( function() {
                    $( '.useralbums .selected' ).removeClass( 'selected' );
                    $( this ).addClass( 'selected' );
                    axslt( $.get( this.href ), '/social/album', function() {
                        $( '.photostream' ).empty().append( $( this ).filter( '*' ) );
                        PhotoListing.PreparePhotoList();
                    } );
                    return false;
                } );
            } );
        } );
    }
}
