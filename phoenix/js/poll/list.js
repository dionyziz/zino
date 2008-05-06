var PollList = {
	Create : function() {
		$( 'div#polllist ul' )[ 0 ].insertBefore( $( 'div.creationmockup' ).clone() , $( 'li.create' )[ 0 ] );
		$( 'div#polllist ul div.creationmockup' ).animate( { height: '40px' } );
	},
	Cancel : function() {
	
	}
};
$( document ).ready( function() {
	$( 'div#polllist li.create a' ). click( function() {
		PollList.Create();
		return false;
	} );

} );