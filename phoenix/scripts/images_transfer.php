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
	
    
    
	$water->Enable(); // on for all
	
	$sql = "SELECT COUNT( * ) AS `images_num` FROM `$images`;";
	$res = mysql_query( $sql );
	$row = mysql_fetch_array( $res );
	
	$totalimages = $row[ 'images_num' ];
	
	function Images_UploadBatch( $offset , $length ) {
		global $images;
		
		$sql = "SELECT * FROM `$images` ORDER BY `image_id` ASC LIMIT $offset, $length;";
		$res = mysql_query( $sql );
		
		$num_rows = mysql_num_rows( $res );
		 
		for ( $i = 0; $i < $num_rows; ++$i ) {
			$row = mysql_fetch_array( $res );
			$image = New Image( $row );
			$path = $image->UserId()."/".$image->Id();
			$binary = $image->Binary();
			$trans = Image_Upload( $path , $binary );
			?>Name: <?php
			echo $image->Name();
			?><br />Path: <?php
			echo $image->UserId()."/".$image->Id();
			?><br /><?php
			if ( $trans ) {
				?><b>Image transferred successfully</b><?php
			}
			else { 
				?><b>Transfering failed</b><?php
			}
			?><br /><br /><?php
		}
	}
	
	$offset = $_GET[ 'offset' ];
	$length = $_GET[ 'length' ];
	if ( !isset( $offset ) || !isset( $length ) ) {
		$offset = 0;
		$length = 100;
	}
	
	header( 'Content-Type: text/html; charset=iso-8859-7' );
	?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
		<head>
			<link href="../css/main.css" type="text/css" rel="stylesheet" />
			<title>Image transfer</title>
		</head>
		<body>
			<h2>Images transfer to Hades</h2><br /><br/><?php
			if ( $start + $length < $totalimages ) {
				?><a href="images_transfer.php?offset=<?php
				echo $offset + $length;
				?>&amp;length=<?php
				echo $length;
				?>">Next >></a><br /><br /><?php
				Images_UploadBatch( $offset , $length );
			}
			else {
				?>All images have been transfered<?php
			}
		?></body>
	</html>
    <?php
    
    Rabbit_Destruct();
?>