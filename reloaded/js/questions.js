var Questions = {
	onedit : false,
	showLinks : function( id ) {
		if ( !Questions.onedit ) {
			g( 'qeditlink_' + id ).style.display = 'inline';
			g( 'qdeletelink_' + id ).style.display = 'inline';
		}
	}
	,hideLinks : function( id ) {
		g( 'qeditlink_' + id ).style.display = 'none';
		g( 'qdeletelink_' + id ).style.display = 'none';
	}
	,deleteq : function( id ) {
		if ( confirm( 'Θέλεις σίγουρα να διαγράψεις τη συγκεκριμένη ερώτηση;' ) ) { 
			var element = g( 'question_' + id );
			while( element.firstChild ) {
				element.removeChild( element.firstChild );
			}
			element.appendChild( d.createElement( 'Διαγραφή...' ) );
			Coala.Warm( 'question/delete' , {'questionid':id} ); 
		}
	}
	,edit : function( id ) {
		if( Questions.onedit ) {
			return;
		}
		
		var question = g( 'qraw_' + id ).innerHTML;
		
		var qform = d.createElement( 'form' );
		qform.id = 'editqform';
		qform.onsubmit = (function( id ) {
				return function() {
					if( qinput.value !== '' ) {
						Coala.Warm( 'question/editm', { 'eid' : id, 'question' : qinput.value } );
						g( 'qraw_' + id ).firstChild.nodeValue = qinput.value;
					}
					else {
						alert( "Δεν μπορείς να δημιουργήσεις κενή ερώτηση" );
					}
					Questions.cancelEdit(id);
					return false;
				};
			}) (id);
		qform.onkeypress = function (e) {
				return submitenter( qform, e );
		};
		var qinput = d.createElement( 'input' );
		qinput.size = '100';
		qinput.type = 'text';
		qinput.value = question;
		qinput.className = 'bigtext';
		
		var imageaccept = document.createElement( 'img' );
		imageaccept.src = 'http://static.chit-chat.gr/images/icons/accept.png';
		
		var imagecancel = document.createElement( 'img' );
		imagecancel.src = 'http://static.chit-chat.gr/images/icons/cancel.png';
		
		var qsubmit = d.createElement( 'a' );
		qsubmit.style.cursor = 'pointer';
		qsubmit.onclick = (function( myform ) {
					return function() { 
						myform.onsubmit();
						return false; 
					};
		})( qform );
		qsubmit.alt = 'Επεξεργασία';
		qsubmit.title = 'Επεξεργασία';
		qsubmit.appendChild( imageaccept );
		
		var qcancel = d.createElement( 'a' );
		qcancel.style.cursor = 'pointer';
		qcancel.onclick = (function(id){ 
			return function(){ 
				Questions.cancelEdit(id);
			}; 
		})(id);
		qcancel.alt = 'Ακύρωση';
		qcancel.title = 'Ακύρωση';
		qcancel.appendChild( imagecancel );
		
		qform.appendChild( qinput );
		qform.appendChild( d.createTextNode( ' ' ) );
		qform.appendChild( qsubmit );
		qform.appendChild( d.createTextNode( ' ' ) );
		qform.appendChild( qcancel );
		qform.appendChild( d.createElement( 'br' ) );
		
		g( 'question_' + id ).style.display = 'none';
		g( 'question_' + id ).parentNode.insertBefore( qform, g( 'question_' + id ).nextSibling );
		
		Questions.onedit = true;
	}
	,cancelEdit : function( id ) {
		g( 'question_' + id ).parentNode.removeChild( g( 'editqform' ) );
		g( 'question_' + id ).style.display = '';
		
		Questions.onedit = false;
	}
	,create : function() {
		g( 'newq' ).style.display = 'none';
		g( 'newqform' ).style.display = 'block';
	}
	,cancelCreate : function() {
		g( 'newq' ).style.display = '';
		g( 'newqform' ).style.display = 'none';
	}
};
