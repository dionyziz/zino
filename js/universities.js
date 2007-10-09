var Uni = {
	Create : function() {
		var unitext = document.getElementById( 'uniname' );
		var unilist = document.getElementById( 'unilist' );
		alert( unitext.value );
		if ( unitext.value != '' ) {
			var newuni = document.createElement( 'div' );
			alert( newuni );
			alert( unilist );
			newuni.appendChild( document.createTextNode( unitext.value ) );
			unilist.insertBefore( unilist.firstChild , newuni );
		}
		else {
			alert( 'Δώσε ένα έγκυρο όνομα πανεπιστημίου' );
		}	
	}
}