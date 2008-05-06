var PollList = {
	Create : function() {
		var newpoll = document.createElement( 'li' );
		$( newpoll ).append( $( 'div.creationmockup' ).clone() );
		$( 'div#polllist ul' )[ 0 ].insertBefore( newpoll , $( 'ul li.create' )[ 0 ] );
		$( 'div#polllist ul div.creationmockup' ).css( 'height' , '0' ).animate( { height: '40px' } , 400 );
		$( 'div#polllist ul div.creationmockup input' )[ 0 ].focus();
		$( 'div#polllist ul div.creationmockup input' ).keydown( function( event ) {
			if ( event.keyCode == 13 ) {
				var heading = document.createElement( 'h4' );
				var headinglink = document.createElement( 'a' );
				$( headinglink ).attr( { 'href' : '' } ).append( document.createTextNode( $( 'div#polllist ul div.creationmockup input' )[ 0 ].value );
				$( heading ).append( headinglink ).css( 'margin-top' , '0' );
				$( 'div#polllist ul div.creationmockup' ).empty().append( heading );
				//$( this )[ 0 ].value;
			}		
		} );
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