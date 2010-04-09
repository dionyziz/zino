var PhotoListing = {
    Initialized: false,
    PhotoList: null,
    PhotoPrototype: null,
    CurrentPage: 1,
    LastLoaded: null,
Init: function(){
    alert(' the test ');
        this.PhotoList = $( '.photofeed ul' );
        this.PlaceholderHTML = '';
        for( var i = 0; i < 100; ++i ){
            this.PlaceholderHTML += '<li><a><img height="150px"/></a></li>';
        }
        this.LastLoaded = $( '.photofeed ul li:last' )[ 0 ];
        this.AssignEvents();
        this.Initialized = true;
    },
    ScrollHandler: function(){
        if( PhotoListing.PhotoList.height() - $( window ).scrollTop() - $( window ).height() < 500 ){
            PhotoListing.PhotoList[ 0 ].innerHTML += PhotoListing.PlaceholderHTML;
            PhotoListing.LastLoaded = $( '.photofeed ul li')[ PhotoListing.CurrentPage * 100 - 1];
            PhotoListing.FetchNewPhotos()
        }
    },
    AssignEvents: function(){
        $( window ).scroll( this.ScrollHandler );
    },
    FetchNewPhotos: function(){
       PhotoListing.CurrentPage++;
       $.get( 'photos',
        { 'page': PhotoListing.CurrentPage },
        function( xml ){
           $( xml ).find( 'entry' ).each( function(){
               var id = $( this ).attr( 'id' );
               var url = $( this ).find( 'media' ).attr( 'url' );
               var count = $( this ).find( 'discussion' ).attr( 'count' );
               do {
                   PhotoListing.LastLoaded = PhotoListing.LastLoaded.nextSibling;
               } while ( PhotoListing.LastLoaded.nodeType != 1);

               $( 'img', $( PhotoListing.LastLoaded ) ).attr( 'src', url );
               $( 'a', $( PhotoListing.LastLoaded ) ).attr( 'href', 'photos/' + id );
               if( count != 0 ){
                   $( 'a', $( PhotoListing.LastLoaded ) ).append( $( '<span class="countbubble">' + count + '</span>' ) );
               }
           } );
       } )
    }
}
