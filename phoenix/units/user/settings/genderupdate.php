<?php

	function UnitUserSettingsGenderupdate( tString $gender ) {
		$gender = $gender->Get();
		
		?>$( '#religion' ).html( <?php
		    ob_start();
    		Element( 'user/settings/personal/religion' , $gender );
    		echo w_json_encode( ob_get_clean() );
		?> );<?php
	}
?>
