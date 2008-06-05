var Comments = {
	Create : function() {
		var texter = $("div.newcomment div.text textarea").get( 0 ).value;
		if ( texter === "" ) {
			alert( "Δε μπορείς να δημοσιεύσεις κενό μήνυμα" );
			return;
		}
		var a = document.createElement( 'a' );
		a.onclick = false;
		a.appendChild( document.createTextNode( "Απάντα" ) );
		var temp = $("div.newcomment").clone( true ).removeClass( "newcomment" ).find( "span.time" ).text( "πριν λίγο" ).end().find( "div.text" ).empty().append( document.createTextNode( texter ) ).end().find( "div.bottom" ).empty().end().find( "div.bottom" ).append( a ).append( document.createTextNode( " σε αυτό το σχόλιο" ) ).end();
		var useros = temp.find( "div.who" ).get(0);
		useros.removeChild( useros.lastChild );
		useros.appendChild( document.createTextNode( " είπε:" ) );
		temp.insertAfter( "div.newcomment" );
		/*end().find( "div.text" ).append( document.createTextNode( texter ) ).end().find( "div.bottom" ).empty().end().find( "div.bottom" ).append( a ).append( document.createTextNode( "Απάντα" ) ).end().find( "div.who" ).text( " είπε:" ).end().insertAfter( "div.newcomment" );*/
	}
};
