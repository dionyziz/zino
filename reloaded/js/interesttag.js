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
					return InterestTag.Close( ul );
				};
			})( ul );
		close.className = "close";
		//------------------------------
		
		var anchor = d.createElement( 'a' );
		anchor.id = "hereiam";
		
		// Fill in the interests
    	var allinterlinks = g( 'interests' ).getElementsByTagName( 'a' );
    	var allinterests = new Array();
    	for ( var i=0;i<allinterlinks.length-1;++i ) {
    		allinterests.push( allinterlinks.item(i).firstChild.nodeValue );
    	}
    	for ( var i in allinterests ) {
    		if ( allinterests[i] === "" ) {
    			continue;
    		}
    		
			var li = InterestTag.createLi( allinterests[i] );
			li.style.backgroundColor = (i%2) ? "rgb( 201, 201, 201 )" : "rgb( 150, 150, 150 )";
			li.appendChild( d.createElement( 'br' ) );
			ul.appendChild( li );
		}
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
						input.value="";
						return;
					}
					var bigpar = input.parentNode.parentNode.childNodes[2]; //input->form->div->ul.append
					var childlen = bigpar.childNodes.length;
					for( var i=0;i<childlen-1;++i ) { // the last child is the anchor
						if ( bigpar.childNodes[i].childNodes[2].nodeValue == text ) {
							alert( "Υπάρχει ήδη ένα τέτοιο ενδιαφέρον" );
							return;
						}
					}
					Coala.Warm( 'interesttag/new', { 'text' : text } );
					var li = InterestTag.createLi( text );
					li.appendChild( d.createElement( 'br' ) );
					li.style.backgroundColor = (childlen%2) ? "rgb( 150, 150, 150 )" : "rgb( 201, 201, 201 )";
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
		
		var form2 = d.createElement( 'form' );
		
		var input2 = d.createElement( 'input' );
		input2.type = "button";
		input2.value = "     Αποθήκευση     ";
		input2.onclick = ( function( ul ) {
				return function() {
					return InterestTag.Close( ul );
				};
			})( ul );
		
		//----------------------------
		
		
		close.appendChild( closeimg );
		form.appendChild( input );
		form.appendChild( d.createTextNode( ' ' ) );
		form.appendChild( editsubmit );
		form2.appendChild( input2 );
		div.appendChild( close );
		div.appendChild( d.createElement( 'br' ) );
		div.appendChild( ul );
		div.appendChild( bold );
		div.appendChild( d.createElement( 'br' ) );
		div.appendChild( form );
		div.appendChild( d.createElement( 'br' ) );
		div.appendChild( d.createElement( 'br' ) );
		div.appendChild( d.createElement( 'br' ) );
		div.appendChild( form2 );
		Modals.Create( div, 300, 330 );
    },
	createLi : function ( text ) {
		var li = d.createElement( 'li' );
		li.className = "interesttag";
		
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
		li.appendChild( del );
		li.appendChild( d.createTextNode( ' ' ) );
		li.appendChild( d.createTextNode( text ) );
		
		return li; 
	},
	is_valid : function( val ) {
    	if ( val.length === 0 ) {
    		alert( "Δεν μπορείς να δημιουργήσεις κενό ενδιαφέρον" );
    		return false;
    	}
    	if ( val.length > 25 ) {
    		alert( "Δεν επιτρέπεται να έχεις ενδιαφέρον με μήκος μεγαλύτερο των 25 χαρακτήρων" );
    		return false;
    	}
    	if ( val.indexOf( ',' ) != -1 ) {
    		alert( "Δεν επιτρέπεται να χρησιμοποιήσεις κόμματα (,)" );
    		return false;
    	}
    	if ( val.indexOf( '\t' ) != -1 ) {
    		alert( "Δεν επιτρέπεται να χρησιμοποιήσεις τον χαρακτήρα tab" );
        	return false;
        }
        return true;
    },
	Delete : function( li ) {
		var text = li.childNodes[2].nodeValue;
		Coala.Warm( 'interesttag/delete', { 'text' : text } );
		var dad = li.parentNode;
		var childlen = dad.childNodes.length;
		dad.removeChild( li );
	},
	Close : function( ul ) {
		var interests = g( 'interests' );
		interests.innerHTML = "";
		for ( var i=0;i<ul.childNodes.length-1;++i ) {
			var text = ul.childNodes[i].childNodes[2].nodeValue;
			var a = d.createElement( 'a' );
			a.style.cursor = "pointer";
			a.href = "?p=tag&text=" + text;
			a.appendChild( d.createTextNode( text ) );
			interests.appendChild( a );
			if ( i != ul.childNodes.length-2 ) {
				interests.appendChild( d.createTextNode( ", " ) );
			}
		}
		var img = d.createElement( 'img' );
		img.src = "http://static.chit-chat.gr/images/icons/page_new.gif";
		
		var a = d.createElement( 'a' );
		a.onclick = function() {
				InterestTag.Create();
				return false;
			};
			
		a.appendChild( img );
		interests.appendChild( a );
		Modals.Destroy();
		return false;
	}
};
