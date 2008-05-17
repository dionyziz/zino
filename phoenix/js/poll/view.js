var PollView = {
	Delete : function( pollid ) {
		
		if ( confirm( "Θέλεις σίγουρα να διαγράψεις τη δημοσκόπηση;" ) ) {
			document.body.style.cursor = 'wait';
			Coala.Warm( 'poll/delete' , { pollid : pollid } );
		}
	},
	Vote : function( optionid , pollid , node ) {
		var parent = node.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode;
		$( parent ).html( $( 'div.pollsmall div.voting' ).html() );
		Coala.Warm( 'poll/vote' , { optionid : optionid , pollid : pollid , node : parent } );
	}
};