var PhotoView = {
	renaming : false,
	Rename : function( photoid ) {
		if ( !PhotoView.renaming ) {
			PhotoView.renaming = true;
			var inputbox = document.createElement( 'input' );
			var photoname = $( 'div#photoview h2' ).html()
			$( inputbox ).attr( { 'type' : 'text' } ).css( 'width' , '200px' ).keydown( function( event ) {
				if ( event.keyCode == 13 ) {
					var name = $( this )[ 0 ].value;
					if ( photoname != name && name !== '' ) {
						PhotoView.renaming = false;
						window.document.title = name;
						Coala.Warm( 'photo/rename' , { photoid : photoid , photoname : name } );
					}
					$( 'div#photoview h2' ).empty().append( document.createTextNode( name ) );
				}
			} );
			$( inputbox )[ 0 ].value = photoname;
			$( 'div#photoview h2' ).empty().append( inputbox );
		}
		$( 'div#photoview h2 input' )[ 0 ].select();
	}
};