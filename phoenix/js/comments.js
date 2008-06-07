var Comments = {
	Create : function( parentid ) {
		var texter;
		if ( parentid === 0 ) { // Clear new comment message
			texter = $("div.newcomment div.text textarea").get( 0 ).value;
			$("div.newcomment div.text textarea").get( 0 ).value = '';
		}
		else {
			texter = $("#comment_reply_" + parentid + " div.text textarea" ).get( 0 ).value;
		}
		if ( texter === "" ) {
			alert( "Δε μπορείς να δημοσιεύσεις κενό μήνυμα" );
			return;
		}
		var a = document.createElement( 'a' );
		a.onclick = function() {
				return false;
			};
		a.appendChild( document.createTextNode( "Απάντα" ) );
		
		var del = document.createElement( 'a' );
		del.onclick = function() {
				return false;
			};
		del.title = "Διαγραφή";
		
		// Dimiourgisa ena teras :-S
		var daddy = (parentid===0)?$("div.newcomment").clone( true ):$("#comment_reply_" + parentid );
		var temp = daddy.css( "opacity", 0 ).removeClass( "newcomment" ).find( "span.time" ).text( "πριν λίγο" ).end()
		.find( "div.text" ).empty().append( document.createTextNode( texter ) ).end()
		.find( "div.bottom" ).empty().append( a ).append( document.createTextNode( " σε αυτό το σχόλιο" ) ).end()
		.find( "div.toolbox" ).append( del ).end();
		
		var useros = temp.find( "div.who" ).get(0);
		useros.removeChild( useros.lastChild );
		useros.appendChild( document.createTextNode( " είπε:" ) );
		if ( parentid=== 0 ){
			temp.insertAfter( "div.newcomment" ).fadeTo( 400, 1 );
		}
		else {
			temp.insertAfter( "comment_" + parentid ).fadeTo( 400, 1 );
		}
		
		var type = temp.find( "#type:first" ).text();
		if ( type == 2 || type == 4 ) { // If Image or Journal
			var node = $( "dl dd.commentsnum" );
			if ( node.length !== 0 ) {
				var commentsnum = parseInt( node.text(), 10 );
				++commentsnum;
				node.text( commentsnum + " σχόλια" );
			}
			else {
				var dd = document.createElement( 'dd' );
				dd.className = "commentsnum";
				dd.appendChild( document.createTextNode( "1 σχόλιο" ) );
				$( "div dl" ).prepend( dd );
			}
		}
		
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
		var indent = ( parentid===0 )?0:parseInt( $( "comment_" + parentid ).css( "marginLeft" ), 10 )/10;
		node.attr( 'id', 'comment_' + id );
		node.find( 'div.bottom a' ).click( function() {
					Comments.Reply( id, indent );
					return false;
				} );
	},
	Reply : function( nodeid, indent ) {
		var temp = $("div.newcomment").clone( true );//.css( {marginLeft : (indent+1)*10 + 'px', opacity : 1 } ).attr( 'id', 'comment_reply_' + nodeid );
		temp.insertAfter( 'comment_' + nodeid );
		alert( "Etreksa ipotithete" );
	}
};
