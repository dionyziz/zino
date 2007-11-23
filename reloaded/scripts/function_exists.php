<?php

	foreach ( $_GET as $key => $value ) {
		if ( $value == '' ) {
			$function = $key;
		}
	}
	
	if ( function_exists( $function ) ) {
		echo "function $function exists";
	}
	else {
		echo "function $function doesn't exist";
	}

?>