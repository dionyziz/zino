var PhotoList = {
	Delete : function( albumid , username ) {
		Confirm( "Θέλεις σίγουρα να διαγράψεις το album;" , function() {
			document.body.style.cursor = 'wait';
			Coala.Warm( 'album/delete' , { albumid : albumid } );
		} );
	}
};