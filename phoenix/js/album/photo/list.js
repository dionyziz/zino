var PhotoList = {
	renaming : false,
	Delete : function( albumid ) {
		if ( confirm( 'Θέλεις σίγουρα να διαγράψεις το album;' ) ) {
			document.body.style.cursor = 'wait';
			Coala.Warm( 'album/delete' , { albumid : albumid } );
		} 
	},
	Rename : function( albumid ) {
		if ( !PhotoList.renaming ) {
			var inputbox = document.createElement( 'input' );
			var albumname = $( 'div#photolist h2' ).html()
			$( inputbox ).attr( { 'type' : 'text' } ).css( 'width' , '200px' ).keydown( function( event ) {
				if ( event.keyCode == 13 ) {
					var name = $( this )[ 0 ].value;
					$( 'div#photolist h2' ).empty().html( name );
					PhotoList.renaming = false
					if ( albumname != name ) {
						alert( 'saving: ' + name );
						Coala.Warm( 'album/rename' , { albumid : albumid , albumname : name } );
					}
				}
			} );
			$( inputbox )[ 0 ].value = albumname;
			$( 'div#photolist h2' ).empty().append( inputbox );
			PhotoList.renaming = true;
		}
		$( 'div#photolist h2 input' )[ 0 ].select();
	}
};