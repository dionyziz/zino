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

                    var albumid = this.href.split( '/' );
                    albumid = albumid[ albumid.length - 1 ];
                    window.location.hash = this.href;
                    axslt( $.get( this.href ), '/social/album', function() {
                        $( '.photostream' ).empty().append( $( this ).find( 'ul' ) );
                        if( $( '.photostream input' ).length == 2 ){
                            $( '.photostream input' )[ 1 ].value = albumid;
                        }
                        PhotoListing.PreparePhotoList();
                    } );
                    return false;
                } );
            } );
        } );
    }
}
