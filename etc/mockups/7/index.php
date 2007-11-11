<?php

$xmlmimetype = 'application/xhtml+xml';
$accepted = explode( ',' , $_SERVER[ 'HTTP_ACCEPT' ] );
if ( in_array( $xmlmimetype , $accepted ) ) {
	header( "Content-Type: application/xhtml+xml; charset=utf-8" );
}
else {
	header( "Content-Type: text/html; charset=utf-8" );
}
echo '<?xml version="1.0" encoding="utf-8"?>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="el" lang="el">
	<head>
		<title>
			Chit-Chat
		</title>
		<link rel="stylesheet" type="text/css" href="default.css" />
		<link rel="stylesheet" type="text/css" href="links.css" />
		<link rel="stylesheet" type="text/css" href="banner.css" />
		<link rel="stylesheet" type="text/css" href="footer.css" />
        <script type="text/javascript" src="../../../js/pngfix.js"></script>
	</head>
	<body><?php
	if ( isset( $_GET[ 'p' ] ) ) {
		$p = $_GET[ 'p' ];
	}
	else {
		$p = 'frontpage';
	}
	if ( isset( $_GET[ 'loggedin' ] ) ) {
		include 'banner.php';
	}
	else {
		include 'bannerout.php';
	}
	include 'footer.php';
	?>
	</body>
</html>