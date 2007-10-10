<?php
	function UnitUniversitiesProfilelist( tInteger $townid ) {
		global $libs;
	
		$libs->Load( 'universities' );
	
		?>var modaluni = document.getElementById( 'modaluni' );
		var modalunidivlist = modaluni.getElementsByTagName( 'div' );
		var newdiv = modalunidivlist[ 1 ];
		newdiv.appendChild( document.createTextNode( 'Διάλεξε ίδρυμα ' ) );
		var selectlist = <?php
		ob_start();
    	Element( 'universities/unipertownlist' , $townid );
    	echo w_json_encode( ob_get_clean() );
    	?>;
		newdiv.appendChild( selectlist );<?php
	}
?>