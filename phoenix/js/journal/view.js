var JournalView = {
	Edit : function( journalid ) {
		
	},
	Delete : function( journalid ) {
		if ( confirm( "Θέλεις σίγουρα να διαγράψεις την καταχώρηση;" ) ){
			document.body.style.cursor = 'wait';
			Coala.Warm( 'journal/delete' , { journalid : journalid } );
		}
	}
};
$( document ).ready( function() {
	if ( $( 'div#journalnew' )[ 0 ] ) {
		$( 'div#journalnew form div.title input' )[ 0 ].select();
	}
} );