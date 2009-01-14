var Im = {
	ImOnLoad : function () {
		$( 'div#im div.cred div.empwd div.mail input' )[ 0 ].focus();
		$( 'div#im div.cred div.empwd div.mail input' )[ 0 ].select();
		$( 'div#im div.cred div.next a' ).click( function() {
			$( 'div#im div.cred div.wrong div.w' ).fadeIn( 400 );
			return false;
		} );
	}
};