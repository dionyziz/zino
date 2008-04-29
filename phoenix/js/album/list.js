$( document ).ready( function() {
	$( 'div.create a.new' ).click( function() {
		var newalbum = document.createElement( 'li' );
		$( newalbum ).append(  $( 'div.createalbum' ).clone() );
		$( newalbum.getElementsByTagName( 'div' )[ 0 ] ).removeClass( 'createalbum' );
		$( 'ul.albums' ).append( newalbum );
		
		
		return false;
	} );
} );