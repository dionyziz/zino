var PollView = {
	Delete : function( pollid ) {
		if ( confirm( "Θέλεις σίγουρα να διαγράψεις τη δημοσκόπηση;" ) ) {
			document.body.style.cursor = 'wait';
			Coala.Warm( 'poll/delete' , { pollid : pollid } );
		}
	},
	Vote : function( optionid , pollid , node ) {
		var parent = node.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode;
		$( parent ).animate( { opacity : "0" } , 400 );
		Coala.Warm( 'poll/vote' , { optionid : optionid , pollid : pollid , node : parent } );
	}
};