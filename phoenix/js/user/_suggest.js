var Suggest = {
    // INTEREST_TAG_TYPE   Please Update everytime you define a new interesttag_type constant
    // Holds the suggestions that we have already received from the server
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
    // Holds all the requests we have done to the server
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
		// If Up or Down is pressed
		if ( ( selindex === 0 && event.keyCode == 38 ) || ( selindex == sel.get(0).options.length-1 && event.keyCode == 40 ) ) {
			$( 'div.' + type + ' input' ).focus();
		}
		else if ( event.keyCode == 27 ) { // Escape
		    $( 'div.' + type ).find( 'form' ).hide().find( 'select' ).attr( 'size', 0 ).find( 'option' ).remove().end()
		    .find( 'input' ).focus();
		    return;
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
	// Displays the suggestions
	suggestCallback : function( type, suggestions, callbacked ) {
		if ( suggestions.length === undefined || suggestions.length == 0 ) {
			return;
		}
		
		// Marks duplicate entries
		var sugLength = suggestions.length;
		for( var i=0;i<suggestions.length;++i ) {
		    if ( $.inArray( suggestions[i], Suggest.list[ type ] ) === -1 ) {
		        Suggest.list[ type ].push( suggestions[i] );
		    }
		    else if ( callbacked ) { // If callbacked is set to true, then the current suggestion always exists in the options. It was added the first time when callbacked was false
		        suggestions[i] = '';
		        --sugLength;
		    }
		}
		
		$( 'div.' + type + ' form' ).show();
		var sel = $( 'div.' + type + ' ul' ).get( 0 );
		
		// Change the size of the selection if necessary and determine appropriate starting selection index for the suggestions
		var counter;
		if ( !callbacked || sel.size === undefined  ) {
		    sel.size = ( sugLength >= 5 )?5:sugLength;
		    counter = 0;
		}
		else {
		    sel.size = ( sel.size + sugLength >= 5 )?5:( sel.size + sugLength );
		    counter = sel.childNodes.length;
		}
		
		// Display at last the suggestions
		var text = $( 'div.' + type + ' input' ).val();
		for( var i in suggestions ) {
		    if ( suggestions[i] !== '' ) {
			    var opt = document.createElement( 'li' );
			    opt.value = suggestions[i];
			    opt.onclick = function() {
				    var typeid = Suggest.type2int( type );
				    if ( typeid == -1 ) {
					    return;
				    }
				    $( 'div.' + type + ' input' ).focus().get( 0 ).value = this.value;
				    Settings.AddInterest( type, typeid );
				    $( 'div.' + type + ' form' ).hide().find( 'ul' ).attr( 'size', 0 ).find( 'li' ).remove();
			    };
			    opt.onmouseover = function( type, counter ) {
				    return function() {
				        $( 'div.' + type + ' ul' ).focus().attr( 'selectedIndex', '' + counter );
				    };
			    }( type, counter );
			    
			    var divani = document.createElement( 'div' );
			    divani.style.fontWeight = 'bold';
			    divani.style.display = 'inline';
			    
			    divani.appendChild( document.createTextNode( text ) );
			    opt.appendChild( divani );
			    opt.appendChild( document.createTextNode( suggestions[i].substr( text.length ) ) );
			    sel.appendChild( opt );
			    ++counter;
			}
		}
	},
	// Process each button the user presses
	fire : function( event, type ) {
		if ( event.keyCode == 38 || event.keyCode == 40 ) { // Up/Down key button
			return;
		}
		if ( event.keyCode == 27 ) { //Escape
		    $( 'div.' + type ).find( 'form' ).hide().find( 'select' ).attr( 'size', 0 ).find( 'option' ).remove().end()
            .find( 'input' ).focus();
		    return;
		}
		var text = $( 'div.' + type + ' input' ).val();
		if ( event.keyCode == 13 || $.trim( text ) === '' ) { // Leave keyCode==13 here. Otherwise suggestions will appear after the interest is added
			return;
		}
		// Get some cached suggestions
		var suggestions = $.grep( Suggest.list[ type ], function( item, index ) {
		                return( item.toUpperCase().substr( 0, text.length ) == text.toUpperCase() );
		               } );
		Suggest.suggestCallback( type, suggestions, false );
		// Do not request any new suggestions if our list is full or we already have all the suggestions for the current text
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
$( function() {
    $( 'div.add ul li' ).remove();
  } );
