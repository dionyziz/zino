var InterestTag = {
	onedit: false,
    Create : function() {
    	var div = d.createElement( 'div' );
    	var ul = d.createElement( 'ul' );
    	ul.style.listStyleType = "none";
    	ul.style.textAlign = "left";
    	
    	var closeimg = d.createElement( 'img' );
		closeimg.src = "http://static.chit-chat.gr/images/colorpicker/close.png";
    	
    	var close = d.createElement( 'a' );
		close.onclick = function() {
					Modals.Destroy();
				};
		close.cssFloat = "right";
		close.position = "relative";
		close.marginRight = "5px";
		close.style.cursor = "pointer";
		close.alt = "Κλείσιμο";
		close.title = "Κλείσιμο";
		
		var newtagimg = d.createElement( 'img' );
		newtagimg.src = "http://static.chit-chat.gr/images/icons/page_new.gif";
		
		var newtag = d.createElement( 'a' );
		newtag.style.cursor = "pointer";
		newtag.style.position = "relative";
		newtag.style.marginLeft = "-300px";
		newtag.alt = "Νέο Ενδιαφέρον";
		newtag.title = "Νέο Ενδιαφέρον";
		newtag.onclick = ( function( ul ) {
				return function() {
					InterestTag.NewTag( ul );
					return false;
				};
			} )( ul );
			
		newtag.appendChild( newtagimg );
		
		var count = 0;
    	
    	var allinterests = g( 'interests' ).firstChild.nodeValue;
    	allinterests = allinterests.split( " " );
    	for ( var i in allinterests ) {
    		if ( allinterests[i] === "" ) {
    			continue;
    		}
    		
			var li = InterestTag.createLi( allinterests[i], count );
			li.appendChild( d.createElement( 'br' ) );
			ul.appendChild( li );
			++count;
		}
		close.appendChild( closeimg );
		div.appendChild( closeimg );
		div.appendChild( ul );
		div.appendChild( newtag );
		Modals.Create( div, 400, 270 );
    },
	createLi : function ( text, count ) {
		var li = d.createElement( 'li' );
		li.appendChild( d.createTextNode( text ) ); 
		li.onmouseover = ( function( id ) {
				return function () {
					InterestTag.showLinks( id );
				};
			} )(count);
		li.onmouseout = ( function( id ) {
				return function() {
					InterestTag.hideLinks( id );
				};
			} )(count);
		
		var editimage = d.createElement( 'img' );
		editimage.src = 'http://static.chit-chat.gr/images/icons/edit.png';
		
		var deleteimage = d.createElement( 'img' );
		deleteimage.src = "http://static.chit-chat.gr/images/icons/delete.png";
		
		var edit = d.createElement( 'a' );
		edit.id = "interedit_" + count;
		edit.style.cursor = "pointer";
		edit.style.display = "none";
		edit.alt = "Επεξεργασία";
		edit.title = "Επεξεργασία";
		edit.onclick = ( function( li ) {
				return function() {
					InterestTag.Edit( li );
				};
			} )(li);

		var del = d.createElement( 'a' );
		del.id = "interdel_" + count;
		del.style.cursor = "pointer";
		del.style.display = "none";
		del.alt = "Διαγραφή";
		del.title = "Διαγραφή";
		del.onclick = (function( li ) {
				return function() {
					InterestTag.Delete( li );
				};
			})(li);
		
		edit.appendChild( editimage );
		del.appendChild( deleteimage );
		li.appendChild( d.createTextNode( ' ' ) );
		li.appendChild( edit );
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
    showLinks : function( id ) {
		if ( !InterestTag.onedit ) {
			g( 'interedit_' + id ).style.display = 'inline';
			g( 'interdel_' + id ).style.display = 'inline';
		}
	},
	hideLinks : function( id ) {
		g( 'interedit_' + id ).style.display = 'none';
		g( 'interdel_' + id ).style.display = 'none';
	},
	Delete : function( li ) {
		alert( li.firstChild.nodeValue );
		Coala.Warm( 'interesttag/delete', { 'text' : li.firstChild.nodeValue } );
		li.parentNode.removeChild( li );
	},
	Edit : function( li ) {
		if ( InterestTag.onedit ) {
			return;
		}
		var text = li.firstChild.nodeValue;
		
		InterestTag.onedit = true;
		li.style.display = "none";

		var form = d.createElement( 'form' );
		form.onsubmit = ( function( prevtext ) {
				return function() {
					var text = input.value;
					if ( !InterestTag.is_valid( text ) ) {
						return;
					}
					Coala.Warm( 'interesttag/edit', { 'old' : prevtext, 'new' : text } );
					form.parentNode.removeChild( form );
					li.style.display = "inline";
					li.firstChild.nodeValue = text;
					InterestTag.onedit = false;
				};
			} )( text );
		form.onkeypress = function ( e ) {
				return submitenter(form, e);
			};
				
		var input = d.createElement( 'input' );
		input.type = "text";
		input.value = text;
		input.className = "bigtext";
		input.style.width = "100px";
		
		var imageaccept = document.createElement( 'img' );
		imageaccept.src = 'http://static.chit-chat.gr/images/icons/accept.png';
		
		var imagecancel = document.createElement( 'img' );
		imagecancel.src = 'http://static.chit-chat.gr/images/icons/cancel.png';
		
		var editsubmit = d.createElement( 'a' );
		editsubmit.style.cursor = 'pointer';
		editsubmit.onclick = (function( myform ) {
					return function() { 
						myform.onsubmit();
						return false; 
					}
				})( form );
		editsubmit.alt = 'Επεξεργασία';
		editsubmit.title = 'Επεξεργασία';
		editsubmit.appendChild( imageaccept );
		
		var editcancel = d.createElement( 'a' );
		editcancel.style.cursor = 'pointer';
		editcancel.onclick = ( function ( li ) {
				return function() {
					li.parentNode.removeChild( form );
					li.style.display = "inline";
					InterestTag.onedit = false;
				};
			} )( li );
		editcancel.alt = 'Ακύρωση';
		editcancel.title = 'Ακύρωση';
		editcancel.appendChild( imagecancel );
		
		form.appendChild( input );
		form.appendChild( d.createTextNode( ' ' ) );
		form.appendChild( editsubmit );
		form.appendChild( d.createTextNode( ' ' ) );
		form.appendChild( editcancel );
		form.appendChild( d.createElement( 'br' ) );
		li.parentNode.insertBefore( form, li );
	},
	NewTag : function( ul ) {
		if( InterestTag.onedit ) {
			return;
		}
		InterestTag.onedit = true;
		var newid = ul.childNodes[ ul.childNodes.length-1 ].getElementsByTagName( 'a' )[ 0 ].id;
		newid = parseInt( newid.substr( 10 ), 10 );
		++newid;
		
		var li = InterestTag.createLi( '', newid );
	
		var input = d.createElement( 'input' );
		input.type = "text";
		input.className = "bigtext";
		input.value = "Νέο Ενδιαφέρον";
		input.style.width = "100px";
		input.focus();
	
		var form = d.createElement( 'form' );
		form.onsubmit = ( function ( input, li ) {
				return function() {
					var text = input.value;
					if ( !InterestTag.is_valid( text ) ) {
						return;
					}
					Coala.Warm( 'interesttag/new', { 'text' : text } );
					li.style.display = "inline";
					li.firstChild.nodeValue = text;
					li.appendChild( d.createElement( 'br' ) );
					var form = input.parentNode;
					form.parentNode.removeChild( form );
					InterestTag.onedit = false;
				};
			} )( input, li );
		form.onkeypress = function ( e ) {
				return submitenter(form, e);
			};
		
		var imageaccept = d.createElement( 'img' );
		imageaccept.src = 'http://static.chit-chat.gr/images/icons/accept.png';
		
		var imagecancel = d.createElement( 'img' );
		imagecancel.src = 'http://static.chit-chat.gr/images/icons/cancel.png';
		
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
		
		var editcancel = d.createElement( 'a' );
		editcancel.style.cursor = 'pointer';
		editcancel.onclick = ( function ( li ) {
				return function() {
					li.parentNode.removeChild( form );
					li.parentNode.removeChild( li );
					InterestTag.onedit = false;
				};
			} )( li );
		editcancel.alt = 'Ακύρωση';
		editcancel.title = 'Ακύρωση';
		editcancel.appendChild( imagecancel );
		
		form.appendChild( input );
		form.appendChild( d.createTextNode( ' ' ) );
		form.appendChild( editsubmit );
		form.appendChild( d.createTextNode( ' ' ) );
		form.appendChild( editcancel );
		form.appendChild( d.createElement( 'br' ) );
		ul.appendChild( li );
		ul.insertBefore( form, li );
	}	
};
