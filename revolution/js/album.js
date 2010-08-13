var AlbumListing = {
    Initialized: false,
    CurrentAlbum: null,
    Minimize: function(){
        $( '.useralbums' ).removeClass( 'expanded' )
            .children( 'ol' ).slideUp().end()
            .children( 'span.minimize' ).text( '▼' );
    },
    Maximize: function(){
        $( '.useralbums' ).addClass( 'expanded' )
            .children( 'ol' ).slideDown().end()
            .children( 'span.minimize' ).text( '▲' );
    },
    LoadAlbums: function( callback ){
        var albums = $.get( '?resource=album&method=listing', { username: $( '.useralbums .user' ).text() } );
        AlbumListing.Initialized = true;
        axslt( albums, '/social', function() {
            $( '.useralbums' ).append( $( this ).filter( 'ol' ) ).children( 'ol' ).hide();
            if ( XMLData.author == User ) {
                $( '.useralbums li:not(.egoalbum,.add)' ).each( function(){
                    AlbumListing.AppendOwnActions( $( this ) );
                });
            }
            callback();
        } );
    },
    LoadAlbum: function( albumid ){
        if( albumid == 0 ){
            axslt( $.get( 'photos/' + User ), '/social/photos', function() {
                $( '.photostream' ).empty().append( $( this ).filter( '.photostream' ).children( 'ul' ) );
            } );
            return;
        }
        $( '.useralbums .selected' ).removeClass( 'selected' );
        $( '#album_' + albumid + ' a' ).addClass( 'selected' );

        AlbumListing.CurrentAlbum = albumid;
        $( '.photostream' ).empty();
        axslt( $.get( 'albums/' + albumid ), '/social/album', function() {
            $( '.photostream' ).append( $( this ).find( 'ul' ) );
            if( $( '.photostream input' ).length == 2 ){
                $( '.photostream input' )[ 1 ].value = albumid;
            }
            
            PhotoListing.PreparePhotoList();
            //the following should be moved to PhotoListing
            if ( XMLData.author == User ) {
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
            }
        } );
    },
    AppendOwnActions: function( li ){
        var albumid = $( li ).attr( 'id' ).split( '_' )[ 1 ];
        //Input editable
        Kamibu.EditableTextElement( $( li ).find( 'p' )[0], 'Όρισε όνομα', function( title ){
            $.post( '?resource=album&method=update', { 
                albumid: albumid,
                name: title
            });
        });
        //album delete
        $( li ).children( 'a' ).append( '<span class="deletebutton">×</span>' );
    },
    Remove: function( albumid ){
        $.post( '?resource=album&method=delete', { 
            albumid: albumid 
        } );
        $( '#album_' + albumid ).fadeOut( 400, function() {
            $( this ).remove();
        } );
        if ( AlbumListing.CurrentAlbum == null || albumid == AlbumListing.CurrentAlbum ) {
            AlbumListing.LoadAlbum( 0 );
        }
    },
    AddClicked: function(){
        var li = $( '<li class="new" style="display: inline-block;">'
                        +'<a><img src="http://static.zino.gr/phoenix/anonymous150.jpg"></a>'
                        +'<p>Όρισε όνομα<input value="" class="editableinput" /></p>'
                    +'</li>' );
        $( li ).hide().insertBefore( '#albumlist .add' ).fadeIn();
        $( li ).find( 'input' ).show().focus().blur( function(){
            if( $( this ).val() == '' ){
                AlbumListing.AddCancel();
                return;
            }
            AlbumListing.Add();
        }).keyup( function( e ){
            if( e.which == 27 ){ //esc
                AlbumListing.AddCancel();
                return false;
            }
            if( e.which == 13 ){ //enter
                AlbumListing.Add();
                return false;
            }
        });
        $( '#albumlist .add' ).hide();
    },
    AddCancel: function(){
        $( '#albumlist .new' ).remove();
        $( '#albumlist .add' ).show();
    },
    Add: function( callback ){
        var title = $( '#albumlist .new p input' ).val();
        $.post( 'album/create', {
            name: title
        }, function( data ){
            var title = $( '#albumlist .new input' ).val();
            var id = $( data ).find( 'album' ).attr( 'id' );
            $( '#albumlist .new' ).attr( 'id', 'album_' + id ).removeClass( 'new' )
                .find( 'p' ).addClass( 'editabletext' ).text( title ).end()
                .children( 'a' ).append( '<span class="deletebutton">×</span>' ).attr( 'href', 'albums/' + id );
            Kamibu.EditableTextElement( $( '#album_' + id + ' p' )[ 0 ], 'Όρισε όνομα', function( title ){
                $.post( '?resource=album&method=update', { 
                    albumid: id,
                    name: title
                });
            $( '#albumlist .add' ).show();
            });
        }, 'xml');
    },
    Init: function() {
        $( '.useralbums span.minimize' ).click( function() {
            if( $('.useralbums' ).hasClass( 'expanded' ) ){
                AlbumListing.Minimize();
                return false;
            }
            if( AlbumListing.Initialized ){
                AlbumListing.Maximize();
                return false;
            }
            AlbumListing.LoadAlbums( AlbumListing.Maximize );
            return false;
        });
        $( '.useralbums li:not(.new) a' ).live( 'click', function() {
            AlbumListing.LoadAlbum( $( this ).closest( 'li' ).attr( 'id' ).split( '_' )[ 1 ] );
            return false;
        } );
        $( '.deletebutton' ).live( 'click', function( e ){
            e.stopImmediatePropagation();
            if( confirm( 'Διαγραφή αυτού του άλμπουμ;' ) ){
                AlbumListing.Remove( $( this ).closest( 'li' ).attr( 'id' ).split( '_' )[ 1 ] );
            }
            return false;
        });
        $( '#albumlist .add label' ).live( 'click', function(){
            AlbumListing.AddClicked();
            return false;
        });
    }
};
