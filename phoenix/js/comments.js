var Comments = {
	Create : function() {
		var texter = $("div.newcomment div.text textarea").get( 0 ).value;
		$("div.newcomment div.text textarea").get( 0 ).value = '';
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
		var temp = $("div.newcomment").clone( true ).css( "opacity", 0 ).removeClass( "newcomment" ).find( "span.time" ).text( "πριν λίγο" ).end()
		.find( "div.text" ).empty().append( document.createTextNode( texter ) ).end()
		.find( "div.bottom" ).empty().append( a ).append( document.createTextNode( " σε αυτό το σχόλιο" ) ).end()
		.find( "div.toolbox" ).append( del ).end();
		
		var useros = temp.find( "div.who" ).get(0);
		useros.removeChild( useros.lastChild );
		useros.appendChild( document.createTextNode( " είπε:" ) );
		temp.insertAfter( "div.newcomment" ).fadeTo( 400, 1 );
		
		var type = temp.find( "#type:first" ).text();
		if ( type == 2 || type == 4 ) { // If Image or Journal
			var commentsnum = parseInt( $( "dl dd.commentsnum" ).text(), 10 );
			++commentsnum;
			$( "dl dd.commentsnum" ).text( commentsnum );
		}
		
		Coala.Warm( 'comments/new', { 	text : texter, 
										parent : 0,
										compage : temp.find( "#item:first" ).text(),
										type : type,
										indent : 0,
										callback : Comments.CreateCallback
									} );
	},
	CreateCallback : function() {
		alert( "" );
	}
};
