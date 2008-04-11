<?php

	function UnitUserSettingsGenderupdate( tString $gender , tString $sex , tString $religion , tString $politics ) {
		$gender = $gender->Get();
		$sex = $sex->Get();
		$religion = $religion->Get();
		$politics = $politics->Get();
		?>$( '#religion' ).html( <?php
		    ob_start();
    		Element( 'user/settings/personal/religion' , $religion , $gender );
    		echo w_json_encode( ob_get_clean() );
		?> );<?php
	}
?>
