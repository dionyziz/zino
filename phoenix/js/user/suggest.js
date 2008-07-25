var Suggest = {
    // INTEREST_TAG_TYPE   Please Update everytime you define a new interesttag_type constant
    list : {
        'hobbies' : new Array(0),
        'movies' : new Array(0),
        'books' : new Array(0),
        'songs' : new Array(0),
        'artists' : new Array(0),
        'games' : new Array(0),
        'shows' : new Array(0)
    },
    // INTEREST_TAG_TYPE   Please Update everytime you define a new interesttag_type constant
    requested : { 
        'hobbies' : new Array(0),
        'movies' : new Array(0),
        'books' : new Array(0),
        'songs' : new Array(0),
        'artists' : new Array(0),
        'games' : new Array(0),
        'shows' : new Array(0)
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
			sel.attr( 'size', 0 ).find( 'option' ).remove();
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
			sel.attr( 'size', 0 ).find( 'option' ).remove();
		}
	},
	suggestCallback : function( type, suggestions, callbacked ) {
		if ( suggestions.length === undefined || suggestions.length == 0 ) {
			return;
		}
		
		var sugLength = suggestions.length;
		for( var i=0;i<suggestions.length;++i ) {
		    if ( $.inArray( suggestions[i], Suggest.list[ type ] ) === -1 ) {
		        Suggest.list[ type ].push( suggestions[i] );
		    }
		    else if ( callbacked ) {
		        suggestions[i] = '';
		        --sugLength;
		    }
		}
		
		$( 'div.' + type + ' form' ).show();
		var sel = $( 'div.' + type + ' select' ).get( 0 );
		
		var counter;
		if ( !callbacked || sel.size === undefined  ) {
		    sel.size = ( sugLength >= 5 )?5:sugLength;
		    counter = 0;
		}
		else {
		    sel.size = ( sel.size + sugLength >= 5 )?5:( sel.size + sugLength );
		    counter = sel.size;
		}
		
		for( var i in suggestions ) {
		    if ( suggestions[i] !== '' ) {
			    var opt = document.createElement( 'option' );
			    opt.value = suggestions[i];
			    opt.onclick = function() {
				    var typeid = Suggest.type2int( type );
				    if ( typeid == -1 ) {
					    return;
				    }
				    $( 'div.' + type + ' input' ).focus().get( 0 ).value = this.value;
				    Settings.AddInterest( type, typeid );
				    $( 'div.' + type + ' form' ).hide().find( 'select' ).attr( 'size', 0 ).find( 'option' ).remove();
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
		                return( item.toUpperCase().substr( 0, text.length ) == text.toUpperCase() );
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
