var PhotoList = {
	renaming : false,
	Delete : function( albumid ) {
		$( 'div#photolist div.owner div.delete a' ).css( 'background-image' , 'url( "' + ExcaliburSettings.imagesurl + 'delete.gif" )' );
		if ( confirm( 'Θέλεις σίγουρα να διαγράψεις το album;' ) ) {
			document.body.style.cursor = 'wait';
			Coala.Warm( 'album/delete' , { albumid : albumid } );
		}
		$( 'div#photolist div.owner div.delete a' ).css( 'background-image' , 'url( "' + ExcaliburSettings.imagesurl + 'delete2.gif" )' );		
	},
	Rename : function( albumid ) {
		if ( !PhotoList.renaming ) {
			PhotoList.renaming = true;
			var inputbox = document.createElement( 'input' );
			var albumname = $( 'div#photolist h2' ).html()
			$( inputbox ).attr( { 'type' : 'text' } ).css( 'width' , '200px' ).keydown( function( event ) {
				if ( event.keyCode == 13 ) {
					var name = $( this )[ 0 ].value;
					if ( albumname != name && name !== '' ) {
						window.document.title = name + ' | ' + ExcaliburSettings.applicationname;
						Coala.Warm( 'album/rename' , { albumid : albumid , albumname : name } );
					}
					$( 'div#photolist h2' ).empty().append( document.createTextNode( name ) );
					PhotoList.renaming = false;
				}
			} );
			$( inputbox )[ 0 ].value = albumname;
			$( 'div#photolist h2' ).empty().append( inputbox );
		}
		$( 'div#photolist h2 input' )[ 0 ].select();
	},
	UploadPhoto : function() {
		alert( 'want to upload' );
		$( '#uploadform' )[ 0 ].submit();
	},
	AddPhoto : function( imageinfo ) {
		photoid = imageinfo.id
		userid = imageinfo.userid;
	}
};