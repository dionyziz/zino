var Comments = {
	Create : function() {
		var texter = $("div.newcomment div.text textarea").get( 0 ).value;
		if ( texter === "" ) {
			alert( "Δε μπορείς να δημοσιεύσεις κενό μήνυμα" );
			return;
		}
		var showcomment = $("div.newcomment").clone( true );
		var a = document.createElement( 'a' );
		a.onclick = false;
		$( showcomment ).removeClass( "newcomment" ).find( "span.time" ).text( "πριν λίγο" ).end().find( "div.text textarea" ).remove().end().find( "div.text" ).append( document.createTextNode( texter ) ).end().find( "div.bottom" ).remove().append( a ).append( document.createTextNode( "Απάντα" ) ).end().find( "div.who:last" ).replaceWith( document.createTextNode( " είπε:" ) );
		$( showcomment ).insertAfter( "div.newcomment" );
	}
};
