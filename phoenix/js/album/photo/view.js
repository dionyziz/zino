var PhotoView = {
	renaming : false,
	Rename : function( photoid , albumname ) {
		if ( !PhotoView.renaming ) {
			PhotoView.renaming = true;
			var inputbox = document.createElement( 'input' );
			var photoname = $( 'div#photoview h2' ).html();
			$( inputbox ).attr( { 'type' : 'text' } ).css( 'width' , '200px' ).keydown( function( event ) {
				if ( event.keyCode == 13 ) {
					var name = $( this )[ 0 ].value;
					if ( photoname != name ) {
						Coala.Warm( 'album/photo/rename' , { photoid : photoid , photoname : name } );
						if ( name === '' ) {
							window.document.title = albumname + ' | ' + ExcaliburSettings.applicationname;
							$( 'div.owner div.edit a' ).empty().append( document.createTextNode( 'Όρισε όνομα' ) );
						}
						else {
							window.document.title = name + ' | ' + ExcaliburSettings.applicationname;
							$( 'div.owner div.edit a' ).empty().append( document.createTextNode( 'Μετονομασία' ) );
						}
					}
					$( 'div#photoview h2' ).empty().append( document.createTextNode( name ) );
					PhotoView.renaming = false;
				}
			} );
			$( inputbox )[ 0 ].value = photoname;
			$( 'div#photoview h2' ).empty().append( inputbox );
		}
		$( 'div#photoview h2 input' )[ 0 ].select();
	},
	Delete : function( photoid ) {
		$( 'div#photoview div.owner div.delete a' ).css( 'background-image' , 'url( "' + ExcaliburSettings.imagesurl + 'delete.gif" )' );
		if ( confirm( "Θέλεις σίγουρα να διαγράψεις την φωτογραφία;" ) ) {
			Coala.Warm( 'album/photo/delete' , { photoid : photoid } );
		}
		$( 'div#photoview div.owner div.delete a' ).css( 'background-image' , 'url( "' + ExcaliburSettings.imagesurl + 'delete2.gif" )' );
	},
	MainImage : function( photoid ) {
		$( 'div#photoview div.owner div.mainimage' ).animate( { opacity : "0" } , 400 , function() {
			$( this ).remove();
		} );
		Coala.Warm( 'album/photo/mainimage' , { photoid : photoid } );
	}
};