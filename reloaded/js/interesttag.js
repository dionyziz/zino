var InterestTag = {
    Create : function() {
    	var div = d.createElement( 'div' );
    	
    	// Create the place in where the interests are stored
    	var ul = d.createElement( 'ul' );
    	ul.className = "allinterests";
    	
    	// Start creating the close link
    	var closeimg = d.createElement( 'img' );
		closeimg.src = "http://static.chit-chat.gr/images/colorpicker/close.png";
		closeimg.alt = "Κλείσιμο";
		closeimg.title = "Κλείσιμο";
    	
    	var close = d.createElement( 'a' );
		close.onclick = ( function( ul ) {
				return function() {
					var texts = "";
					for ( var i=0;i<ul.childNodes.length;++i ) {
						if ( ul.childNodes[i].nodeName.toUpperCase() != "LI" ) {
							continue;
						}
						texts += ul.childNodes[i].firstChild.nodeValue  + " ";
					}
					g( 'interests' ).firstChild.nodeValue = texts;
					Modals.Destroy();
					return false;
				};
			})( ul );
		close.className = "close";
		//------------------------------
		
		var anchor = d.createElement( 'a' );
		anchor.id = "hereiam";
		
		// Fill in the interests
    	var allinterlinks = g( 'interests' ).firstChild;
    	var allinterests;
    	for ( var i in allinterlinks ) {
    		if ( allinterlinks.nodeName.toUpperCase() != "A" ) {
    			continue;
    		}
    		allinterests.push( allinterlinks[i].firstChild.nodeValue );
    	}
		ul.style.height = (allinterests.length<=15)?(allinterests.length*16)+"px":"150px";
    	for ( var i in allinterests ) {
    		if ( allinterests[i] === "" ) {
    			continue;
    		}
    		
			var li = InterestTag.createLi( allinterests[i] );
			li.style.backgroundColor = (i%2) ? "rgb( 221, 231, 255 )" : "rgb( 232, 237, 255 )";
			li.appendChild( d.createElement( 'br' ) );
			ul.appendChild( li );
		}
		ul.childNodes[ ul.childNodes.length -1 ].style.borderBottomWidth = "1px";
		ul.appendChild( anchor );
		//-----------
		
		
		
		// Start creating the new tag box
		var bold = d.createElement( 'b' );
		bold.appendChild( d.createTextNode( "Νέο Ενδιαφέρον:" ) );
		bold.className = "inter";
		
		var input = d.createElement( 'input' );
		input.type = "text";
		input.className = "bigtext";
		input.value = "Νέο Ενδιαφέρον";
		input.style.width = "100px";
		input.onfocus = function() {
					input.value="";
				};
	
		var form = d.createElement( 'form' );
		form.onsubmit = ( function ( input ) {
				return function() {
					var text = input.value;
					if ( !InterestTag.is_valid( text ) ) {
						return;
					}
					var bigpar = input.parentNode.parentNode.childNodes[2]; //input->form->div->ul.append
					var childlen = bigpar.childNodes.length;
					for( var i=0;i<childlen-1;++i ) { // the last child is the anchor
						if ( bigpar.childNodes[i].firstChild.nodeValue == text ) {
							alert( "Υπάρχει ήδη ένα τέτοιο ενδιαφέρον" );
							return;
						}
					}
					Coala.Warm( 'interesttag/new', { 'text' : text } );
					var li = InterestTag.createLi( text );
					li.appendChild( d.createElement( 'br' ) );
					li.style.borderBottomWidth = "1px";
					li.style.backgroundColor = (childlen%2) ? "rgb( 232, 237, 255 )" : "rgb( 221, 231, 255 )";
					var heig = bigpar.style.height;
					heig = parseInt( heig.substr( 0, heig.length-2 ), 10 ); // remove the px ending
					if ( heig <= 160 ) {
						bigpar.style.height = (heig+20)+"px";
					}
					bigpar.childNodes[ childlen-2 ].style.borderBottomWidth = "0px";
					bigpar.insertBefore( li, bigpar.childNodes[ childlen-1 ] );
					g( "hereiam" ).focus();
					input.value="";
					input.focus();
				};
			} )( input );
		form.onkeypress = function ( e ) {
				return submitenter(form, e);
			};
		form.className = "inter";
		
		var imageaccept = d.createElement( 'img' );
		imageaccept.src = 'http://static.chit-chat.gr/images/icons/accept.png';
		
		var editsubmit = d.createElement( 'a' );
		editsubmit.style.cursor = 'pointer';
		editsubmit.onclick = (function( myform ) {
					return function() { 
						myform.onsubmit();
						return false; 
					};
				})( form );
		editsubmit.alt = 'Δημιουργία';
		editsubmit.title = 'Δημιουργία';
		editsubmit.appendChild( imageaccept );
		//----------------------------
		
		close.appendChild( closeimg );
		form.appendChild( input );
		form.appendChild( d.createTextNode( ' ' ) );
		form.appendChild( editsubmit );
		div.appendChild( close );
		div.appendChild( d.createElement( 'br' ) );
		div.appendChild( ul );
		div.appendChild( bold );
		div.appendChild( d.createElement( 'br' ) );
		div.appendChild( form );
		Modals.Create( div, 300, 270 );
    },
	createLi : function ( text ) {
		var li = d.createElement( 'li' );
		li.appendChild( d.createTextNode( text ) );
		li.style.padding = "1px 0px 1px 4px";
		li.style.borderStyle = "solid";
		li.style.borderWidth = "1px 1px 0px 1px";
		li.style.clear = "both";
		
		var deleteimage = d.createElement( 'img' );
		deleteimage.src = "http://static.chit-chat.gr/images/icons/delete.png";

		var del = d.createElement( 'a' );
		del.style.cursor = "pointer";
		del.alt = "Διαγραφή";
		del.title = "Διαγραφή";
		del.onclick = (function( li ) {
				return function() {
					InterestTag.Delete( li );
				};
			})(li);
		del.style.cssFloat = "right";
			
		del.appendChild( deleteimage );
		li.appendChild( d.createTextNode( ' ' ) );
		li.appendChild( del );
		
		return li; 
	},
	is_valid : function( val ) {
    	if ( val.length === 0 || val.length > 20 || val.indexOf( ',' ) != -1 || val.indexOf( ' ' ) != -1 ) {
        	alert( "Δεν μπορείς να δημιουργήσεις κενό ενδιαφέρον ή να χρησιμοποιήσεις κόμμα (,) ή κενά ή να έχει πάνω απο 20 χαρακτήρες" );
        	return false;
        }
        return true;
    },
	Delete : function( li ) {
		var text = li.firstChild.nodeValue
		Coala.Warm( 'interesttag/delete', { 'text' : text } );
		var dad = li.parentNode;
		var childlen = dad.childNodes.length;
		if ( childlen > 1 && dad.childNodes[ childlen - 2 ].firstChild.nodeValue == text ) {
			dad.childNodes[ childlen-3 ].style.borderBottomWidth = "1px";
		}
		dad.removeChild( li );
		var heig = dad.style.height;
		heig = parseInt( heig.substr( 0, heig.length-2 ), 10 ); // remove the px ending
		if ( heig >= 15 && childlen <=10 ) {
			dad.style.height= (heig-17) + "px";
		}
	}
};
