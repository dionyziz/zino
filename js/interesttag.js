var InterestTag = {
	onedit: false,
/*    Submit: function( val ) {
        
        Coala.Warm( 'interesttag/new', { 'text': val, 'callback' : InterestTag.SubmitCallback } );
    },
    SubmitCallback : function( val ) {
    	var inp = g( 'newinteresttag' );
    	inp.parentNode.insertBefore( d.createTextNode( val + " " ), inp );
        inp.value = '';
        inp.focus();
    },*/
    Create : function() {
    	var div = d.createElement( 'div' );
    	var form = d.createElement( 'form' );
    	
    	var close = document.createElement( 'a' );
		close.onclick = function() {
					Modals.Destroy();
				};
		close.className = "close";
		close.onmouseover = function() {
						document.body.style.cursor = "pointer";
					};
		close.onmouseout = function () {
						document.body.style.cursor = "default";
					};
		
		var closeimg = document.createElement( 'img' );
		closeimg.src = "http://static.chit-chat.gr/images/colorpicker/close.png";
		closeimg.alt = "Κλείσιμο";
		closeimg.title = "Κλείσιμο";
    	
    	var allinterests = g( 'interests' ).firstChild.nodeValue;
    	allinterests = allinterest.split( " " );
    	for ( var i in allinterests ) {
    		var input = d.createElement( 'input' );
    		input.type = "text";
    		input.value = allinterests[i];
    		input.className = "interest_hidden";
    		input.onkeypress = ( function( input ) {
					return function( e ) {
						if ( !e ) {
							e = window.event;
						}
						if ( e.keyCode == 13 && is_valid( input.value ) ) {
							//Coala.Warm();
							alert( "The thing should be sent" );
						}
					};
				} )( input );
			
			var editimage = d.createElement( 'img' );
			editimage.src = 'http://static.chit-chat.gr/images/icons/edit.png';
			
			var deleteimage = d.createElement( 'img' );
			deleteimage.src = "http://static.chit-chat.gr/images/icons/delete.png";
			
			var edit = d.createElement( 'a' );
			edit.style.cursor = "pointer";
			edit.alt = "Επεξεργασία";
			edit.title = "Επεξεργασία";
			edit.onclick = function() {
					alert( "The thing should be edited");
				};
	
			var del = d.createElement( 'a' );
			del.style.cursor = "pointer";
			del.alt = "Διαγραφή";
			del.title = "Διαγραφή";
			del.onclick = function() {
					alert( "The thing should be deleted" );
				};
			
			edit.appendChild( editimage );
			del.appendChild( deleteimage );
			form.appendChild( input );
			form.appendChild( d.createTextNode( ' ' ) );
			form.appendChild( edit );
			form.appendChild( del );
			form.appendChild( d.createElement( 'br' ) );
		}
		close.appendChild( closeimg );
		div.appendChild( closeimg );
		div.appendChild( form );
		Modals.Create( div, 400, 270 );
    },
    is_valid : function( text ) {
    	if ( val.length === 0 || val.indexOf( ',' ) != -1 || val.indexOf( ' ' ) != -1 ) {
        	alert( "Δεν μπορείς να δημιουργήσεις κενό ενδιαφέρον ή να χρησιμοποιήσεις κόμμα (,) ή κενά" );
        	return false;
        }
        return true;
    }
};
