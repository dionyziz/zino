var Im = {
	ImOnLoad : function () {
		var email = $( 'div#im div.cred div.empwd div.mail input' )[ 0 ];
		var pwd = $( 'div#im div.cred div.empwd div.pwd input' )[ 0 ];
		var nullcrederror = false;
		email.focus();
		email.select();
		$( email ).keyup( function( event ) {
			if ( event.keyCode == 13 ) {
				if ( !nullcrederror ) {
					$( 'div#im div.cred div.empwd div.mail select' )[ 0 ].focus();
				}
			}
			else {
				if ( nullcrederror ) {
					$( 'div#nullcred' ).fadeOut( 400 );
				}
			}
		} );
		$( 'div#im div.cred div.next a' ).click( function() {
			if ( email.value && pwd.value ) {
				$( 'div#im div.cred div.wrong div.w' ).fadeIn( 400 );
				var emailaddr = email.value + '@' + $( 'div#im div.cred div.empwd div.mail select' )[ 0 ].value;
				alert( 'email is: ' + emailaddr );
				alert( 'password is: ' + pwd.value );
			}
			else {
				$( 'div#nullcred' ).fadeIn( 400 );
				nullcrederror = true;
			}
			return false;
		} );
	}
};