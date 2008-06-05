var Comments = {
	Create : function() {
		var texter = $("div.newcomment div.text textarea").get( 0 ).value;
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
/*		del.style.opacity = 0;
		del.style.backgroundImage = "url( 'http://static.zino.gr/phoenix/delete.png' )";*/
		
		var temp = $("div.newcomment").clone( true ).removeClass( "newcomment" ).find( "span.time" ).text( "πριν λίγο" ).end().find( "div.text" ).empty().append( document.createTextNode( texter ) ).end().find( "div.bottom" ).empty().append( a ).append( document.createTextNode( " σε αυτό το σχόλιο" ) ).end().find( "div.toolbox" ).append( del ).end();
		//.find( "div.toolbox a" ).fadeIn( 450 ).end();
		var useros = temp.find( "div.who" ).get(0);
		useros.removeChild( useros.lastChild );
		useros.appendChild( document.createTextNode( " είπε:" ) );
		temp.insertAfter( "div.newcomment" );
	}
};
