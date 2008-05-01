$( document ).ready( function() {
	$( 'div.create a.new' ).click( function() {
		var newalbum = document.createElement( 'li' );
		$( newalbum ).append(  $( 'div.createalbum' ).clone() );
		$( newalbum.getElementsByTagName( 'div' )[ 0 ] ).removeClass( 'createalbum' );
		$( 'ul.albums' ).append( newalbum );
		$( newalbum ).css( "width" , "0" ).animate( { width: "180px" } , 700 );		
		$( 'span.desc input' ).select().focus();
		//make creation link disabled
		return false;
	} );
} );