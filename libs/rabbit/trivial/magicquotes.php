<?php
	function magicquotes_off() {
        if ( !get_magic_quotes_gpc() ) {
            return;
        }
        
		// manual magic quotes off
		foreach ( $_GET as $key => $value ) {
			$_GET[ $key ] = stripslashes( $value );
		}
		foreach ( $_POST as $key => $value ) {
			$_POST[ $key ] = stripslashes( $value );
		}
	}
?>
