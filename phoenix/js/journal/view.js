var JournalView = {
	Delete : function( journalid ) {
		if ( confirm( "Θέλεις σίγουρα να διαγράψεις την καταχώρηση;" ) ){
			document.body.style.cursor = 'wait';
			Coala.Warm( 'journal/delete' , { journalid : journalid } );
		}
	},
	AddFav : function( journalid , linknode ) {
		if ( $( linknode ).find( 'span' ).hasClass( 's_addfav' ) ) {
			$( linknode ).animate( { opacity: "0" } , 800 , function() {
				$( linknode )
				.css( 'cursor' , 'default' )
				.attr( {
					href : '',
					title : 'Αγαπημένο'
				} )
				.find( 'span' )
				.removeClass( 's_addfav' )
				.addClass( 's_isaddedfav' )
				.empty()
				.animate( { opacity: "1" } , 800 );
			} );
			Coala.Warm( 'favourites/add' , { itemid : journalid , typeid : Types.Journal } );
		}
	}
};