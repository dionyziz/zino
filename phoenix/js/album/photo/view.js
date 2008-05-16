var PhotoView = {
	renaming : false,
	Rename : function( photoid ) {
		if ( !PhotoView.renaming ) {
			var inputbox = document.createElement( 'input' );
			var photoname = $( 'div#photoview h2' ).html()
			$( inputbox ).attr( { 'type' : 'text' } ).css( 'width' , '200px' ).keydown( function( event ) {
				if ( event.keyCode == 13 ) {
					var name = $( this )[ 0 ].value;
					if ( photoname != name && name !== '' ) {
						PhotoView.renaming = false;
						$( 'div#photoview h2' ).empty().append( document.createTextNode( name ) );
						window.document.title = name;
						Coala.Warm( 'photo/rename' , { photoid : photoid , photoname : name } );
					}
				}
			} );
			$( inputbox )[ 0 ].value = photoname;
			$( 'div#photoview h2' ).empty().append( inputbox );
			PhotoView.renaming = true;
		}
		$( 'div#photoview h2 input' )[ 0 ].select();
	}
};