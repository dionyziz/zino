$( document ).ready( function() {
	$( 'li.create a.new' ).click( function() {
		var newalbum = document.createElement( 'li' );
		$( newalbum ).append(  $( 'div.createalbum' ).clone() ).css( "width" , "0" ).animate( { width: "180px" } , 400 ).find( "div.createalbum" ).removeClass( "createalbum" );
		$( 'ul.albums' )[ 0 ].insertBefore( newalbum , $( 'li.create' )[ 0 ] );
		$( 'span.desc input' ).keydown( function( event ) {
			if ( event.keyCode == 13 ) {
				var spandesc = document.createElement( 'span' );
				var albumname = $( 'span.desc input' )[ 0 ].value;
				$( spandesc ).append( document.createTextNode( albumname ) ).addClass( "desc" );
				$( this ).parent().parent().find( "a" ).append( spandesc );
				$( this ).parent().remove();
			}
		} );
		$( 'span.desc input' )[ 0 ].select();
		$( 'span.desc input' )[ 0 ].focus();
		//make creation link disabled
		return false;
	} );
} );