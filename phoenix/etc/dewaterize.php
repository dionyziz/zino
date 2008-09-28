#!/usr/bin/env php

<?php

	function optimized( $source ) {
		$methods = array( 'Notice', 'Trace', 'Warning' );
		$new = '';
		$append = true;
		$tokens = token_get_all( $source );
		for ( $i = 0; $i < count( $tokens ); ++$i ) {
			if ( is_array( $tokens[ $i ] ) ) {
				$tokens[ $i ] = $tokens[ $i ][ 1 ];
			}
		}
		foreach ( $tokens as $i => $token ) {
			if ( $token == 'w_assert' ) {
				$append = false;
			}
			else if ( $token == '$water' && $tokens[ $i + 1 ] == '->' ) {
				foreach ( $methods as $meth ) {
					if ( $tokens[ $i + 2 ] == $meth ) {
						$append = false;
					}
				}
			}
			if ( $append ) {
				$new .= $token;
			}
			if ( $token == ';' ) {
				$append = true;
			}
		}
		return $new;
	}

	function dewaterize( $directory, $extensions ) {
		$files = New RecursiveIteratorIterator( New RecursiveDirectoryIterator( $directory ) );
		foreach ( $files as $file ) {
			$filename = $file->getFilename();
			if ( $filename != 'water.php' ) {
				foreach ( $extensions as $ext ) {
					if ( substr( $filename, strlen( $filename ) - strlen( $ext ) ) == $ext ) {
						file_put_contents( $file, optimized( file_get_contents( $file ) ) );
						break;
					}
				}
			}
		}
	}

	switch ( $argc ) {
		case 1:
			$directory = '.';
			$extensions = array( 'php' );
			break;
		case 2:
			$directory = $argv[ 1 ];
			$extensions = array( 'php' );
			break;
		default:
			$directory = $argv[ 1 ];
			$extensions = array_slice( $argv, 2 );
	}
	dewaterize( $directory, $extensions );

?>
