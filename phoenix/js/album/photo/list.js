var PhotoList = {
	Delete : function( albumid , username ) {
		if ( confirm( "Θέλεις σίγουρα να διαγράψεις το album;" ) ) {
			document.body.style.cursor = 'wait';
			Coala.Warm( 'album/delete' , { albumid : albumid } );
		} );
	}
};