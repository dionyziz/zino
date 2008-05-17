var PollView = {
	Delete : function( pollid ) {
		$( 'div#pollview div.delete a' ).css( 'background-image' , 'url( "' + ExcaliburSettings.imagesurl + 'delete.gif" )' );
		if ( confirm( "Θέλεις σίγουρα να διαγράψεις τη δημοσκόπηση;" ) ) {
			document.body.style.cursor = 'wait';
			Coala.Warm( 'poll/delete' , { pollid : pollid } );
		}
		$( 'div#pollview div.delete a' ).css( 'background-image' , 'url( "' + ExcaliburSettings.imagesurl + 'delete2.gif" )' );
	},
	Vote : function( optionid , pollid , node ) {
		var parent = node.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode;
		$( parent ).html( $( 'div.pollsmall div.voting' ).html() );
		Coala.Warm( 'poll/vote' , { optionid : optionid , pollid : pollid , node : parent } );
	}
};
$( document ).ready( function() { 
	if ( $( 'div#pollview' )[ 0 ] ){
		var delete1 = new Image();
		delete1.src = ExcaliburSettings.imagesurl + 'delete1.gif';
		var delete2 = new Image();
		delete2.src = ExcaliburSettings.imagesurl + 'delete2.gif';
	}
	
} );