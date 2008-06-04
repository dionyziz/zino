var Comments = {
	Create : function() {
		var texter = $("div.newcomment div.text textarea").eq[0].value;
		if ( texter == "" ) {
			alert( "Δε μπορείς να δημοσιεύσεις κενό μήνυμα" );
			return;
		}
		var showcomment = $("div.newcomment").eq[0].cloneNode( true );
		var a = document.createElement( 'a' );
		a.onclick = false;
		a.appendChild( document.
		//TODO: who
		showcomment.removeClass( "newcomment" ).find( "span.time" )[0].text( "πριν λίγο" ).end().find( "div.text textarea" ).remove().text( texter ).end().find( "div.bottom" )[0].remove().append( a ).append( document.createTextNode( "Απάντα" ) ).end().insertAfter( "div.newcomment" );
	}
}
