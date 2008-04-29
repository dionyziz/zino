$( document ).ready( function() {
	$( 'div.create a.new' ).click( function() {
		var newalbum = document.createElement( 'li' );
		$( newalbum ).html( $( 'div.creationmakeup div.createalbum' ).html() );
		
		return false;
	} );
} );