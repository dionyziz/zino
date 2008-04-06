<?php
	function UnitUserJoined( tInteger $doby , tInteger $dobm , tInteger $dobd , tString $gender , tInteger $location ) {
		global $user;
		
		$doby = $doby->Get();
		$dobm = $dobm->Get();
		$dobd = $dobd->Get();
		$gender = $gender->Get();
		$location = $location->Get();
		
		?>alert( '<?php echo $doby; ?>' );
		alert( '<?php echo $dobm; ?>' );
		alert( '<?php echo $dobd; ?>' );
		alert( '<?php echo $gender; ?>' );
		alert( '<?php echo $location; ?>' );<?php
	}
?>
