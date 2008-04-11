<?php

	function UnitUserSettingsGenderupdate( tString $gender ) {
		$gender = $gender->Get();
		
		?>$( '#religion' )[ 0 ].html( <?php
		    ob_start();
    		Element( 'user/settings/personal/religion' , $gender );
    		echo w_json_encode( ob_get_clean() );
		?> );<?php
	}
?>
