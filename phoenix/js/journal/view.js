var JournalView = {
	Delete : function( journalid ) {
		if ( confirm( "Θέλεις σίγουρα να διαγράψεις την καταχώρηση;" ) ){
			document.body.style.cursor = 'wait';
			Coala.Warm( 'journal/delete' , { journalid : journalid } );
		}
		return false;
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
		return false;
	},
    OnLoad : function() {
        Coala.Cold( 'admanager/showad', { f: function ( html ) {
            var ads = $( 'div.ads' )[ 0 ];
            ads.innerHTML = html;
            if ( ads.offsetHeight >= ads.parentNode.offsetHeight ) {
                $( ads.parentNode ).css( 'height' , ads.offsetHeight );
            }
        } } );
    }
};
