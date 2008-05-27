var JournalList = {
	AddFav : function( /* journal id will be used as a parameter */ linknode ) {
		/*
		do not add this function with jquery, as a parameter is needed according to the journal 
		that needs to be faved. Maybe a user id is also needed to fav a journal
		*/

		$( linknode ).animate( { opacity: "0" } , 800 , function() {
			$( linknode ).attr( {
				href : '',
				title : 'Είναι αγαπημένο'
			} )
			.click( function() {
				return false;
			} )
			.css( 'backgound-position' , '0 -1261px' )
			.animate( { opacity: "1" } , 800 );
		});

		
		//make Coala call
	}
};