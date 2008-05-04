var AlbumList = {
	Create : function() {
		var newalbum = document.createElement( 'li' );
		$( newalbum ).append( $( 'div.createalbum' ).clone() ).css( "display" , "none" );
		$( 'ul.albums' )[ 0 ].insertBefore( newalbum , $( 'li.create' )[ 0 ] );
		$( 'span.desc input' ).keydown( function( event ) {
			if ( event.keyCode == 13 ) {
				var albumname = $( 'span.desc input' )[ 0 ].value;
				if ( albumname !== '' ) {
					var spandesc = document.createElement( 'span' );
					$( spandesc ).append( document.createTextNode( albumname ) ).addClass( "desc" );
					$( this ).parent().parent().find( "a" ).append( spandesc );
					$( this ).parent().remove();
					//$( 'li.create' ).html( $( 'div.creating' ).html() );
					document.body.style.cursor = 'wait';
					Coala.Warm( 'album/create' , { albumname : albumname , albumnode : newalbum } );
				}
			}
		} );
		setTimeout( function() {
			$( newalbum ).show( 400 , function() {
				$( 'span.desc input' )[ 0 ].focus();
			} );
		} , 100 );
		//$( 'span.desc input' )[ 0 ].select();
		var link = document.createElement( "a" );
		$( link ).attr( { href: "" } ).addClass( "new" ).append( document.createTextNode( "«Ακύρωση" ) ).click( function() {
			$( newalbum ).hide( 400 , function() {
				$( newalbum ).remove();
			} );
			AlbumList.Cancel( newalbum , true );
			return false;
		} );
		$( 'li.create' ).empty().append( link );
	},
	Cancel : function( albumnode , vanquish ) {
		if ( vanquish ) {
			$( albumnode ).animate( { width: "0" } , 100 , function() {
				$( albumnode ).remove();
			} );
		}
		var link = document.createElement( "a" );
		var createimg = document.createElement( "img" );
		$( createimg ).attr( {
			src: "http://static.zino.gr/phoenix/add3.png",
			alt: "Δημιουργία album",
			title: "Δημιουργία album"
		} );
		$( link ).attr( { href: "" } ).addClass( "new" ).append( createimg ).append( document.createTextNode( "Δημιουργία" ) ).click( function() {
			AlbumList.Create();
			return false;
		} );
		$( 'li.create' ).empty().append( link );
	}
	,

};
$( document ).ready( function() {
	$( 'li.create a.new' ).click( function() {
		AlbumList.Create();
		return false;
	} );
} );