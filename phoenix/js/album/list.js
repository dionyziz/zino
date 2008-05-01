$( document ).ready( function() {
	$( 'li.create a.new' ).click( function() {
		var newalbum = document.createElement( 'li' );
		$( newalbum ).append(  $( 'div.createalbum' ).clone() ).css( "width" , "0" ).animate( { width: "180px" } , 400 ).find( "div.createalbum" ).removeClass( "createalbum" );
		$( 'li.create a.new' )[ 0 ].insertBefore( newalbum );		
		$( 'span.desc input' )[ 0 ].select();
		$( 'span.desc input' )[ 0 ].focus();
		//make creation link disabled
		return false;
	} );
} );