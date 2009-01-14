var Im = {
	ImOnLoad : function () {
		var email = $( 'div#im div.cred div.empwd div.mail input' )[ 0 ];
		var pwd = $( 'div#im div.cred div.empwd div.pwd input' )[ 0 ];
		email.focus();
		email.select();
		$( 'div#im div.cred div.next a' ).click( function() {
			$( 'div#im div.cred div.wrong div.w' ).fadeIn( 400 );
			var emailaddr = email.value + '@' + $( 'div#im div.cred div.empwd div.mail select' )[ 0 ].value;
			alert( 'email is: ' + emailaddr );
			alert( 'password is: ' + pwd.value );
			return false;
		} );
	}
};