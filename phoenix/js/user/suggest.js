var Suggest = {
	selectMove : function( event, type ) {
		if ( $( 'div.' + type + ' form' ).css( "display" ) == "none" ) {
			return;
		}
		var sel = $( 'div.' + type + ' select' );
		if ( ( sel.attr( "selectedIndex" ) === undefined && event.keyCode == 38 ) || ( sel.attr( "selectedIndex" ) == sel.get(0).options.length-1 && event.keyCode == 40 ) ) {
			$( 'div.' + type + ' input' ).focus();
		}
	},
	inputMove : function( event, type ) {
		if ( $( 'div.' + type + ' form' ).css( "display" ) == "none" ) {
			return;
		}
		var sel = $( 'div.' + type + ' select' );
		if ( event.keyCode == 40 ) {
			sel.attr( 'selectedIndex', 0 );
			sel.focus();
		}
		else if ( event.keyCode == 38 ) {
			sel.attr( 'selectedIndex', sel.get(0).options.length-1 );
			sel.focus();
		}
	}
}
