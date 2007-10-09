var Uni = {
	Create : function() {
		var unitext = document.getElementById( 'uniname' );
		var unilist = document.getElementById( 'unilist' );
		if ( unitext.value !== '' ) {
			var newuni = document.createElement( 'div' );
			var unitype = document.getElementById( 'uniaei' );
			var uniplace = document.getElementById( 'uni_area' );
			var placeid = uniplace.value;
			var typeid;
			newuni.appendChild( document.createTextNode( unitext.value ) );
			if ( unitype.checked ) {
				typeid = 0;
				newuni.appendChild( document.createTextNode( " - ΑΕΙ" ) );
			}
			else {
				typeid = 1;
				newuni.appendChild( document.createTextNode( " - ΤΕΙ" ) );
			}
			newuni.appendChild( document.createTextNode( " - " + document.getElementById( placeid ).innerHTML ) );
			unilist.appendChild( newuni );
			Coala.Warm( 'universities/create' , { uniname : unitext.value , typeid : typeid , placeid : placeid } );
		}
		else {
			alert( 'Δώσε ένα έγκυρο όνομα πανεπιστημίου' );
		}
		unitext.focus();
		unitext.select();
	},
	Edit : function( uniid ) {
		area = document.getElementById( 'modaldiv' ).cloneNode( true );
		area.style.display = '';
		var uniname = document.getElementById( 'name' + uniid ).innerHTML;
		var unitypeid = document.getElementById( 'type' + uniid ).innerHTML;
		var uniplaceid = document.getElementById( 'place' + uniid ).innerHTML;
		document.getElementById( 'modaluniname' ).value = uniname;
		if ( unitypeid == 0 ) {
			document.getElementById( 'modaluniaei' ).checked = true;
		}
		else {
			document.getElementById( 'modalunitei' ).checked = true;
		}
		document.getElementById( 'modaluniplace' + uniplaceid ).selected = true;
		document.getElementById( 'modaluniplace' ).value = uniplaceid;
		Modals.Create( area, 500, 300 );
	}
}