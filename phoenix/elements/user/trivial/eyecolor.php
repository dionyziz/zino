<?php

	function ElementUserTrivialEyecolor( $color ) {
		$eyes = array( 
			'-'		=> '-',
			'black' => 'Μαύρο',
			'brown' => 'Καφέ',
			'green' => 'Πράσινο',
			'blue'	=> 'Μπλε',
			'gray'	=> 'Γκρι'
		);
		echo htmlspecialchars( $eyes[ $color ] );	
	}
?>
