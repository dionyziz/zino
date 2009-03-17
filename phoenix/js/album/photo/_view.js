var PhotoView = {
	renaming : false,
	Rename : function( photoid , albumname ) {
		if ( !PhotoView.renaming ) {
			PhotoView.renaming = true;
			var inputbox = document.createElement( 'input' );
			var photoname = $( 'div#pview h2' ).text();
			$( inputbox ).attr( { 'type' : 'text' } ).css( 'width' , '200px' ).keydown( function( event ) {
				if ( event.keyCode == 13 ) {
					PhotoView.renameFunc( this, photoid, photoname, albumname );
				}
			} ).blur( function() { PhotoView.renameFunc( this, photoid, photoname, albumname ); } );
			$( inputbox )[ 0 ].value = photoname;
			$( 'div#pview h2' ).empty().append( inputbox );
		}
		$( 'div#pview h2 input' )[ 0 ].select();
		return false;
	},
	Delete : function( photoid ) {
		if ( confirm( "Θέλεις σίγουρα να διαγράψεις την φωτογραφία;" ) ) {
			Coala.Warm( 'album/photo/delete' , { photoid : photoid } );
		}
		return false;
	},
	MainImage : function( photoid , node ) {
		Coala.Warm( 'album/photo/mainimage' , { photoid : photoid } );
		$( node.parentNode ).fadeOut( 200 , function() {
			$( this ).empty()
			.append( document.createTextNode( 'Ορίστηκε ως προεπιλεγμένη' ) )
			.fadeIn( 400 );
		} );
		return false;
	},
	AddFav : function( photoid , linknode ) {
		if ( $( linknode ).find( 'span' ).hasClass( 's_addfav' ) ) {
			$( linknode ).fadeOut( 800 , function() {
				$( linknode ).attr( {
					href : '',
					title : 'Αγαπημένο'
				} )
				.removeClass( 's_addfav' )
				.addClass( 's_isaddedfav' )
				.empty()
				.fadeIn( 800 );
			} );
			Coala.Warm( 'favourites/add' , { itemid : photoid , typeid : Types.Image } );
		}
		return false;
	},
    completeFav : function() {
        $( 'div#pview div.image_tags:last' ).children( 'div:last' ).remove();
        
        return false;
    },
	renameFunc : function( elem, photoid, photoname, albumname ) {
		var name = elem.value;
		if ( photoname != name ) {
			Coala.Warm( 'album/photo/rename' , { photoid : photoid , photoname : name } );
			var span = document.createElement( 'span' );
			$( span ).addClass( 's_edit' ).css( 'paddingLeft' , '19px' );
			if ( name === '' ) {
				window.document.title = albumname + ' | ' + ExcaliburSettings.applicationname;
				$( 'div.owner div.edit a' ).empty()
				.append( span )
				.append( document.createTextNode( 'Όρισε όνομα' ) );
			}
			else {
				window.document.title = name + ' | ' + ExcaliburSettings.applicationname;
				$( 'div.owner div.edit a' ).empty()
				.append( span )
				.append( document.createTextNode( 'Μετονομασία' ) );
			}
		}
		$( 'div#pview h2' ).empty().append( document.createTextNode( name ) );
		PhotoView.renaming = false;
	}
};
