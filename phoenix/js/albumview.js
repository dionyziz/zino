var AlbumPhotosNum = g( 'album_photosnum' ).childNodes[ 0 ].nodeValue;
var AlbumMainImage = g( 'album_mainimage' ).childNodes[ 0 ].nodeValue;

if ( AlbumPhotosNum === 0 ) {
	Photos.Newphoto( document.getElementById( 'newphotolink' ) );
}

var MainImageNode = g( 'photo' + AlbumMainImage );