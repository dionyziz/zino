<?php
	function UnitUniversitiesProfilelist( tInteger $townid ) {
		global $libs;
	
		$libs->Load( 'universities' );
	
		?>var modaluni = document.getElementById( 'modaluni' );
		var modalunidivlist = modaluni.getElementsByTagName( 'div' );
		var newdiv = modalunidivlist[ 1 ];
		while( newdiv.firstChild ) {
			newdiv.removeChild( newdiv.firstChild );
		}
		newdiv.appendChild( document.createTextNode( 'Διάλεξε ίδρυμα ' ) );
		var selectlist = <?php
		ob_start();
    	Element( 'universities/unipertownlist' , $townid );
    	echo w_json_encode( ob_get_clean() );
    	?>;
		newdiv.innerHTML += '<br />' + selectlist;<?php
	}
?>