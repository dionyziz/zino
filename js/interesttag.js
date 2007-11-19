var InterestTag = {
    Create : function() {
    	var div = d.createElement( 'div' );
    	
    	// Create the place in where the interests are stored
    	var ul = d.createElement( 'ul' );
    	ul.style.listStyleType = "none";
    	ul.style.textAlign = "left";
    	ul.style.marginTop = "15px";
    	
    	// Start creating the close link
    	var closeimg = d.createElement( 'img' );
		closeimg.src = "http://static.chit-chat.gr/images/colorpicker/close.png";
		closeimg.alt = "Κλείσιμο";
		closeimg.title = "Κλείσιμο";
    	
    	var close = d.createElement( 'a' );
		close.onclick = ( function( ul ) {
				return function() {
					InterestTag.Closing( ul );
					return false;
				};
			})( ul );
		close.style.cssFloat = "right";
		close.style.marginRight = "20px";
		close.style.marginTop = "5px";
		close.style.cursor = "pointer";
		//------------------------------
		

		// Fill in the interests
    	var allinterests = g( 'interests' ).firstChild.nodeValue;
    	allinterests = allinterests.split( " " );
    	for ( var i in allinterests ) {
    		if ( allinterests[i] === "" ) {
    			continue;
    		}
    		
			var li = InterestTag.createLi( allinterests[i] );
			li.appendChild( d.createElement( 'br' ) );
			ul.appendChild( li );
		}
		//-----------
		
		// Start creating the new tag box
		var input = d.createElement( 'input' );
		input.type = "text";
		input.className = "bigtext";
		input.value = "Νέο Ενδιαφέρον";
		input.style.width = "100px";
	
		var form = d.createElement( 'form' );
		form.onsubmit = ( function ( input ) {
				return function() {
					var text = input.value;
					if ( !InterestTag.is_valid( text ) ) {
						return;
					}
					Coala.Warm( 'interesttag/new', { 'text' : text } );
					var li = InterestTag.createLi( text );
					li.appendChild( d.createElement( 'br' ) );
					input.parentNode.parentNode.childNodes[2].appendChild( li ); //input->form->div->ul.append
				}
			} )( input );
		form.onkeypress = function ( e ) {
				return submitenter(form, e);
			};
		form.style.cssFloat = "left";
		form.style.marginLeft = "40px";
		
		var imageaccept = d.createElement( 'img' );
		imageaccept.src = 'http://static.chit-chat.gr/images/icons/accept.png';
		
		var editsubmit = d.createElement( 'a' );
		editsubmit.style.cursor = 'pointer';
		editsubmit.onclick = (function( myform ) {
					return function() { 
						myform.onsubmit();
						return false; 
					}
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
		div.appendChild( form );
		Modals.Create( div, 300, 270 );
    },
	createLi : function ( text ) {
		var li = d.createElement( 'li' );
		li.appendChild( d.createTextNode( text ) ); 
		
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
			
		del.appendChild( deleteimage );
		li.appendChild( d.createTextNode( ' ' ) );
		li.appendChild( del );
		
		return li; 
	},
	is_valid : function( val ) {
    	if ( val.length === 0 || val.indexOf( ',' ) != -1 || val.indexOf( ' ' ) != -1 ) {
        	alert( "Δεν μπορείς να δημιουργήσεις κενό ενδιαφέρον ή να χρησιμοποιήσεις κόμμα (,) ή κενά" );
        	return false;
        }
        return true;
    },
	Delete : function( li ) {
		Coala.Warm( 'interesttag/delete', { 'text' : li.firstChild.nodeValue } );
		li.parentNode.removeChild( li );
	},
	Closing : function ( ul ) {
		var texts = "";
		for ( var i in ul.childNodes ) {
			if ( ul.childNodes[i].nodeName.toUpperCase() != "LI" || ul.childNodes[i].firstChild.nodeName.toUpperCase() != "#TEXT" ) {
				continue;
			}
			texts += ul.childNodes[i].firstChild.nodeValue + " ";
		}
		alert( "Teliono" );
		g( 'interests' ).firstChild.nodeValue = texts;
		Modals.Destroy();
	}
};
