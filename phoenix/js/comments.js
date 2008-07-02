var Comments = {
	numchildren : new Object(),
	Create : function( parentid ) {
		var texter;
		if ( parentid === 0 ) { // Clear new comment message
			texter = $( "div.newcomment div.text textarea" ).get( 0 ).value;
			$( "div.newcomment div.text textarea" ).get( 0 ).value = '';
		}
		else {
			texter = $( "#comment_reply_" + parentid + " div.text textarea" ).get( 0 ).value;
		}
		texter = $.trim( texter );
		if ( texter === "" ) {
			alert( "Δε μπορείς να δημοσιεύσεις κενό μήνυμα" );
			return;
		}
		var a = document.createElement( 'a' );
		a.onclick = function() {
				return false;
			};
		a.appendChild( document.createTextNode( "Απάντησε" ) );
		
		var del = document.createElement( 'a' );
		del.onclick = function() {
				return false;
			};
		del.title = "Διαγραφή";
		
		var indent = ( parentid===0 )?-1:parseInt( $( "#comment_" + parentid ).css( "marginLeft" ), 10 )/20;
		
		// Dimiourgisa ena teras :-S
		var daddy = (parentid===0)?$( "div.newcomment:first" ).clone( true ):$( "#comment_reply_" + parentid );
		var temp = daddy.css( "opacity", 0 ).removeClass( "newcomment" ).find( "span.time" ).text( "πριν λίγο" ).end()
		.find( "div.text" ).empty().append( document.createTextNode( texter ) ).end()
		.find( "div.bottom" ).empty().append( a ).append( document.createTextNode( " σε αυτό το σχόλιο" ) ).end()
		.find( "div.toolbox" ).append( del ).end();
				
		var useros = temp.find( "div.who" ).get( 0 );
		useros.removeChild( useros.lastChild );
		useros.appendChild( document.createTextNode( " είπε:" ) );
		if ( parentid=== 0 ){
			temp.insertAfter( "div.newcomment:first" ).fadeTo( 400, 1 );
		}
		else {
			temp.insertAfter( "#comment_" + parentid ).fadeTo( 400, 1 );
			var deletes = $( "#comment_" + parentid + " div.toolbox a" ); // Hide parent's delete button
			if ( deletes.length > 0 && deletes.css( 'opacity' ) == 1 ) {
				deletes.fadeOut( 400 );
			}
		}
		
		var type = temp.find( "#type:first" ).text();
		Comments.FixCommentsNumber( type, true );
		Coala.Warm( 'comments/new', { 	text : texter, 
										parent : parentid,
										compage : temp.find( "#item:first" ).text(),
										type : type,
										node : temp, 
										callback : Comments.NewCommentCallback
									}, function() {
											alert( "Υπήρχε ένα πρόβλημα με την δημιουργία σχολίου, παρακαλώ προσπάθησε ξανά" );
											window.location.reload();
										}
											 );
	},
	NewCommentCallback : function( node, id, parentid ) {
		if ( parentid !== 0 ) {
			++Comments.numchildren[ parentid ];
		}
		Comments.numchildren[ id ] = 0;
	
		var indent = ( parentid===0 )?-1:parseInt( $( "#comment_" + parentid ).css( "marginLeft" ), 10 )/20;
		node.attr( 'id', 'comment_' + id );
		node.find( 'div.bottom a' ).toggle( function() {
					Comments.Reply( id, indent+1 );
					return false;
				}, function() {
					$( '#comment_reply_' + id ).hide( 300, function() { $(this).remove(); } );
					return false;
				}
			);
		node.find( 'div.text' ).get( 0 ).ondblclick = function() {
					Comments.Edit( id );
					return false;
				};
		node.find( 'div.toolbox a' ).get( 0 ).onclick = function() {
					Comments.Delete( id, parentid );
					return false;
				};
	},
	Reply : function( nodeid, indent ) {
		var temp = $( "div.newcomment:first" ).clone( true ).css( { marginLeft : (indent+1)*20 + 'px', opacity : 0 } ).attr( 'id', 'comment_reply_' + nodeid );
		temp.find( "div.toolbox span.time" ).css( { marginRight : (indent+1)*20 + 'px' } );
		temp.find( "div.bottom form input:first" ).get( 0 ).onclick = function() { // Only with DOM JS the onclick event is overwritten
					Comments.Create( nodeid );
					return false;
				} ;
		temp.insertAfter( '#comment_' + nodeid ).fadeTo( 300, 1 );
		temp.find( "div.text textarea" ).get( 0 ).focus();
	},
	Edit : function( nodeid ) {
		var node = $( "#comment_" + nodeid );
		var text = node.find( "div.text" ).text();
		
		var textarea = document.createElement( 'textarea' );
		textarea.value = text;
		
		var div = document.createElement( 'div' );
		div.className = "bottom";
		
		var form = document.createElement( 'form' );
		form.onsubmit = function() {
					return false;
				};
				
		var input = document.createElement( 'input' );
		input.type = "submit";
		input.value = "Επεξεργασία";
		input.onclick = function() {
					var daddy = $( this ).parents().eq(2); // get big div
					var texter = daddy.find( "div.text textarea" ).get( 0 ).value;
					texter = $.trim( texter );
					if ( texter === '' ) {
						alert( "Δε μπορείς να δημοσιεύσεις κενό μήνυμα" );
						return;
					}
					daddy.find( "div.text" ).empty().append( document.createTextNode( texter ) ).end()
					.find( "div.bottom:last" ).remove().end()
					.find( "div.bottom" ).css( 'display', 'block' );
					Coala.Warm( 'comments/edit', {	id : daddy.attr( 'id' ).substring( 8 ),
													text : texter
												}, function() {
													alert( "Υπήρχε ένα πρόβλημα με την επεξεργασία σχολίου, παρακαλώ προσπάθησε ξανά" );
													window.location.reload();
											} );
				};
			
		var input2 = document.createElement( 'input' );
		input2.type = "reset";
		input2.value = "Ακύρωση";
		input2.onclick = function() {
					var daddy = $( this ).parents().eq(2); // get big div
					daddy.find( "div.text" ).empty().append( document.createTextNode( text ) ).end()
					.find( "div.bottom:last" ).remove().end()
					.find( "div.bottom" ).css( 'display', 'block' );
				};
		
		form.appendChild( input );
		form.appendChild( document.createTextNode( ' ' ) );
		form.appendChild( input2 );
		div.appendChild( form );
		
		node.find( "div.text" ).empty().append( textarea ).end()
		.find( "div.bottom" ).css( 'display', 'none' ).end()
		.append( div );
		node.find( "div.text textarea" ).get( 0 ).focus();
	}, 
	Delete : function( nodeid, parentid ) {
		var node = $( "#comment_" + nodeid );
		node.fadeOut( 450, function() { $( this ).remove(); } );
		Comments.FixCommentsNumber( node.find( "#type:first" ).text(), false );
		Coala.Warm( 'comments/delete', { commentid : nodeid, 
										callback : Comments.DeleteCommentCallback }, function() {
									alert( 'Υπήρχε κάποιο πρόβλημα με την διαγραφή σχολίου, παρακαλώ προσπαθήστε ξανά' );
									window.location.reload();
							} );
	},
	DeleteCommentCallback : function( nodeid, parentid, show ) {
		Comments.numchildren[ nodeid ] = -1;
		if ( parentid !== 0 ) {
			--Comments.numchildren[ parentid ];
		}
		if ( Comments.numchildren[ parentid] != 0 || !show ) {
			return;
		}
		
		var a = document.createElement( 'a' );
		a.onclick = function() { 
				Comments.Delete( parentid );
				return false;
			};
		a.title = "Διαγραφή";
		
		$( '#comment_' + parentid + " div.toolbox" ).append( a );
	},
	FixCommentsNumber : function( type, inc ) {
		if ( type != 2 && type != 4 ) { // If !Image or Journal
			return;
		}
		var node = $( "dl dd.commentsnum" );
		if ( node.length !== 0 ) {
			var commentsnum = parseInt( node.text(), 10 );
			commentsnum = (inc)?commentsnum+1:commentsnum-1;
			node.text( commentsnum + " σχόλια" );
		}
		else {
			var dd = document.createElement( 'dd' );
			dd.className = "commentsnum";
			dd.appendChild( document.createTextNode( "1 σχόλιο" ) );
			$( "div dl" ).prepend( dd );
		}
	}
};
$( document ).ready( function() {
		$( "div.comments div.comment" ).not( ".newcomment" ).each( function( i ) {
			var id = $( this ).attr( 'id' ).substring( 8 );
			var indent = parseInt( $( this ).css( 'marginLeft' ), 10 )/20;
			$( this ).find( "div.bottom a" ).toggle( function() {
					Comments.Reply( id, indent );
					return false;
				}, function() {
					$( '#comment_reply_' + id ).hide( 300, function() { $(this).remove(); } );
					return false;
				}
			);
		} );
	} );
