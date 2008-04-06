var Joined = {
	dobd : $( 'div.profinfo form div select' )[ 0 ],
	dobm : $( 'div.profinfo form div select' )[ 1 ],
	doby : $( 'div.profinfo form div select' )[ 2 ],
	gender : $( 'div.profinfo form div select' )[ 3 ],
	location : $( 'div.profinfo form div select' )[ 4 ]
};
$( document ).ready( function() {
	$( 'div a.button' ).click( function() {
		alert( Joined.doby.options[ Joined.doby.selectedIndex ].value );
		alert( Joined.dobm.options[ Joined.dobm.selectedIndex ].value );
		alert( Joined.dobd.options[ Joined.dobd.selectedIndex ].value );
		alert( Joined.gender.options[ Joined.gender.selectedIndex ].value );
		alert( Joined.location.options[ Joined.location.selectedIndex ].value );
		return false;
	});

});