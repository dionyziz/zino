var JournalList = {
	AddFav : function( /* journal id will be used as a parameter */ linknode ) {
		/*
		do not add this function with jquery, as a parameter is needed according to the journal 
		that needs to be faved. Maybe a user id is also needed to fav a journal
		*/
		if ( linknode.firstChild.src == 'http://static.zino.gr/phoenix/heart_add.png' ) {
			$( linknode ).animate( { opacity: "0" } , 800 , function() {
				linknode.firstChild.src = 'http://static.zino.gr/phoenix/heart.png';
				linknode.firstChild.title = 'Είναι αγαπημένο';
				linknode.href = '';
				$( linknode ).animate( { opacity: "1" } , 800 ).css( {cursor : "default" } ).click( function() {
					return false;
				});
			});
		}
		
		//make Coala call
	}
};