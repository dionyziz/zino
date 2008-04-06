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
		/*
		alert( 'month' , Joined.dobm.selectedIndex );
		alert( 'day ' , Joined.dobd.selectedIndex );
		alert( 'gender ' , Joined.gender.selectedIndex );
		alert( 'location ' , Joined.location.selectedIndex );
		*/
		return false;
	});

});