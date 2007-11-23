<?php
	return;
	
	set_include_path( '../:./' );
	
	global $water;
	global $images;
	global $albums;
	global $page;
	global $rabbit_settings;
	
	require '../libs/rabbit/rabbit.php';
	
    Rabbit_Construct();
		
	$water->Enable(); // on for all
	header( 'Content-Type: text/html; charset=iso-8859-7' );
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
		<head>
			<title>Images control panel</title>
		</head>
		<body style="text-align:left;padding-left:10px;"><?php
			if ( is_dir( $dir ) ) {
			    if ( $dh = opendir( $dir ) ) {
			        while ( ( $file = readdir( $dh ) ) !== false ) {
			            echo "filename: $file : filetype: " . filetype( $dir . $file ) . "\n";
			        }
			        closedir( $dh );
			    }
			}
			else {
				echo "file: $dir";
			}
		?>
		</body>
	</html><?php
	Rabbit_Destruct();
?>