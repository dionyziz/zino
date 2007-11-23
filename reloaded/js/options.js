var SetCat = {
	activated : -1,
	activate_category: function( catindex ) {
			if ( SetCat.activated != -1 ) {
				document.getElementById( 'cat' + SetCat.activated ).style.display = "none";
				document.getElementById( 'setimg' + SetCat.activated ).src = "http://static.chit-chat.gr/images/icons/settings-collapsed.png";
			}

			if ( SetCat.activated == catindex ) {
				document.getElementById( 'cat' + catindex ).style.display = "none";
				document.getElementById( 'setimg' + catindex ).src = "http://static.chit-chat.gr/images/icons/settings-collapsed.png";
				SetCat.activated = -1;
			}
			else {
				document.getElementById( 'cat' + catindex ).style.display = "block";	
				document.getElementById( 'setimg' + catindex ).src = "http://static.chit-chat.gr/images/icons/settings-expanded.png";
				SetCat.activated = catindex;
			}
	},
	submitchanges: function() {
		document.getElementById( 'theuseropties' ).submit();
	}
};

var ProfileOptions = {
	paused: false,
	Pause: function( input ) {
		input.style.backgroundColor = "white";
		input.style.border = "1px solid black";
		ProfileOptions.paused = input.name;
	},
	Save: function( input ) {
		ProfileOptions.paused = false;
		ProfileOptions.DestroyBox( input );
				
		Coala.Warm( 'users/options', { 'input_value' : input.value, 'input_name' : input.name } );
	},
	CreateBox: function( input ) {
		if ( !ProfileOptions.paused ) {
			input.style.border = "1px solid black";
			input.style.backgroundColor = "white";
		}
	},
	DestroyBox: function( input ) {
		if ( ProfileOptions.paused != input.name ) {
			input.style.border = "0px solid black";
			input.style.backgroundColor = "transparent";
		}
	},
	Init: function( ul ) {
		ul.style.lineHeight = '160%';
				
		elements = ul.getElementsByTagName( "dd" );
		for ( i in elements ) {
			if ( !elements[ i ].id || elements[ i ].id.substring( 0, 13 ) != "user_options_" ) {
				// alert( "wrong class name: " + elements[ i ].id );
				continue;
			}
			
			content = "";
			if ( elements[ i ].childNodes.length > 0 ) {
				content = elements[ i ].firstChild.nodeValue;
			}
			elements[ i ].innerHTML = "";
			
			elements[ i ].style.height = '100%';
			elements[ i ].style.backgroundColor = '#ececec';
		
			input 			= d.createElement( "input" );
			input.type		= "text";
			input.name 		= elements[ i ].id.substring( 13 ); // user_options_foo -> user_foo
			input.className = "user_options";
			input.value		= content;
			if ( content === "" ) {
				// input.value = "Δεν έχεις ορίσει";
			}
			input.style.backgroundColor = "transparent";
			input.style.position = "absolute";
			input.style.width = "250px";
			
			input.onfocus = ( function( input ) { 
				return function() {
					input.select(); 
					ProfileOptions.Pause( input ); 
				};
			} )( input );
			input.onblur  = ( function( input ) { 
				return function() { 
					ProfileOptions.Save( input );  
				};
			} )( input );
			input.onmouseover = ( function( input ) { 
				return function(){ 
					ProfileOptions.CreateBox( input ); 
				};
			} )( input );
			input.onmouseout = ( function( input ){ 
				return function() { 
					ProfileOptions.DestroyBox( input ); 
				};
			} )( input );
			
			input.onkeypress = ( function( input, ul ) {
				return function( event ) {
					if ( event.which == 13 ) {
						ProfileOptions.Save( input );
						input.blur();
					}
				};
			} )( input, ul );
			
			elements[ i ].appendChild( input );
		}
	}
};
