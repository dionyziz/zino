var Uni = {
	Create : function() {
		var unitext = document.getElementById( 'uniname' );
		var unilist = document.getElementById( 'unilist' );
		var newuni = document.createElement( 'div' );
		alert( unitext.value );
		if ( unitext.value != '' ) {
			newuni.appendChild( document.createTextNode( unitext.value ) );
			unilist.insertBefore( unilist.firstChild , newuni );
		}
		else {
			alert( 'Δώσε ένα έγκυρο όνομα πανεπιστημίου' );
		}	
	}
}