var AlbumListing = {
    Initialized: false,
    CurrentAlbum: null,
    Init: function() {
        $( '.useralbums span.minimize' ).click( function() {
            var albumlist = $( '.useralbums' );
            if ( albumlist.hasClass( 'expanded' ) ) {
                albumlist.removeClass( 'expanded' ).animate( {
                    height: '22px'
                } );
                $( this ).text( '▼' );
                return;
            }
            $( this ).text( '▲' );
            albumlist.addClass( 'expanded' );
            albumlist.animate( {
                height: '210px'
            } );
            if( AlbumListing.Initialized ){
                return;
            }
            AlbumListing.Initialized = true;
            var albums = $.get( '?resource=album&method=listing', { username: $( '.useralbums .user' ).text() } );
            axslt( albums, '/social', function() {
                $( '.useralbums' ).append( $( this ).filter( 'ol' ) );
                if( XMLData.author == User ){
                    $( '.useralbums li[class!=egoalbum] p' ).each( function() {
                        var albumid = $( this ).siblings( 'a' ).attr( 'href' ).split( '/' )[ 1 ];
                        Kamibu.EditableTextElement( this, 'Όρισε όνομα', function( title ) {
                            $.post( '?resource=album&method=update', { albumid: albumid, name: title } );
                        } );
                    } );
                    $( '.useralbums li[class!=egoalbum] a' ).each( function() {
                    $( this ).append( '<span class="deletebutton">×</span>' );
                        $( '.deletebutton', this ).click( function( e ) {
                            e.stopImmediatePropagation();
                            if ( confirm( 'Διαγραφή αυτού του άλμπουμ;' ) ) {
                                var albumid = this.parentNode.href.split( '/' ).pop();
                                $.post( '?resource=album&method=delete', { albumid: albumid } );
                                $( this ).parents( 'li' ).fadeOut( 400, function() {
                                    $( this ).remove();
                                } );
                                if ( AlbumListing.CurrentAlbum == null || albumid == AlbumListing.CurrentAlbum ) {
                                    axslt( $.get( 'photos/' + User ), '/social/photos', function() {
                                        $( '.photostream' ).empty().append( this );
                                        $( '.photostreams .useralbums:last' ).remove();
                                    } );
                                }
                            }
                            return false;
                        } );
                    } );
                }
                $( '.useralbums a' ).click( function() {
                    $( '.useralbums .selected' ).removeClass( 'selected' );
                    $( this ).addClass( 'selected' );

                    var albumid = this.href.split( '/' );
                    albumid = albumid[ albumid.length - 1 ];
                    AlbumListing.CurrentAlbum = albumid;
                    window.location.hash = this.href;
                    axslt( $.get( this.href ), '/social/album', function() {
                        $( '.photostream' ).empty().append( $( this ).find( 'ul' ) );
                        if( $( '.photostream input' ).length == 2 ){
                            $( '.photostream input' )[ 1 ].value = albumid;
                        }
                        PhotoListing.PreparePhotoList();
                        $( '.photostream a' ).each( function ( i ) {
                            var $span = $( '<span class="mainbutton" title="Ορισμός ως προεπιλεγμένης εικόνας του album">↑</span>' );
                            var spanClicked = false;

                            $span.click( function ( e ) {
                                spanClicked = true;
                                $( '#albumlist .selected img' )[ 0 ].src = $( this ).siblings( 'img' )[ 0 ].src;
                                $.post( 'album/update', {
                                    albumid: albumid,
                                    mainimageid: $( this ).parent()[ 0 ].href.split( '/' ).pop(),
                                } );
                            } );
                            $( this ).append( $span );
                            $( this ).click( function () {
                                if ( spanClicked ) {
                                    spanClicked = false;
                                    return false;
                                }
                            } );
                        } );
                    } );
                    return false;
                } );
            } );
        } );
    }
};
