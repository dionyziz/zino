var Joined = {
	doby : $( 'div#joined' )[ 0 ] ? $( 'div.profinfo form div select' )[ 2 ] : false,
	dobm : $( 'div#joined' )[ 0 ] ? $( 'div.profinfo form div select' )[ 1 ] : false,
	dobd : $( 'div#joined' )[ 0 ] ? $( 'div.profinfo form div select' )[ 0 ] : false,
	gender : $( 'div#joined' )[ 0 ] ? $( 'div.profinfo form div select' )[ 3 ] : false,
	location : $( 'div#joined' )[ 0 ] ? $( 'div.profinfo form div select' )[ 4 ] : false,
	enabled : true,
	invaliddob : false
};
$( document ).ready( function() {
	if ( $( 'div#joined' )[ 0 ] ) {
		$( 'div a.button' ).click( function() {
			if ( Joined.enabled ) {
				$( this ).addClass( 'button_disabled' );
				if ( Joined.doby.options[ Joined.doby.selectedIndex ].value != -1 && Joined.dobm.options[ Joined.dobm.selectedIndex ].value != -1 && Joined.dobd.options[ Joined.dobd.selectedIndex ].value != -1 ) {
					if ( !Dates.ValidDate( Joined.dobd.options[ Joined.dobd.selectedIndex ].value , Joined.dobm.options[ Joined.dobm.selectedIndex ].value , Joined.doby.options[ Joined.doby.selectedIndex ].value ) ) {
						$( 'div.profinfo form span.invaliddob' ).css( 'opacity' , '0' ).show().animate( { opacity : "1" } , 200 ) ;
						Joined.invaliddob = true;
					}
				}
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
	}
	$( 'div.profinfo form div select' ).change( function() {
		$( 'div.profinfo form span.invaliddob' ).animate( { opacity : "0" } , 200 , function() {
			$( this ).hide();
		} );
	} );
});