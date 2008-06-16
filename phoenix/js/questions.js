var Questions = {
	Create : function() {
		$( 'form' ).show( 450 );
		$( '#newq' ).hide( 450 );
		return false;
	},
	cancelCreate : function() {
		$( 'form' ).hide( 450 );
		$( '#newq' ).show( 450 );
	}
}
