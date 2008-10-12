var JournalView = {
	Delete : function( journalid ) {
		if ( confirm( "Θέλεις σίγουρα να διαγράψεις την καταχώρηση;" ) ){
			document.body.style.cursor = 'wait';
			Coala.Warm( 'journal/delete' , { journalid : journalid } );
		}
	},
	AddFav : function( journalid , linknode ) {
		if ( $( linknode ).hasClass( 'add' ) ) {
			$( linknode ).animate( { opacity: "0" } , 800 , function() {
				$( linknode ).attr( {
					href : '',
					title : 'Αγαπημένο'
				} )
				.removeClass( 'add' )
				.addClass( 'isadded' )
				.empty()
				.animate( { opacity: "1" } , 800 );
			} );
			Coala.Warm( 'favourites/add' , { itemid : journalid , typeid : Types.Journal } );
		}
	}
};
$( function() {
	if ( $( 'div#journalview' )[ 0 ] ) {
		var delete1 = new Image();
		delete1.src = ExcaliburSettings.imagesurl + 'delete.gif';
		var delete2 = new Image();
		delete2.src = ExcaliburSettings.imagesurl + 'delete2.gif';
	}
} );