<?php
	return;
	
	set_include_path( '../:./' );
	require '../libs/rabbit/rabbit.php';
	Rabbit_Construct();

	global $water;
	global $images;
	global $page;
    global $rabbit_settings;
	global $libs;
	
	$libs->Load( 'image/image' );
	header( 'Content-Type: text/html; charset=iso-8859-7' );
	
	?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
		<head>
			<link href="../css/main.css" type="text/css" rel="stylesheet" />
			<title>One photo transfer</title>
		</head>
		<body>
			<h2>One photo testing</h2><?php
			$image = New Image( 3024 );
			$path = $image->UserId()."/".$image->Id();
			$binary = $image->Binary();
			$result = Image_Upload( $path , $binary );
			?><h3>Image</h3>
			Name: <?php
			echo $image->Name();
			?><br />Id: <?php
			echo $image->Id();
			?><br />Userid: <?php
			echo $image->UserId();
			?><br />Path: <?php
			echo $path;
			?><br /><?php
			echo $result;
			?></body>
	</html>