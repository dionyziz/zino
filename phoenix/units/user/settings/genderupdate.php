<?php

	function UnitUserSettingsGenderupdate( tString $gender , tString $sex , tString $religion , tString $politics ) {
		$gender = $gender->Get();
		$sex = $sex->Get();
		$religion = $religion->Get();
		$politics = $politics->Get();
		?>$( '#sex' ).html( <?php
		    ob_start();
    		Element( 'user/settings/personal/sex' , $sex , $gender );
    		echo w_json_encode( ob_get_clean() );
		?> );
		$( '#religion' ).html( <?php
		    ob_start();
    		Element( 'user/settings/personal/religion' , $religion , $gender );
    		echo w_json_encode( ob_get_clean() );
		?> );
		$( '#politics' ).html( <?php
		    ob_start();
    		Element( 'user/settings/personal/politics' , $politics , $gender );
    		echo w_json_encode( ob_get_clean() );
		?> );<?php
	}
?>
