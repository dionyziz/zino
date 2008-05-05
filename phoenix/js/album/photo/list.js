var PhotoList = {
	Delete : function( albumid ) {
		if ( confirm( 'Θέλεις σίγουρα να διαγράψεις το album;' ) ) {
			document.body.style.cursor = 'wait';
			Coala.Warm( 'album/delete' , { albumid : albumid } );
		} 
	},
	Rename : function() {
		var albumname = $( 'div#photolist' ).html();
		var inputbox = document.createElement( 'input' );
		$( inputbox ).attr( { "type" , "text" } );
		$( 'div#photolist' ).append( $( inputbox ).attr( { 'type' : 'text' } ).append( document.createTextNode( albumname ) ).select() );
	}
};