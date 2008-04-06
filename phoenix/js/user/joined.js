var Joined = {
	doby : $( $( 'div.profinfo form div select' )[ 0 ] ),
	dobm : $( $( 'div.profinfo form div select' )[ 1 ] ),
	dobd : $( $( 'div.profinfo form div select' )[ 2 ] ),
	gender : $( $( 'div.profinfo form div select' )[ 3 ] ),
	location : $( $( 'div.profinfo form div select' )[ 4 ] )
};
$( document ).ready( function() {
	$( 'div a.button' ).click( function() {
		alert( 'year ' , Joined.doby.selected );
		alert( 'month' , Joined.dobm.selected );
		alert( 'day ' , Joined.dobd.selected );
		alert( 'gender ' , Joined.gender.selected );
		alert( 'location ' , Joined.location.selected );
	
	});

});