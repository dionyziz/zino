var PhotoList = {
	Delete : function( albumid ) {
		if ( confirm( 'Θέλεις σίγουρα να διαγράψεις το album;' ) ) {
			document.body.style.cursor = 'wait';
			Coala.Warm( 'album/delete' , { albumid : albumid } );
		} 
	},
	Rename : function( albumid ) {
		var albumname = $( 'div#photolist' ).html();
		var inputbox = document.createElement( 'input' );
		$( 'div#photolist h2' ).append( $( inputbox ).attr( { 'type' : 'text' } ).append( document.createTextNode( albumname ) ).select() );
	}
};