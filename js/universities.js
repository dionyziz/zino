var Uni = {
	Create : function() {
		var unitext = document.getElementById( 'uniname' );
		var unilist = document.getElementById( 'unilist' );
		alert( unitext.value );
		if ( unitext.value != '' ) {
			var newuni = document.createElement( 'div' );
			var unitype = document.getElementById( 'uniaei' );
			newuni.appendChild( document.createTextNode( unitext.value ) );
			if ( unitype.checked ) {
				newuni.appendChild( document.createTextNode( " - ΑΕΙ" ) );
			}
			else {
				newuni.appendChild( document.createTextNode( " - ΤΕΙ" ) );
			}
			
			unilist.appendChild( newuni );
		}
		else {
			alert( 'Δώσε ένα έγκυρο όνομα πανεπιστημίου' );
		}	
	}
}