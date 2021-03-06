var Suggest = {
	allowHover : true, // Allow onmouseover to select a li
    // INTEREST_TAG_TYPE   Please Update everytime you define a new interesttag_type constant
    // Holds the suggestions that we have already received from the server
    list : {
        'hobbies' : [],
        'movies' : [],
        'books' : [],
        'songs' : [],
        'artists' : [],
        'games' : [],
        'shows' : []
    },
    // INTEREST_TAG_TYPE   Please Update everytime you define a new interesttag_type constant
    // Holds all the requests we have done to the server
    requested : { 
        'hobbies' : [],
        'movies' : [],
        'books' : [],
        'songs' : [],
        'artists' : [],
        'games' : [],
        'shows' : []
    },
	// INTEREST_TAG_TYPE   Please Update everytime you define a new interesttag_type constant
	// Holds whether the mouse is over a suggestion list. Necessary for the scrolling bar to work
	over : {
		'hobbies' : false,
		'movies' : false,
		'books' : false,
		'songs' : false,
		'artists' : false,
		'games' : false,
		'shows' : false
	},
	// INTEREST_TAG_TYPE   Please Update everytime you define a new interesttag_type constant
	// Contains all the already added interests
	added : {
		'hobbies' : [],
		'movies' : [],
		'books' : [],
		'songs' : [],
		'artists' : [],
		'games' : [],
		'shows' : []
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
    inputMove : function( event, type ) {
        var ul = $( 'div.' + type + ' ul' );
        if ( ul.css( "display" ) == "none" && event.keyCode != 13 ) {
			return;
		}
        var lis = ul.find( 'li.selected' );
		var text = $( 'div.' + type + ' input' ).val();
        if ( event.keyCode == 40 ) { // down
            if ( lis.length == 0 ) {
                ul.find( 'li:first' ).addClass( 'selected' ).get( 0 ).scrollIntoView( false );
                return;
            }
            lis.removeClass( 'selected' ).next().addClass( 'selected' ).get( 0 ).scrollIntoView( false );
			Suggest.allowHover = false;
			setTimeout( function() { Suggest.allowHover = true; }, 15 );
			$("div.add." + type +" input").val( $("div.add." + type + " ul li.selected").text() );
        }
        else if ( event.keyCode == 38 ) { // up
            if ( lis.length === 0 ) {
                ul.find( 'li:last' ).addClass( 'selected' ).get( 0 ).scrollIntoView( false );
                return;
            }
            ul.find( 'li.selected' ).removeClass( 'selected' ).prev().addClass( 'selected' ).get( 0 ).scrollIntoView( false );
			Suggest.allowHover = false;
			setTimeout( function() { Suggest.allowHover = true; }, 15 );
			$("div.add." + type +" input").val( $("div.add." + type + " ul li.selected").text() );
        }
		else if ( event.keyCode == 33 ) { // PageUp
			var piso = ul.find( 'li.selected' ).removeClass( 'selected' ).prevAll();
			Suggest.allowHover = false;
			if ( piso.length < 5 ) {
				ul.find( 'li:first' ).addClass( 'selected' ).get( 0 ).scrollIntoView( false );
			}
			else {
				$( piso[ 4 ] ).addClass( 'selected' ).get( 0 ).scrollIntoView( false );
			}
			setTimeout( function() { Suggest.allowHover = true; }, 15 );
			$("div.add." + type +" input").val( $("div.add." + type + " ul li.selected").text() );
		}
		else if ( event.keyCode == 34 ) { // PageDown
			var mprosta = ul.find( 'li.selected' ).removeClass( 'selected' ).nextAll();
			Suggest.allowHover = false;
			if ( mprosta.length < 5 ) {
				ul.find( 'li:last' ).addClass( 'selected' ).get( 0 ).scrollIntoView( false );
			}
			else {
				$( mprosta[ 4 ] ).addClass( 'selected' ).get( 0 ).scrollIntoView( false );
			}
			setTimeout( function() { Suggest.allowHover = true; }, 15 );
			$("div.add." + type +" input").val( $("div.add." + type + " ul li.selected").text() );
		}
        else if ( event.keyCode == 27 ) { // escape
            ul.find( 'li' ).remove();
        }
        else if ( event.keyCode == 13 ) { // enter
			Suggest.over[ type ] = false;
            /*
			if ( lis.text().length != 0 ) {
				$( 'div.' + type + ' input' ).attr( 'value', lis.text() );
			}
			$("div.add." + type +" input").val( $("div.add." + type + " ul li.selected").text() );
            */
			Settings.AddInterest( type, Suggest.type2int( type ) );
			// ul.find( 'li' ).remove();
        }
		else if ( $.trim( text ) !== '' ) {
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
		else {
			$( 'div.' + type + ' ul li' ).remove();
		}
    },
	suggestCallback : function( type, suggestions, callbacked ) {
		/*if ( suggestions.length === undefined || suggestions.length == 0 ) {
			return;
		}*/
		
		if ( !callbacked ) {
			$( 'div.' + type + ' ul li' ).remove();
		}

		// Marks duplicate entries
		var sugLength = suggestions.length;
		for( var j=0;j<suggestions.length;++j ) {
		    if ( $.inArray( suggestions[ j ], Suggest.list[ type ] ) === -1 && $.inArray( suggestions[ j ], Suggest.added[ type ] ) === -1 ) {
		        Suggest.list[ type ].push( suggestions[ j ] );
		    }
		    else if ( callbacked ) { // If callbacked is set to true, then the current suggestion always exists in the options. It was added the first time when callbacked was false
		        suggestions[ j ] = '';
		        --sugLength;
		    }
		}
		
		$( 'div.' + type + ' ul' ).show();
		
		var text = $( 'div.' + type + ' input' ).val();
		for( var i in suggestions ) {
		    if ( suggestions[i] !== '' ) {
				var li = document.createElement( 'li' );
				li.onmousedown = function( i ) {
					return function() {
						Suggest.over[ type ] = false;
						$( 'div.' + type + ' input' ).attr( 'value', suggestions[ i ] );
						Settings.AddInterest( type, Suggest.type2int( type ) );
						$( 'div.' + type + ' ul li' ).remove();
					};
				}( i );
				li.onmousemove = function() {
					if ( !Suggest.allowHover ) {
						return;
					}
					$( 'div.' + type + ' ul li.selected' ).removeClass( 'selected' );
					this.className = "selected";
				};

				var b = document.createElement( 'b' );
				b.appendChild( document.createTextNode( suggestions[ i ].substr( 0, text.length ) ) );
				li.appendChild( b );
				li.appendChild( document.createTextNode( suggestions[ i ].substr( text.length ) ) );
			
				$( 'div.' + type + ' ul' ).append( li );
			}
		}
		
	},
	hideBlur : function( type ) {
		if ( Suggest.over[ type ] ) {
			setTimeout( "$( 'div." + type + " input' ).focus()", 5 );
			return false;
		}
		$( 'div.' + type + ' ul' ).hide();
	},
    OnLoad : function() {
        $( 'form#interestsinfo div.option div.setting div.add ul li' ).remove();
        var inttagtypes = [ 'hobbies', 'movies', 'books', 'songs', 'artists', 'games', 'shows' ];
        for( var i=0;i<inttagtypes.length;++i ) {
            var spans = $( 'form#interestsinfo div.option div.setting ul.' + inttagtypes[ i ] + ' li div.aplbubble span.aplbubblemiddle' );
            if ( spans.length === 0 ) {
                continue;
            }
            $.each( spans, function() {
                Suggest.added[ inttagtypes[ i ] ].push( $( this ).text() );
            } );
        }
    }
};
