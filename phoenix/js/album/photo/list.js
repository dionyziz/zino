var PhotoList = {
	renaming : false,
	Delete : function( albumid ) {
		$( 'div#photolist div.owner div.delete a' ).css( 'background-image' , 'url( "' + ExcaliburSettings.imagesurl + 'delete.gif" )' );
		if ( confirm( 'Θέλεις σίγουρα να διαγράψεις το album;' ) ) {
			document.body.style.cursor = 'wait';
			Coala.Warm( 'album/delete' , { albumid : albumid } );
		}
		$( 'div#photolist div.owner div.delete a' ).css( 'background-image' , 'url( "' + ExcaliburSettings.imagesurl + 'delete2.gif" )' );		
		return false;
	},
	Rename : function( albumid ) {
		if ( !PhotoList.renaming ) {
			PhotoList.renaming = true;
			var inputbox = document.createElement( 'input' );
			var albumname = $( 'div#photolist h2' ).html();
			$( inputbox ).attr( { 'type' : 'text' } ).css( 'width' , '200px' ).keydown( function( event ) {
				if ( event.keyCode == 13 ) {
					PhotoList.renameFunc( this, albumid, albumname );
				}
			} ).blur( function() { PhotoList.renameFunc( this, albumid, albumname ); } );
			$( inputbox )[ 0 ].value = albumname;
			$( 'div#photolist h2' ).empty().append( inputbox );
		}
		$( 'div#photolist h2 input' )[ 0 ].select();
		return false;
	},
	UploadPhoto : function() {
        alert("1");
		$( 'form#uploadform' )[ 0 ].submit();
		alert("2");
        $( 'form#uploadform' ).hide();
		alert("3");
        $( 'div#uploadingwait' ).show();
        alert("4");
	},
	AddPhoto : function( imageinfo , x100 ) {
		imageid = imageinfo.id;
		var li = document.createElement( 'li' );
		$( li ).css( 'display' , 'none' );
		$( 'div#photolist ul' ).prepend( li );
		Coala.Warm( 'album/photo/upload' , { imageid : imageid , node : li , x100 : x100 } );
		if ( imageinfo.imagesnum == 1 ) {
			var dt = document.createElement( 'dt' );
			$( dt ).addClass( 'photonum' );
			$( 'div#photolist dl' ).prepend( dt );
		}
        if ( x100 ) { // if on schools page...
            Modals.Destroy();
        }
		PhotoList.UpdatePhotoNum( imageinfo.imagesnum );
	},
	UpdatePhotoNum : function( photonum ) {
		if ( photonum === 0 ) {
			$( 'div#photolist dl dt.photonum' ).remove();
		}
		else {
			var text = document.createTextNode( photonum );
			$( 'div#photolist dl dt.photonum' ).empty().append( text );
		}
	},
	renameFunc : function( elem, albumid, albumname ) {
		var name = elem.value;
		if ( albumname != name && name !== '' ) {
			window.document.title = name + ' | ' + ExcaliburSettings.applicationname;
			Coala.Warm( 'album/rename' , { albumid : albumid , albumname : name } );
		}
		if ( name!== '' ) {
			$( 'div#photolist h2' ).empty().append( document.createTextNode( name ) );
		}
		PhotoList.renaming = false;
	}
};
