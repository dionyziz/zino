var Suggest = {
    // INTEREST_TAG_TYPE   Please Update everytime you define a new interesttag_type constant
    list : {
        'hobbies' : {},
        'movies' : {},
        'books' : {},
        'songs' : {},
        'artists' : {},
        'games' : {},
        'shows' : {}
    },
    // INTEREST_TAG_TYPE   Please Update everytime you define a new interesttag_type constant
    requested : { 
        'hobbies' : {},
        'movies' : {},
        'books' : {},
        'songs' : {},
        'artists' : {},
        'games' : {},
        'shows' : {}
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
	suggestCallback : function( type, suggestions, callbacked ) {
		if ( suggestions.length === undefined || suggestions.length == 0 ) {
			return;
		}
		
		for( var i=0;i<suggestions.length;++i ) {
		    if ( $.inArray( suggestions[i], Suggest.list[ type ] ) === -1 ) {
		        Suggest.list[ type ].push( suggestions[i] );
		    }
		}
		
		$( 'div.' + type + ' form' ).show();
		var sel = $( 'div.' + type + ' select' ).get( 0 );
		
		var counter;
		if ( !callbacked ) {
		    sel.size = ( suggestions.length >= 5 )?5:suggestions.length;
		    counter = 0;
		}
		else {
		    sel.size = ( sel.size + suggestions.length >= 5 )?5:( sel.size + suggestions.length );
		    counter = sel.size;
		}
		
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
			opt.onmouseover = function( type, counter ) {
				return function() {
				    $( 'div.' + type + ' select' ).focus().attr( 'selectedIndex', '' + counter );
				};
			}( type, counter );
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
		if ( event.keyCode == 13 || $.trim( text ) === '' ) { // Leave keyCode==13 here. Otherwise suggestions will appear after the interest is added
			return;
		}
		var suggestions = $.grep( Suggest.list[ type ], function( item, index ) {
		                return( item.substr( 0, text.length ) == text );
		               } );
		Suggest.suggestCallback( type, suggestions, false );
		if ( suggestions.length > 40 || $.inArray( text, Suggest.requested[ type ] ) !== -1 ) {
		    return;
		}
		Coala.Cold( 'user/settings/tags/suggest', { 'text' : text,
		                                            'type' : type,
		                                            'callback' : Suggest.suggestCallback
		                                           } );
		Suggest.requested[ type ].push( text );
	}
};
