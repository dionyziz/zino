<?php

	function ElementUserTrivialEyecolor( $color ) {
		$eyes = array( 
			'-'		=> '-',
			'black' => 'Μαύρο',
			'brown' => 'Καφέ',
			'green' => 'Πράσινο',
			'blue'	=> 'Μπλε',
			'grey'	=> 'Γκρι'
		);
		echo htmlspecialchars( $eyes[ $color ] );	
	}
?>
