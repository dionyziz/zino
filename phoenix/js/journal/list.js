var JournalList = {
	AddFav : function( /* journal id will be used as a parameter */ linknode ) {
		/*
		do not add this function with jquery, as a parameter is needed according to the journal 
		that needs to be faved. Maybe a user id is also needed to fav a journal
		*/
		$( linknode ).animate( { opacity: "0" } , 800 , function() {
			linknode.firstChild.src = 'http://static.zino.gr/phoenix/heart.png';
			linknode.href = '';
			linknode.title = 'Είναι αγαπημένο';
			$( linknode ).animate( { opacity: "1" } , 800 );
			$( linknode ).click( function() {
				return false;
			});
		});
		
		//make Coala call
	}
};