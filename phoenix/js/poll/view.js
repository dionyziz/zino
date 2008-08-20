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
		//$( 'div.lastpoll div.container' ).css( { marginRight : '260px' } );
		Coala.Warm( 'poll/vote' , { optionid : optionid , pollid : pollid , node : parent } );
		//$( 'div.lastpoll' ).css( { marginRight : '0px' } );
	}
};
$( document ).ready( function() { 
	if ( $( 'div#pollview' )[ 0 ] ){
		var delete1 = new Image();
		delete1.src = ExcaliburSettings.imagesurl + 'delete.gif';
		var delete2 = new Image();
		delete2.src = ExcaliburSettings.imagesurl + 'delete2.gif';
	}
	
} );
