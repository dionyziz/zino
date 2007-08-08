<?php
	return;
?>
<html>
	<head><title>Directory listing and rights</title></head>
	<body>
<?php
	require 'header.php';
	global $xc_settings;
	
	function show_contents( $dir ) {
		if ( is_dir( $dir ) ) {
		   if ($dh = opendir($dir)) {
		       while ( ($file = readdir( $dh ) ) !== false) {
		           echo "filename: $file : filetype: " . filetype($dir . $file) . "\n";
		       }
		       closedir( $dh );
		   }
		}
	}
	if ( is_dir( $xc_settings[ 'resourcesdir' ] ) ) {
		?>It is a dir<?php
	}
	else {
		?>It is not a dir<?php
	}
	show_contents( $xc_settings['resourcesdir'] );
?>
	</body>
</html>