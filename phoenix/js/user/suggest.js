var Suggest = {
	timeoutid : { // During the execution of the code, this array holds the setTimeOut id's for each suggestion type
		// INTEREST_TAG_TYPE   Please Update everytime you define a new interesttag_type constant
		'hobbies' : false,
		'movies' : false,
		'books' : false,
		'songs' : false,
		'artists' : false,
		'games' : false,
		'shows' : false
	},
	type2int : function( type ) {
		switch( type ) {
			// INTEREST_TAG_TYPE   Please Update everytime you define a new interesttag_type constant
			case 'hobbies':
				return 1;
			case 'movies':
				return 2;
			case 'books':
				return 3;
			case 'songs':
				return 4;
			case 'artists':
				return 5;
			case 'games':
				return 6;
			case 'shows':
				return 7;
			default:
				return -1;
		}
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
		// If Up or Down is pressed TODO: prevent input's onkeyup from firing
		if ( ( selindex === 0 && event.keyCode == 38 ) || ( selindex == sel.get(0).options.length-1 && event.keyCode == 40 ) ) {
			$( 'div.' + type + ' input' ).focus();
		}
		else if ( event.keyCode == 13 ) {
			var text = sel.get( 0 ).options[ selindex ].value;
			$( 'div.' + type + ' input' ).val( text ).focus();
			var typeid = Suggest.type2int( type );
			if ( typeid === -1 ) {
				return;
			}
			Settings.AddInterest( type, typeid );
			sel.find( 'option' ).remove();
			$( 'div.' + type + ' form' ).hide();
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
		else {
			$( 'div.' + type + ' form' ).hide();
			sel.find( 'option' ).remove();
		}
	},
	suggestCallback : function( type, suggestions ) {
		if ( suggestions.length == undefined ) {
			return;
		}
		$( 'div.' + type + ' form' ).show();
		var sel = $( 'div.' + type + ' select' ).get( 0 );
		sel.size = ( suggestions.length >= 5 )?5:suggestions.length;
		var counter = 0;
		for( var i in suggestions ) {
			var opt = document.createElement( 'option' );
			opt.value = suggestions[i];
			opt.onclick = function() {
				var typeid = Suggest.type2int( type );
				if ( typeid == -1 ) {
					return;
				}
				$( 'div.' + type + ' input' ).focus().get( 0 ).value = this.value;
				Settings.AddInterest( type, typeid );
				$( 'div.' + type + ' form' ).hide().find( 'option' ).remove();
			};
			opt.onmouseover = function() {
				//this.style.backgroundColor = '#E4EAF9';
				$( 'div.' + type + ' select' ).attr( 'selectedIndex', counter );
			};
			/*
			opt.onmouseout = function() {
				this.style.backgroundColor = 'white';
			};*/
			opt.appendChild( document.createTextNode( suggestions[i] ) );
			sel.appendChild( opt );
			++counter;
		}
	},
	fire : function( event, type ) {
		if ( event.keyCode == 38 || event.keyCode == 40 ) {
			return;
		}
		var text = $( 'div.' + type + ' input' ).val();
		if ( Suggest.timeoutid[ type ] !== false ) {
			window.clearTimeout( Suggest.timeoutid[ type ] );
		}
		if ( event.keyCode == 13 || $.trim( text ) == '' ) { // Leave keyCode==13 here. Otherwise suggestions will appear after the interest is added
			return;
		}
		Suggest.timeoutid[ type ] = window.setTimeout( "Coala.Cold( 'user/settings/tags/suggest', { 'text' : '" + text + "', 'type' : '" + type + "', 'callback' : Suggest.suggestCallback } );", 1500 );
	}
};
