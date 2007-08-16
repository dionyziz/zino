var Relations = {
	onedit : false // This variable is true when a relation is being edited and false otherwise
	,deleteM : function( id ) {
		Modals.Confirm( 
            'Θέλεις σίγουρα να διαγράψεις τη συγκεκριμένη σχέση;',
            function () { 
                Relations.deleteR( id );
            }
        );
	}
	,deleteR : function( id ) {
		var element = document.getElementById( 'relation_' + id );
		element.style.display = 'none';
        Coala.Warm( 'relations/delete' , { 'relid': id } );
    }
	,edit : function( id ) {
		if( Relations.onedit ) {
			alert( "Μια άλλη σχέση βρίσκεται ήδη ύπο επεξεργασία" );
			return;
		}
		
		var relation = g('rraw_' + id ).innerHTML;
		
		var rform = d.createElement( 'form' );
		rform.id = 'editrform';
		rform.onsubmit = (function( id ) {
				return function() {
					if( rinput.value != '' && rinput.value.length <= 20 ) {
						Coala.Warm( 'relations/edit', { 'rid' : id, 'rtype' : rinput.value } );
						g( 'rraw_' + id ).firstChild.nodeValue = rinput.value;
					}
					else if ( rinput.value == '' ) {
						alert( "Δεν μπορείς να δημιουργήσεις κενή σχέση" );
					}
					else {
						alert( "Δεν μπορεί μια σχέση να έχει όνομα μεγαλύτερο των 20 χαρακτήρων" );
					}
					Relations.cancelEdit( id );
						return false;
				}
			}) (id);
		rform.onkeypress = function( e ) {
				return submitenter(rform, e);
			};
		
		var rinput = d.createElement( 'input' );
		rinput.type = 'text';
		rinput.value = relation;
		rinput.setAttribute( "class", "bigtext" );
					
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
				})( rform );
		editsubmit.alt = 'Επεξεργασία';
		editsubmit.title = 'Επεξεργασία';
		editsubmit.appendChild( imageaccept );
		
		var editcancel = d.createElement( 'a' );
		editcancel.style.cursor = 'pointer';
		editcancel.onclick = (function( id ) {
				return function() {
					Relations.cancelEdit( id );
					return false;
				}
			})( id );
		editcancel.alt = 'Ακύρωση';
		editcancel.title = 'Ακύρωση';
		editcancel.appendChild( imagecancel );
		
		rform.appendChild( rinput );
		rform.appendChild( d.createTextNode( ' ' ) );
		rform.appendChild( editsubmit );
		rform.appendChild( d.createTextNode( ' ' ) );
		rform.appendChild( editcancel );
		rform.appendChild( d.createElement( 'br' ) );
		
		g( 'relation_' + id ).style.display = 'none';
		g( 'relation_' + id ).parentNode.insertBefore( rform, g( 'relation_' + id ).nextSibling );
		Relations.onedit = true;
	}
	,cancelEdit : function( id ) {
		g( 'relation_' + id ).parentNode.removeChild( g( 'editrform' ) ); // Remove the form edit() created
		g( 'relation_' + id ).style.display = '';
		
		Relations.onedit = false;
	}
	,showLinks : function( id ) {
		if ( !Relations.onedit ) {
			g( 'reditlink_' + id ).style.display = 'inline';
			g( 'rdeletelink_' + id ).style.display = 'inline';
		}
	}
	,hideLinks : function( id ) {
		g( 'reditlink_' + id ).style.display = 'none';
		g( 'rdeletelink_' + id ).style.display = 'none';
	}
	,create : function() {
		g( 'newr' ).style.display = 'none';
		g( 'newrform' ).style.display = 'block';
		Relations.onedit = true;
	}
	,cancelCreate : function() {
		g( 'newr' ).style.display = '';
		g( 'newrform' ).style.display = 'none';
		g( 'newrform' ).type.value = "Γράψε εδώ την νέα Σχέση!";
		Relations.onedit = false;
	}
	,checkSize : function( e ) {
		var key;
		var targ;
		if ( !e ) {
			e = window.event;
		}
		if ( e.target ) {
			targ = e.target;
		}
		else if ( e.srcElement ) {
			targ = e.srcElement;
		}
		if ( e.keyCode ) {
			key = e.keyCode;
		}
		else if ( e.which ) {
			key = e.which;
		}
		else {
			alert( "Δεν ήταν δυνατό να προσδιοριστεί το κουμπί που πατήθηκε!Τι φυλλομετρητή χρησιμοποιείς;" );
		}
		var len = targ.value.length;
		if ( len >= 20 ) { // The text exceeded the 300 character limit
			switch ( key ) {
				case 8: // Backspace
				case 46: // Delete
				case 37: // Left
				case 39: // Right
				case 36: // Home
				case 35: //End
				case 38: // Up
				case 40: // Down
					return true;
				case 13: // Enter
					g( 'newrform' ).submit();
					return true;
				default:
					return false;
			}
		}
		else {
			return true;
		}
	}
};
		
