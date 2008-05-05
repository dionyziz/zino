var PhotoList = {
	Delete : function( albumid , username ) {
		Modals.Confirm( "Είσαι σίγουρος ότι θέλεις να διαγράψεις το album;" , function() {
			document.body.style.cursor = 'wait';
			Coala.Warm( 'album/delete' , { albumid : albumid } );
		} );
	}
};