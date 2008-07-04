var Frontpage = {
	Closenewuser : function ( node ) {
		$( 'div.frontpage div.ybubble' ).animate( { height : '0'} , 800 , function() {
			$( this ).remove();
		} );
	},
	/*
	Showunis : function( node ) {
		var divlist = node.getElementsByTagName( 'div' );
		var contenthtml = "<span style=\"padding-left:5px;\">ÐáíåðéóôÞìéï:</span><select><option value=\"0\" selected=\"selected\">-</option><option value=\"2\">Öéëïëïãßá</option><option value=\"6\">Çëåêôñïëüãùí Ìç÷áíéêþí &amp; Ìç÷áíéêþí Õðïëïãéóôþí</option><option value=\"9\">ÉáôñéêÞ</option><option value=\"23\">ÇëåêôñïíéêÞ</option><option value=\"25\">Öéëïóïößá</option><option value=\"43\">Èåïëïãßá</option><option value=\"35\">ÐëçñïöïñéêÞ</option><option value=\"67\">Ìç÷áíéêüò Õðïëïãéóôþí</option><option value=\"98\">ÏäïíôïúáôñéêÞ</option></select>";
		var newdiv = document.createElement( 'div' );
		newdiv.innerHTML = contenthtml;
		node.insertBefore( newdiv, divlist[ 0 ].nextSibling );
	},
	*/
	DeleteShout : function( shoutid ) {
		if ( confirm( 'Θέλεις σίγουρα να διαγράψεις το μήνυμα;' ) ) {
			$( '#s_' + shoutid ).animate( { height : "0" , opacity : "0" } , 300 , function() {
				$( this ).remove();
			} );
			Coala.Warm( 'shoutbox/delete' , { shoutid : shoutid } );
		}
	}
};
$( document ).ready( function() {
	if ( $( 'div.frontpage div.inshoutbox' )[ 0 ] ) {
		$( 'div.frontpage div.inshoutbox div.shoutbox div.comments div.newcomment div.bottom input' ).click( function() {
			var list = $( 'div.frontpage div.inshoutbox div.shoutbox div.comments' );
			var text = $( list ).find( 'div.newcomment div.text textarea' )[ 0 ].value;
			if ( $.trim( text ) == '' ) {
				
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
	}
} );
