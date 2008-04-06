var Joined = {
	doby : $( 'div.profinfo form div select' )[ 0 ],
	dobm : $( 'div.profinfo form div select' )[ 1 ],
	dobd : $( 'div.profinfo form div select' )[ 2 ],
	gender : $( 'div.profinfo form div select' )[ 3 ],
	location : $( 'div.profinfo form div select' )[ 4 ]
};
$( document ).ready( function() {
	$( 'div a.button' ).click( function() {
		alert( Joined.doby );
		alert( Joined.doby.selectedIndex );
		alert( Joined.dobm.selectedIndex );
		alert( Joined.dobd.selectedIndex );
		alert( Joined.gender.selectedIndex );
		alert( Joined.location.selectedIndex );
		return false;
	});

});