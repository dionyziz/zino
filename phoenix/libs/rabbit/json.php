<?php
	function w_json_encode( $what, $chopstrings = -1, $depth = 0, $ascii = true ) {
		if ( $depth > 6 ) {
			return '"[and more]"';
		}
		if ( is_int( $what ) || is_float( $what ) ) {
			return $what;
		}
		if ( is_bool( $what ) ) {
			return $what? 'true': 'false';
		}
		if ( is_string( $what ) ) {
			if ( $chopstrings > 0 && $chopstrings < strlen( $what ) ) { 
				// avoid SELECT queries ( There is no point in hiding them ) -- Aleksis
				// NO. How do you know it's a query? The json encoding system should be generalized. 
				// Say I have a 50MB-string starting with the word "SELECT", this doesn't mean it has to crash water! --dionyziz
				$what = substr( $what, 0, $chopstrings ) . '...';
			}
			if ( $ascii ) {
				return '"' . addcslashes( $what, "\\\"\n\r\t\0..\37" ) . '"';
			}
			else {
				return '"' . addcslashes( $what, "\\\"\n\r\t\0..\37!@\@\177..\377" ) . '"';
			}
		}
		if ( is_resource( $what ) ) {
			return '"[resource: ' . get_resource_type( $what ) . ']"';
		}
		if ( is_null( $what ) ) {
			return 'null';
		}
		if ( is_object( $what ) ) {
			return '"[object: ' . get_class( $what ) . ']"';
		}
		if ( is_array( $what ) ) {
			// check if it is non-assosiative
			if ( array_keys( $what ) == range( 0, count( $what ) ) ) {
				for ( $i = 0 ; $i < count( $what ) ; ++$i ) {
					$what[ $i ] = w_json_encode( $what, $chopstrings, $depth + 1, $ascii );
				}
				return '[' . implode(',', $what) . ']';
			}
			$ret = '{';
			reset( $what );
			for ( $i = 0 ; $i < count( $what ) ; ++$i ) {
				$item = each( $what );
				$ret .= w_json_encode( $item[ 'key' ], $chopstrings, $depth, $ascii );
				$ret .= ':';
				$ret .= w_json_encode( $item[ 'value'], $chopstrings, $depth + 1, $ascii );
				if ( $i + 1 < count( $what ) ) {
					$ret .= ',';
				}
			}
			$ret .= '}';
			return $ret;
		}
		return '"[unknown]"';
	}
?>
