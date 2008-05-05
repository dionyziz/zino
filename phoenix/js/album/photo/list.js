var PhotoList = {
	Delete : function( albumid ) {
		if ( confirm( 'Θέλεις σίγουρα να διαγράψεις το album;' ) ) {
			document.body.style.cursor = 'wait';
			Coala.Warm( 'album/delete' , { albumid : albumid } );
		} 
	},
	Rename : function( albumid ) {
		var inputbox = document.createElement( 'input' );
		var name = $( 'div#photolist h2' ).html();
		alert( name );
		$( 'div#photolist h2' ).empty().append( $( inputbox ).attr( { 'type' : 'text' } ).html( name ) );
		$( inputbox )[ 0 ].select();
	}
};