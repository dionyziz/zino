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
    	var ul = d.createElement( 'ul' );
    	
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
		
		var count = 0;
    	
    	var allinterests = g( 'interests' ).firstChild.nodeValue;
    	allinterests = allinterests.split( " " );
    	for ( var i in allinterests ) {
    		if ( allinterests[i] == "" ) {
    			continue;
    		}
    		var li = d.createElement( 'li' );
    		li.id = "interest_" + count;
    		li.appendChild( d.createTextNode( allinterests[i] ) );
    		li.onmouseover = ( function( id ) {
    				InterestTag.showLinks( id );
    			} )(count);
    		li.onmouseout = ( function( id ) {
    				InterestTag.hideLinks( id );
    			} )(count);
			
			var editimage = d.createElement( 'img' );
			editimage.src = 'http://static.chit-chat.gr/images/icons/edit.png';
			
			var deleteimage = d.createElement( 'img' );
			deleteimage.src = "http://static.chit-chat.gr/images/icons/delete.png";
			
			var edit = d.createElement( 'a' );
			edit.id = "interedit_" + count;
			edit.style.cursor = "pointer";
			edit.style.displa = "none";
			edit.alt = "Επεξεργασία";
			edit.title = "Επεξεργασία";
			edit.onclick = function() {
					alert( "The thing should be edited");
				};
	
			var del = d.createElement( 'a' );
			del.id = "interdel_" + count;
			del.style.cursor = "pointer";
			del.style.display = "none";
			del.alt = "Διαγραφή";
			del.title = "Διαγραφή";
			del.onclick = (function( li ) {
					InterestTag.Delete( li );
				})(li);
			
			edit.appendChild( editimage );
			del.appendChild( deleteimage );
			li.appendChild( edit );
			li.appendChild( d.createTextNode( ' ' ) );
			li.appendChild( del );
			ul.appendChild( li );
			
			++count;
		}
		close.appendChild( closeimg );
		div.appendChild( closeimg );
		div.appendChild( ul );
		Modals.Create( div, 400, 270 );
    },
    is_valid : function( text ) {
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
		Coala.Warm( 'interesttag/delete', { text : li.firstChild.nodeValue } );
		li.parentNode.removeChild( li );
	}
		
};
