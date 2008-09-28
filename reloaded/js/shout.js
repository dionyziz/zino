var Shoutbox = {
	formid: 'newshout',
	New : function() {
		if( g( Shoutbox.formid ).style.display == "none" ) {
			g( Shoutbox.formid ).style.display = "block";
			g( "newshoutarea" ).focus(); // focus on the new shout textarea
		}
		else {
			g( Shoutbox.formid ).style.display = "none";
		}
	},
	Edit : function( id ) {
		if ( g( 'shoutedit_' + id ).style.display == 'block' ) {
			return;
		}
		
		element = g( 'shoutedit_' + id );
		var text = element.firstChild.nodeValue;
		
		while( element.firstChild ) {
			element.removeChild( element.firstChild );
		}
		
		var editform = d.createElement( 'form' );
		editform.onsubmit = (function( id ){ 
					return function(){
						Animations.SetAttribute( element, 'opacity', 0.5 );
						Coala.Warm( 'shout/edit', {'id':editid.value ,  'shouttext':edittext.value, 'callback':Shoutbox.cancelEdit});
						Animations.SetAttribute( element , 'opacity' , 1 );
						return false;
					};
				} ) (id); // use cancelEdit() in order to change shoutedit

		var imageaccept = document.createElement( 'img' );
		imageaccept.src = 'http://static.zino.gr/images/icons/accept.png';

		var imagecancel = document.createElement( 'img' );
		imagecancel.src = 'http://static.zino.gr/images/icons/cancel.png';
		
		var editid = d.createElement( 'input' );
		editid.type = 'hidden';
		editid.name = 'id';
		editid.value = id;
		
		var edittext = d.createElement( 'textarea' );
		edittext.name = 'shout';
		edittext.id = 'ShoutAreA';
		edittext.value = text;
		edittext.onkeypress = Shoutbox.checkSize;
					
		edittext.style.width = '210px';
		edittext.style.height = '40px';
		
		var editsubmit = d.createElement( 'a' );
		editsubmit.href = '';
		editsubmit.onclick = (function ( myform ) {
				return function () {
					myform.onsubmit();
					return false;
				};
			})( editform );
		editsubmit.alt = 'Επεξεργασία';
		editsubmit.title = 'Επεξεργασία';
		editsubmit.appendChild( imageaccept );
		
		var editcancel = d.createElement( 'a' );
		editcancel.href = '';
		editcancel.alt = 'Ακύρωση';
		editcancel.title = 'Ακύρωση';
		editcancel.onclick = (function( id, answer ){
					return function(){ 
						Shoutbox.cancelEdit(id, answer);
						return false;
					}; 
				})(id, text);
		editcancel.appendChild( imagecancel );

		
		editform.appendChild( editid );
		editform.appendChild( edittext );
		editform.appendChild( d.createElement( 'br' ) );
		editform.appendChild( editsubmit );
		editform.appendChild( d.createTextNode( ' ' ) );
		editform.appendChild( editcancel );
		
		element.appendChild( editform );
		
		g( 'shouttext_' + id ).style.display = 'none';
		g( 'shoutedit_' + id ).style.display = 'block';
	}
	,cancelEdit : function( id, textn, textformat ) {
		element = g( 'shoutedit_' + id );		
		element.style.display = 'none';
		while( element.firstChild ) {
			element.removeChild( element.firstChild );
		}
		element.appendChild( d.createTextNode( textn ) );

		var stext = g( 'shouttext_' + id );
        if( textformat !== undefined ) {
            stext.innerHTML = textformat;
            

    		var editsh = d.createElement( 'a' );
    		editsh.setAttribute("style","cursor: pointer;");
    		editsh.onclick=(function( id ) {
    						return function() { 
    							Shoutbox.Edit(id);
    							return false;
    						};
    					})(id);
    		editsh.href="";
    		editsh.title="Επεξεργασία Μικρού Νέου";

    		var editimg = d.createElement( 'img' );
    		editimg.width=16;
    		editimg.height=16;
    		editimg.src="http://static.zino.gr/images/icons/icon_wand.gif";
    		editimg.alt="Επεξεργασία Μικρού Νέου";
    		
    		var delsh = d.createElement( 'a' );
    		delsh.setAttribute("style","cursor: pointer;");
    		delsh.onclick=(function( id ) {
    						return function() {
    							Shoutbox.deleteShout( id );
    							return false;
    						};
    					})(id);
    		delsh.href="";
    		delsh.title="Διαγραφή Μικρού Νέου";

    		var delimg = d.createElement( 'img' );
    		delimg.src="http://static.zino.gr/images/icons/delete_sm.png";
    		delimg.alt="Διαγραφή Μικρού Νέου";

    		editsh.appendChild( editimg );
    		delsh.appendChild( delimg );
    		stext.appendChild( editsh );
    		stext.appendChild( delsh );
    	}	
    	stext.style.display = 'block';
	}
	,deleteShout : function( id ) {
		Modals.Confirm( 'Θέλεις σίγουρα να διαγράψεις το συγκεκριμένο νέο;', function () {
            Coala.Warm( 'shout/delete', {'id':id} );
            element = g( 'shout_' + id );
            while( element.firstChild ) {
                element.removeChild( element.firstChild );
            }
		} );
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
		// Internet Explorer counts newline as 2 characters (\r\n) while Firefox as 1 (\n)
		var text = targ.value;
		var express = new RegExp("\r", "g");
		var table = text.match(express);
		var len;
		if ( table==null ) {
			len = text.length;
		}
		else {
			len = text.length-table.length;
		}
		if ( len >= 300 ) { // The text exceeded the 300 character limit
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
				default:
					return false;
			}
		}
		else {
			return true;
		}
	}
};

