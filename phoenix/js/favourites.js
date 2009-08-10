var Favourites = {
    Delete: function( favid ) {
        Coala.Warm( 'favourites/delete', { 'favid': favid } );
        fav = $( "ul.events > li#favourite_" + favid )
        fav.fadeTo( 300, 0 ).slideUp( 500, function() {
            fav.remove();
            $( "ul.events > li:last" ).addClass( "last" ).siblings().removeClass( "last" );
        } );
        return false;
    },

    Add : function( id , type , linknode ) {
		if ( $( linknode ).find( 'span' ).hasClass( 's1_0019' ) ) {
			$( linknode ).fadeOut( 800 , function() {
				$( linknode ).attr( {
					href : '',
					title : 'Αγαπημένο'
				} )
				.removeClass( 's1_0019' )
				.addClass( 's1_0020' )
                .css( 'padding-left' , '18px' )
				.empty()
				.fadeIn( 800 );
			} );
			Coala.Warm( 'favourites/add' , { itemid : id , typeid : Types[ type ] } );
        }

        return false;
    }
};
