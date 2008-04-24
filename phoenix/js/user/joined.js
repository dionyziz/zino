var Joined = {
	doby : $( 'div.profinfo form div select' )[ 2 ],
	dobm : $( 'div.profinfo form div select' )[ 1 ],
	dobd : $( 'div.profinfo form div select' )[ 0 ],
	gender : $( 'div.profinfo form div select' )[ 3 ],
	location : $( 'div.profinfo form div select' )[ 4 ],
	enabled : true
};
$( document ).ready( function() {
	alert( 'joined' );
	$( 'div a.button' ).click( function() {
		if ( Joined.enabled ) {
			$( this ).addClass( 'button_disabled' );
			Coala.Warm( 'user/joined' , { 
				doby : Joined.doby.options[ Joined.doby.selectedIndex ].value,
				dobm : Joined.dobm.options[ Joined.dobm.selectedIndex ].value,
				dobd : Joined.dobd.options[ Joined.dobd.selectedIndex ].value,
				gender : Joined.gender.options[ Joined.gender.selectedIndex ].value,
				location : Joined.location.options[ Joined.location.selectedIndex ].value 
			});
			Joined.enabled = false;
		}
		return false;
	});

});