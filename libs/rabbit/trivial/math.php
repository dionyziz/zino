<?php
    return; // deprecated
    
    // TODO: replace all calls to this by ceil(), floor(), round(), and intval()
	function rounding( $no , $direction ) {
	/*
		A small script that allow you to round up, round down or just return the integer number.
		The $direction value cand be:
		0  - rounding down
		1 - rounding up
		any thing else for returning the integer value	
																	*/
		
		$skip = 0;
		if ( is_float( $no ) and $direction = 1 ) {
			$exploded = explode( "." , $no );
			$nrr = $exploded[0] + 1;
			$skip=1;
	    }
	 
		if (	is_float( $no ) and $direction = 0 ) {
			$exploded = explode( "." , $no );
			$nrr = $exploded[0];
			$skip=1;
	    }

		if ( !is_float( $no ) and $skip == 1 ) {
			$nrr = $nrr; 
		}
		else { 
			$nrr = floor($nrr);
		}

	    return $nrr;
	}
?>
