var Uni = {
	Create : function() {
		var unitext = document.getElementById( 'uniname' );
		var unilist = document.getElementById( 'unilist' );
		alert( unitext.value );
		if ( unitext.value != '' ) {
			var newuni = document.createElement( 'div' );
			var unitype = document.getElementById( 'uniaei' );
			var uniplace = document.getElementById( 'uni_area' );
			var placeid = uniplace.value;
			newuni.appendChild( document.createTextNode( unitext.value ) );
			if ( unitype.checked ) {
				newuni.appendChild( document.createTextNode( " - ΑΕΙ" ) );
			}
			else {
				newuni.appendChild( document.createTextNode( " - ΤΕΙ" ) );
			}
			newuni.appendChild( document.createTextNode( " - " + document.getElementById( placeid ).innerHTML );
			unilist.appendChild( newuni );
		}
		else {
			alert( 'Δώσε ένα έγκυρο όνομα πανεπιστημίου' );
		}
		unitext.focus();
		unitext.select();
	}
}