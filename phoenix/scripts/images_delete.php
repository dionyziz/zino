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
	$sql = "SELECT 
				*
			FROM 	
				`$images`
			WHERE `image_delid` = '1';";
	$sqlr = mysql_query( $sql );
	$num_rows = mysql_num_rows( $sqlr );
	header( 'Content-Type: text/html; charset=iso-8859-7' );
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
		<head>
			<title>Images control panel</title>
		</head>
		<body style="text-align:left;padding-left:10px;">
		<h2>Deleted images</h2><?php
		for ( $i = 0; $i < $num_rows; ++$i ) {
			$row = mysql_fetch_array( $sqlr );
			$name = $row[ 'image_name' ];
			$userid = $row[ 'image_userid' ];
			$id = $row[ 'image_id' ];
			$path = $rabbit_settings[ 'resourcesdir' ] . '/' . $userid . '/' . $id;
			// $res = unlink( $path );
			echo $name;
			if ( $res ) {
				?> - Successfully deleted<?php
			}
			else {
				?> - Delete failed<?php
			}
			?><br /><?php
		}
		?>
		
		
		</body>
	</html>