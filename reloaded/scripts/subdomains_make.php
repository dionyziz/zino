<?php
	set_include_path( '../:./' );
	
	global $users;
	
	require '../libs/rabbit/rabbit.php';
	
    Rabbit_Construct();
		
	$water->Enable(); // on for all

	header( 'Content-Type: text/html; charset=iso-8859-7' );

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
	<head>
		<title>Subdomains</title>
	</head>
	<body style="text-align:left;padding-left:10px;">
	<?php 
		echo "Hello world!<br />" . $_GET[ 'update' ];
	?>
	</body>
</html>
