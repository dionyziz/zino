var JournalView = {
	Edit : function( journalid ) {
		
	},
	Delete : function( journalid ) {
		$( 'div#journalview div.owner div.delete a' ).css( 'background-image' , 'url( "' + ExcaliburSettings.imagesurl + 'delete.gif" )' );
		if ( confirm( "Θέλεις σίγουρα να διαγράψεις την καταχώρηση;" ) ){
			document.body.style.cursor = 'wait';
			Coala.Warm( 'journal/delete' , { journalid : journalid } );
		}
		$( 'div#journalview div.owner div.delete a' ).css( 'background-image' , 'url( "' + ExcaliburSettings.imagesurl + 'delete2.gif" )' );
	},
	Create : function() {
		alert( 'creating' );
	}
};
$( document ).ready( function() {
	if ( $( 'div#journalnew' )[ 0 ] ) {
		$( 'div#journalnew form div.title input' )[ 0 ].select();
	}
	if ( $( 'div#journalview' )[ 0 ] ) {
		var delete1 = new Image();
		delete1.src = ExcaliburSettings.imagesurl + 'delete.gif';
		var delete2 = new Image();
		delete2.src = ExcaliburSettings.imagesurl + 'delete2.gif';
	}
} );