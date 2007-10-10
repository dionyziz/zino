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
		var inputlist = area.getElementsByTagName( 'input' );
		var selectlist = area.getElementsByTagName( 'select' );
		var uniname = document.getElementById( 'name' + uniid ).innerHTML;
		var unitypeid = document.getElementById( 'type' + uniid ).innerHTML;
		var uniplaceid = document.getElementById( 'place' + uniid ).innerHTML;
		inputlist[ 0 ].value = uniname;
		alert( 'uniplaceid is ' + uniplaceid );
		if ( unitypeid == 0 ) {
			inputlist[ 1 ].checked = true;
		}
		else {
			inputlist[ 2 ].checked = true;
		}
		selectlist[ 0 ].getElementById( 'modaluniplace' + uniplaceid ).selected = true;		Modals.Create( area, 400, 200 );
	}
};