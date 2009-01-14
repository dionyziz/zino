var Im = {
	ImOnLoad : function () {
		var email = $( 'div#im div.cred div.empwd div.mail input' )[ 0 ];
		var pwd = $( 'div#im div.cred div.empwd div.pwd input' )[ 0 ];
		var mailerror = false;
		var pwderror = false;
		email.focus();
		email.select();
		$( email ).keyup( function( event ) {
			if ( event.keyCode == 13 ) {
				if ( !mailerror ) {
					$( 'div#im div.cred div.empwd div.mail select' )[ 0 ].focus();
				}
			}
			else {
				if ( mailerror ) {
					$( 'div#nullmail' ).fadeOut( 400 );
					mailerror = false;
				}
			}
		} );
		$( pwd ).keyup( function( event ) {
			if ( event.keyCode == 13 ) {
				if ( !pwderror ) {
					$( 'div#im div.cred div.next a' )[ 0 ].focus();
				}
			}
			else {
				if ( pwderror ) {
					$( 'div#nullpwd' ).fadeOut( 400 );
					pwderror = false;
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
				if ( !email.value ) {
					$( 'div#nullmail' ).fadeIn( 400 );
					mailerror = true;
				}
				else {
					$( 'div#nullpwd' ).fadeIn( 400 );
					pwderror = true;
				}
			}
			return false;
		} );
	}
};