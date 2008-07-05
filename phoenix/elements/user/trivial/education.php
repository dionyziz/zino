<?php

	function ElementUserTrivialEducation( $education ) {
		$educations = array( '-' => '-',
							 'elementary' => 'Δημοτικό',
							 'gymnasium' => 'Γυμνάσιο',
							 'TEE' 		  => 'ΤΕΕ',
							 'lyceum' 	  => 'Λύκειο',
							 'TEI'		  => 'ΤΕΙ',
							 'university' => 'Πανεπιστήμιο'
		);
		echo htmlspecialchars( $educations[ $education ] );
	}
?>
