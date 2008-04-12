<?php

	function UnitUserSettingsSave( tString $gender , tInteger $place , tString $education , tString $sex , tString $religion , tString $politics , tString $aboutme ) {
		global $user;
	
		$gender = $gender->Get();
		$place = $place->Get();
		$education = $education->Get();
		$sex = $sex->Get();
		$religion = $religion->Get();
		$politics = $politics->Get();
		$aboutme = $aboutme->Get();
		
		if ( $gender ) {
			?>alert( '<?php echo $gender; ?>' );<?php
		}
		if ( $place ) {
			?>alert( '<?php echo $place; ?>' );<?php
		}
		if ( $education ) {
			?>alert( '<?php echo $education; ?>' );<?php
		}
		if ( $sex ) {	
			?>alert( '<?php echo $sex; ?>' );<?php
		}
		if ( $religion ) {
			?>alert( '<?php echo $religion; ?>' );<?php
		}
		if ( $politics ) {
			?>alert( '<?php echo $politics; ?>' );<?php
		}
		if ( $aboutme ) {
			?>alert( '<?php echo $aboutme; ?>' );<?php
		}
	}
?>
