var PhotoList = {
	Delete : function( albumid ) {
		if ( confirm( 'Θέλεις σίγουρα να διαγράψεις το album;' ) ) {
			document.body.style.cursor = 'wait';
			Coala.Warm( 'album/delete' , { albumid : albumid } );
		} 
	},
	Rename : function( albumid ) {
		var inputbox = document.createElement( 'input' );
		$( inputbox ).attr( { 'type' : 'text' } ).css( 'width' , '200px' );
		$( inputbox )[ 0 ].value = $( 'div#photolist h2' ).html();
		$( 'div#photolist h2' ).empty().append( inputbox );
		$( inputbox )[ 0 ].select();
	}
};