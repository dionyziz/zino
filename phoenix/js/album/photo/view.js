var PhotoView = {
	renaming : false,
	Rename : function( photoid , albumname ) {
		if ( !PhotoView.renaming ) {
			PhotoView.renaming = true;
			var inputbox = document.createElement( 'input' );
			var photoname = $( 'div#photoview h2' ).text();
			$( inputbox ).attr( { 'type' : 'text' } ).css( 'width' , '200px' ).keydown( function( event ) {
				if ( event.keyCode == 13 ) {
					PhotoView.renameFunc( this, photoid, photoname, albumname );
				}
			} ).blur( function() { PhotoView.renameFunc( this, photoid, photoname, albumname ); } );
			$( inputbox )[ 0 ].value = photoname;
			$( 'div#photoview h2' ).empty().append( inputbox );
		}
		$( 'div#photoview h2 input' )[ 0 ].select();
		return false;
	},
	Delete : function( photoid ) {
		if ( confirm( "Θέλεις σίγουρα να διαγράψεις την φωτογραφία;" ) ) {
			Coala.Warm( 'album/photo/delete' , { photoid : photoid } );
		}
		return false;
	},
	MainImage : function( photoid ) {
		Coala.Warm( 'album/photo/mainimage' , { photoid : photoid } );
		$( 'div#photoview div.owner div.mainimage' ).hide().empty()
		.append( document.createTextNode( 'Ορίστηκε ως προεπιλεγμένη' ) )
		.css( "opacity" , "0" )
		.show()
		.animate( { opacity : "1" } , 400 );
		return false;
	},
	AddFav : function( photoid , linknode ) {
		if ( $( linknode ).hasClass( 'add' ) ) {
			$( linknode ).animate( { opacity: "0" } , 800 , function() {
				$( linknode ).attr( {
					href : '',
					title : 'Αγαπημένο'
				} )
				.removeClass( 'add' )
				.addClass( 'isadded' )
				.empty()
				.animate( { opacity: "1" } , 800 );
			} );
			Coala.Warm( 'favourites/add' , { itemid : photoid , typeid : Types.Image } );
		}
		return false;
	},
	renameFunc : function( elem, photoid, photoname, albumname ) {
		var name = elem.value;
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
};
