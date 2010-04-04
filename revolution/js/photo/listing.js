var PhotoListing = {
    Initialized: false,
    PhotoList: null,
    PhotoPrototype: null,
    CurrentPage: 1,
    LastLoaded: null,
    Init: function(){
        this.PhotoList = $( '.photofeed ul' );
        this.PlaceholderHTML = '';
        for( var i = 0; i < 150; ++i ){
            this.PlaceholderHTML += '<li><a><img height="150px"/></a></li>';
        }
        this.LastLoaded = $( '.photofeed ul li:last' )[ 0 ];
        this.AssignEvents();
        this.Initialized = true;
    },
    ScrollHandler: function(){
        if( PhotoListing.PhotoList.height() - $( window ).scrollTop() - $( window ).height() < 500 ){
            PhotoListing.PhotoList[ 0 ].innerHTML += PhotoListing.PlaceholderHTML;
            setTimeout( PhotoListing.FetchNewPhotos, 100 );
        }
    },
    AssignEvents: function(){
        $( window ).scroll( this.ScrollHandler );
    },
    FetchNewPhotos: function(){
       PhotoListing.CurrentPage++;
       $.get( 'http://alpha.zino.gr/petros?resource=photo&method=listing&page=' + PhotoListing.CurrentPage, function( xml ){
           $( xml ).find( 'entry' ).each( function(){
               var id = $( this ).attr( 'id' );
               var url = $( this ).find( 'media' ).attr( 'url' );
               var count = $( this ).find( 'discussion' ).attr( 'count' );
               alert( PhotoListing.LastLoaded );
               alert( PhotoListing.LastLoaded = PhotoListing.LastLoaded.nextSibling );
               $( 'img', $( ph ) ).attr( 'src', url );
           } );
       }, 'xml' )
    }
}
PhotoListing.Init();
