var PhotoListing = {
    Initialized: false,
    PhotoList: null,
    PhotoPrototype: null,
    CurrentPage: 1,
    LastLoaded: null,
    Loading: false,
    EndOfPhotos: false,
    Init: function(){
        SI.Files.stylizeAll();
        this.PlaceholderHTML = '';
        for( var i = 0; i < 100; ++i ){
            this.PlaceholderHTML += '<li><a><img /></a></li>';
        }
        PhotoListing.PreparePhotoList();
        $( 'form input' ).change( function () {
            $( this ).parents( 'form' )[ 0 ].submit();
            $( 'body' ).append(
                '<div class="wait">'
                    + '<img src="http://static.zino.gr/phoenix/ajax-loader.gif" />'
                + '</div>'
            );
        } );
		if ( $( '.useralbums' ) ) {
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
                var albums = $.get( '?resource=album&method=listing', { username: $( '.useralbums .user' ).text() } );
                axslt( albums, '/', function() {
                    $( '.useralbums' ).append( $( this ).find( 'ol' ) );
                    $( '.useralbums a' ).click( function() {
                        axslt( $.get( this.href ), '/social/album', function() {
                            $( '.photostream' ).empty().append( $( this ).filter( '*' ) );
                            PhotoListing.PreparePhotoList();
                        } );
                        return false;
                    } );
                } );
			} );
		}
    },
    PreparePhotoList: function() {
        PhotoListing.PhotoList = $( '.photostream ul' );
        PhotoListing.LastLoaded = $( '.photostream ul li:last' )[ 0 ];
        PhotoListing.AssignEvents();
        if ( $( '.photostream ul li' ).length < 100 ) {
            for ( i = 0; i < 20; ++i ) { //Last Line justify hack
                PhotoListing.PhotoList[ 0 ].innerHTML += ' <li class="justifyhack"><a><img /></a></li> ';
            }
        }
        PhotoListing.Initialized = true;
    },
    ScrollHandler: function(){
        if( PhotoListing.PhotoList.height() - $( window ).scrollTop() - $( window ).height() < 500 ){
            PhotoListing.FetchNewPhotos();
        }
    },
    AssignEvents: function(){
        $( window ).bind( 'scroll', PhotoListing.ScrollHandler );
    },
    RemoveEvents: function(){
        $( window ).unbind( 'scroll', PhotoListing.ScrollHandler );
    },
    FetchNewPhotos: function(){
        if( PhotoListing.Loading ){
            return;
        }
        PhotoListing.Loading = true;
        PhotoListing.RemoveEvents();
        PhotoListing.PhotoList[ 0 ].innerHTML += PhotoListing.PlaceholderHTML;
        PhotoListing.LastLoaded = $( '.photostream ul li')[ PhotoListing.CurrentPage * 100 - 1];
        PhotoListing.CurrentPage++;
        $.get( window.location,
        { 'page': PhotoListing.CurrentPage },
        function( xml ){
            var responseSize = $( xml ).find( 'photo' ).length;
            var i;

            $( xml ).find( 'photo' ).each( function( index ){
                var id = $( this ).attr( 'id' );
                var url = $( this ).find( 'media' ).attr( 'url' );
                var count = $( this ).find( 'discussion' ).attr( 'count' );
                do {
                    PhotoListing.LastLoaded = PhotoListing.LastLoaded.nextSibling;
                } while ( PhotoListing.LastLoaded.nodeType != 1);

                if ( url ) {
                    $( 'img', $( PhotoListing.LastLoaded ) ).attr( 'src', url );
                }
                else {
                    alert( id );
                }
                $( 'a', $( PhotoListing.LastLoaded ) ).attr( 'href', 'photos/' + id );
                if ( count != '0' ) {
                    if ( count < 100 ) {
                        $( 'a', $( PhotoListing.LastLoaded ) ).append( $( '<span class="countbubble">' + count + '</span>' ) );
                    }
                    else {
                        $( 'a', $( PhotoListing.LastLoaded ) ).append( $( '<span class="countbubble">∞</span>' ) );
                    }
                }
            } );
            if ( responseSize < 100 ){
                PhotoListing.EndOfPhotos = true;
                var lastChild = $( '.photostream ul li:last' )[ 0 ];
                for( i = 0; i < 100 - responseSize; ++i ) {
                    var nextLastChild = lastChild.previousSibling;
                    $( lastChild ).remove();
                    lastChild = nextLastChild;
                }
                for( i = 0; i < 20; ++i ){ //Last Line justify hack
                    PhotoListing.PhotoList[ 0 ].innerHTML += ' <li class="justifyhack"><a><img /></a></li> ';
                }
                return; //Prevent Events From reassining
            }
            PhotoListing.Loading = false;
            PhotoListing.AssignEvents();
            PhotoListing.ScrollHandler();
        } );
    }
};
