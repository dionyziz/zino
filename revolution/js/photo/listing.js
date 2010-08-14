var PhotoListing = {
    Initialized: false,
    PhotoList: null,
    PhotoPrototype: null,
    CurrentPage: 1,
    LastLoaded: null,
    Loading: false,
    EndOfPhotos: false,
    Init: function(){
        this.PlaceholderHTML = '';
        for( var i = 0; i < 100; ++i ){
            this.PlaceholderHTML += '<li><a><img /></a></li>';
        }
        PhotoListing.PreparePhotoList();
        if( $( '.useralbums' ) ) {
            AlbumListing.Init();
        }
        $( '.photostream ul li ar' ).live( 'click', function(){
            PhotoListing.Preview( $( this ).parent().attr( 'id' ).split( '_' )[1], $( this ).children( 'img' ).attr( 'src' ).slice( 0, -7 ) + 'full.jpg' );
            return false;
        });
        $( '.imageoverlay .arrow:not(.disabled)' ).live( 'click', function(){
            $( '.imageoverlay .arrow' ).removeClass( 'disabled' );
            $( '.photostream ul li' ).removeClass( 'selected' );
            var id = $( '.imageoverlay img' ).attr( 'id' ).split( '_' )[ 1 ];
            if( $( this ).hasClass( 'left' ) ){
                var fid = $( '#photo_' + id ).prev().addClass( 'selected' ).attr( 'id' ).split( '_' )[ 1];
            }
            else{
                var fid = $( '#photo_' + id ).next( ':not(.justifyhack)' ).addClass( 'selected' ).attr( 'id' ).split( '_' )[ 1];
            }
            var src = $( '#photo_' + fid ).find( 'img' ).attr( 'src' ).slice( 0, -7 ) + 'full.jpg';
            if( !$( '#photo_' + fid ).prev().length ){
                $( '.imageoverlay .left' ).addClass( 'disabled' );
            }
            if( !$( '#photo_' + fid ).next().length ){
                $( '.imageoverlay .right' ).addClass( 'disabled' );
            }
            $( '.imageoverlay img' ).attr( 'id', 'sel_' + fid ).attr( 'src', src );
        });
    },
    Preview: function( id, src ){
        $( '#photo_' + id ).addClass( 'selected' );
        $( '<div class="imageoverlay">'
                +'<div class="arrow left"></div>'
                +'<div class="arrow right"></div>'
                +'<img id="sel_' + id + '" src="' + src + ' />'
            +'</div>' ).appendTo( 'body' );
    },
    CancelPreview: function(){
        
    },
    SetUploadAction: function() {
        SI.Files.stylizeAll();
        $( '.photostream input[type=file]' ).change( function () {
            $( this ).parents( 'form' )[ 0 ].submit();
            $( 'body' ).append(
                '<div class="wait">'
                    + '<img src="http://static.zino.gr/phoenix/ajax-loader.gif" />'
                + '</div>'
            );
        } );
    },
    PreparePhotoList: function() {
        PhotoListing.PhotoList = $( '.photostream ul' );
        PhotoListing.LastLoaded = $( '.photostream ul li:last' )[ 0 ];
        PhotoListing.CurrentPage = 1;
        if ( $( '.photostream ul li' ).length < 100 ) {
            for ( i = 0; i < 20; ++i ) { //Last Line justify hack
                PhotoListing.PhotoList[ 0 ].innerHTML += ' <li class="justifyhack"><a><img /></a></li> ';
            }
            PhotoListing.RemoveEvents();
            PhotoListing.SetUploadAction();
            return;
        }
        PhotoListing.SetUploadAction();
        PhotoListing.AssignEvents();
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
        var target = window.location.href;
        if( window.location.hash == '' ){
            target = window.location.hash.split( '#' );
            target = target[ 1 ];
        }
        $.get( target,
            { 'page': PhotoListing.CurrentPage },
            function( xml ) {
                var responseSize = $( xml ).find( 'photo' ).length;
                var i;

                $( xml ).find( 'photo' ).each( function( index ){
                    var id = $( this ).attr( 'id' );
                    var url = $( this ).find( 'media' ).attr( 'url' );
                    var count = $( this ).find( 'discussion' ).attr( 'count' );
                    var user = $( this ).find( 'author name' ).text();
                    do {
                        PhotoListing.LastLoaded = PhotoListing.LastLoaded.nextSibling;
                    } while ( PhotoListing.LastLoaded.nodeType != 1);

                    if ( url ) {
                        $( 'img', $( PhotoListing.LastLoaded ) ).attr( 'src', url );
                    }
                    else {
                        alert( id );
                    }
                    if ( user ) {
                        $( 'img', $( PhotoListing.LastLoaded ) ).attr( 'alt', user );
                        $( 'img', $( PhotoListing.LastLoaded ) ).attr( 'title', user );
                    }
                    $( 'a', $( PhotoListing.LastLoaded ) ).attr( 'href', 'photos/' + id );
                    if ( count != '0' ) {
                        if ( count < 100 ) {
                            $( 'a', $( PhotoListing.LastLoaded ) ).append( $( '<span class="countbubble">' + count + '</span>' ) ); }
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
            }
        );
    }
};
