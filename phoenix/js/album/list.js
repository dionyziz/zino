var AlbumList = {
	Create : function() {
		var newalbum = document.createElement( 'li' );
		$( newalbum ).append( $( 'div.createalbum' ).clone() ).css( "display" , "none" );
		$( 'ul.albums' )[ 0 ].insertBefore( newalbum , $( 'li.create' )[ 0 ] );
		$( 'span.desc input' ).keydown( function( event ) {
			if ( event.keyCode == 13 ) {
				AlbumList.renameFunc( this, newalbum );
			}
		} ).blur( function() { AlbumList.renameFunc( this, newalbum ); } );
		setTimeout( function() {
			$( newalbum ).show( 400 , function() {
				$( 'span.desc input' )[ 0 ].focus();
			} );
		} , 50 );
		var link = document.createElement( "a" );
		$( link ).attr( { href: "" } ).addClass( "new" ).append( document.createTextNode( "«Ακύρωση" ) ).click( function() {
			$( newalbum ).hide( 400 , function() {
				$( newalbum ).remove();
			} );
			AlbumList.Cancel( newalbum );
			return false;
		} );
		$( 'li.create' ).empty().append( link );
	},
	Cancel : function( albumnode ) {
		var link = document.createElement( "a" );
		var createimg = document.createElement( "img" );
		$( createimg ).attr( {
			src: ExcaliburSettings.imagesurl + "add3.png",
			alt: "Δημιουργία album",
			title: "Δημιουργία album"
		} );
		$( link ).attr( { href: "" } ).addClass( "new" ).append( createimg ).append( document.createTextNode( "Δημιουργία album" ) ).click( function() {
			AlbumList.Create();
			return false;
		} );
		$( 'li.create' ).empty().append( link );
	},
	renameFunc : function( elem, newalbum ) {
		var albumname = $( 'span.desc input' )[ 0 ].value;
		if ( albumname !== '' ) {
			var spandesc = document.createElement( 'span' );
			$( spandesc ).append( document.createTextNode( albumname ) ).addClass( "desc" );
			$( elem ).parent().parent().find( "a" ).append( spandesc );
			$( elem ).parent().remove();
			//$( 'li.create' ).html( $( 'div.creating' ).html() );
			document.body.style.cursor = 'wait';
			Coala.Warm( 'album/create' , { albumname : albumname , albumnode : newalbum } );
		}
	},
    OnLoad : function() {
        $( 'ul.albums li.create a.new' ).click( function() {
            AlbumList.Create();
            return false;
        } );
        Coala.Cold( 'admanager/showad', { f: function ( html ) {
            $( 'div.ads' )[ 0 ].innerHTML = html;
        } } );
    }
};
