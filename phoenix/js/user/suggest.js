var Suggest = {
	timeoutid : { 
		'hobbies' : false,
		'movies' : false,
		'books' : false,
		'songs' : false,
		'artists' : false,
		'games' : false,
		'shows' : false
	},
	selectMove : function( event, type ) {
		if ( $( 'div.' + type + ' form' ).css( "display" ) == "none" ) {
			return;
		}
		var sel = $( 'div.' + type + ' select' );
		var selindex = sel.attr( "selectedIndex" );
		if ( selindex === undefined ) {
			selindex = 0;
		}
		if ( ( selindex === 0 && event.keyCode == 38 ) || ( selindex == sel.get(0).options.length-1 && event.keyCode == 40 ) ) {
			$( 'div.' + type + ' input' ).focus();
		}
		else if ( event.keyCode == 13 ) {
			var text = sel.get( 0 ).options[ selindex ].value;
			$( 'div.' + type + ' input' ).val( text ).focus();
			sel.find( 'option' ).remove();
			$( 'div.' + type + ' form' ).hide();
		}
		else {
			sel.find( 'option' ).remove();
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
	},
	suggestCallback : function( type, suggestions ) {
		if ( suggestions.length == 0 ) {
			return;
		}
		$( 'div.' + type + ' form' ).show();
		var sel = $( 'div.' + type + ' select' );
		//sel.find( 'option' ).remove();
		sel = sel.get(0);
		for( var i in suggestions ) {
			var opt = document.createElement( 'option' );
			opt.value = suggestions[i];
			opt.appendChild( document.createTextNode( suggestions[i] ) );
			sel.appendChild( opt );
		}
	},
	fire : function( type ) {
		if ( Suggest.timeoutid[ type ] !== false ) {
			window.clearTimeout( Suggest.timeoutid[ type ] );
		}
		var text = $( 'div.' + type + ' input' ).val();
		Suggest.timeoutid[ type ] = window.setTimeout( "Coala.Cold( 'user/settings/tags/suggest', { 'text' : '" + text + "', 'type' : '" + type + "', 'callback' : Suggest.suggestCallback } );", 1500 );
	}
}
