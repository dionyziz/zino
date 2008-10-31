var JournalView = {
	Delete : function( journalid ) {
		if ( confirm( "Θέλεις σίγουρα να διαγράψεις την καταχώρηση;" ) ){
			document.body.style.cursor = 'wait';
			Coala.Warm( 'journal/delete' , { journalid : journalid } );
		}
	},
	AddFav : function( journalid , linknode ) {
		if ( $( linknode ).find( 'span' ).hasClass( 's_addfav' ) ) {
			$( linknode ).fadeOut( 800 , function() {
				$( linknode )
				.attr( {
					href : '',
					title : 'Αγαπημένο'
				} )
				.removeClass( 's_addfav' )
				.addClass( 's_isaddedfav' )
				.empty()
				.fadeIn( 800 );
			} );
			Coala.Warm( 'favourites/add' , { itemid : journalid , typeid : Types.Journal } );
		}
	}
};