var Frontpage = {
	Closenewuser : function ( node ) {
		$( 'div.frontpage div.ybubble' ).animate( { height : '0'} , 800 , function() {
			$( this ).remove();
		} );
	},
	/*
	Showunis : function( node ) {
		var divlist = node.getElementsByTagName( 'div' );
		var contenthtml = "<span style=\"padding-left:5px;\">?aia?eoo?iei:</span><select><option value=\"0\" selected=\"selected\">-</option><option value=\"2\">Oeeieia?a</option><option value=\"6\">Ceaeonieuaui Ic?aiee?i &amp; Ic?aiee?i O?ieiaeoo?i</option><option value=\"9\">Eaonee?</option><option value=\"23\">Ceaeoniiee?</option><option value=\"25\">Oeeioio?a</option><option value=\"43\">Eaieia?a</option><option value=\"35\">?ecnioinee?</option><option value=\"67\">Ic?aieeuo O?ieiaeoo?i</option><option value=\"98\">Iaiioiuaonee?</option></select>";
		var newdiv = document.createElement( 'div' );
		newdiv.innerHTML = contenthtml;
		node.insertBefore( newdiv, divlist[ 0 ].nextSibling );
	},
	*/
	DeleteShout : function( shoutid ) {
		if ( confirm( 'Θέλεις σίγουρα να διαγράψεις το μήνυμα;' ) ) {
			$( 'div#s_' + shoutid ).animate( { height : "0" , opacity : "0" } , 300 , function() {
				$( this ).remove();
			} );
			Coala.Warm( 'shoutbox/delete' , { shoutid : shoutid } );
		}
	}
};
$( document ).ready( function() {
	if ( $( 'div.frontpage div.inuser div.shoutbox' )[ 0 ] ) {
		$( 'div.frontpage div.inuser div.shoutbox div.comments div.newcomment div.bottom input' ).click( function() {
			var list = $( 'div.frontpage div.inuser div.shoutbox div.comments' );
			var text = $( list ).find( 'div.newcomment div.text textarea' )[ 0 ].value;
			if ( $.trim( text ) === '' ) {
				
				alert( 'Δε μπορείς να δημοσιεύσεις κενό μήνυμα' );
				$( list ).find( 'div.newcomment div.text textarea' )[ 0 ].value = '';
				$( list ).find( 'div.newcomment div.text textarea' )[ 0 ].focus();
			}
			else {
				var newshout = $( list ).find( 'div.empty' )[ 0 ].cloneNode( true );
				$( newshout ).removeClass( 'empty' ).insertAfter( $( list ).find( 'div.newcomment' )[ 0 ] ).show().css( "opacity" , "0" ).animate( { opacity : "1" } , 400 ).find( 'div.text' ).append( document.createTextNode( text ) );
				Coala.Warm( 'shoutbox/new' , { text : text , node : newshout } );
				$( list ).find( 'div.newcomment div.text textarea' )[ 0 ].value = '';
			}
		} );
		if ( $( 'div.frontpage div.ybubble' )[ 0 ] ) {
			$( '#selectplace select' ).change( function() {
				var place = $( '#selectplace select' )[ 0 ].value;
				$( 'div.frontpage div.ybubble div.body div.saving' ).removeClass( 'invisible' );
				Coala.Warm( 'frontpage/welcomeoptions' , { place : place } );
			} );
			$( '#selecteducation select' ).change( function() {
				var edu = $( '#selecteducation select' )[ 0 ].value;
				$( 'div.frontpage div.ybubble div.body div.saving' ).removeClass( 'invisible' );
				Coala.Warm( 'frontpage/welcomeoptions' , { education : edu } );
			} );
			$( '#selectuni select' ).change( function() {
				var uni = $( '#selectuni select' )[ 0 ].value;
				$( 'div.frontpage div.ybubble div.body div.saving' ).removeClass( 'invisible' );
				Coala.Warm( 'frontpage/welcomeoptions' , { university : uni } );
			} );
		}
		if ( $( 'div.frontpage div.notifications div.list' )[ 0 ] ) {
			var notiflist = $( 'div.frontpage div.notifications div.list' )[ 0 ];
			var notiflistheight = $( notiflist )[ 0 ].offsetHeight;
			
			$( 'div.frontpage div.notifications div.list div.event' ).mouseover( function() {
				$( this ).css( "border" , "1px dotted #666" ).css( "padding" , "4px" );
			} )
			.mouseout( function() {
				$( this ).css( "border" , "0" ).css( "padding" , "5px" );
			} );
			
			$( 'div.frontpage div.notifications div.expand a' ).click( function() {
				if ( $( notiflist ).hasClass( 'invisible' ) ) {
					$( 'div.frontpage div.notifications div.expand a' )
					.css( "background-image" , 'url( "' + ExcaliburSettings.imagesurl + 'arrow_up.png" )' )
					.attr( {
						title : 'Απόκρυψη'
					} );
					$( notiflist ).removeClass( 'invisible' ).animate( { height : notiflistheight } , 400 );
				}
				else {
					$( 'div.frontpage div.notifications div.expand a' )
					.css( "background-image" , 'url( "' + ExcaliburSettings.imagesurl + 'arrow_down.png" )' )
					.attr( {
						title : 'Εμφάνιση'
					} );
					$( notiflist ).animate( { height : "0" } , 400 , function() {
						$( notiflist ).addClass( 'invisible' );
					} );
				}
				return false;
			} );
		}
	}
} );
