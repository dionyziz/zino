<?php
	return;
	
	set_include_path( '../:./' );

	global $water;
	global $images;
	global $page;
    global $rabbit_settings;
    global $user;
	
	require '../libs/rabbit/rabbit.php';
	
    Rabbit_Construct();
    
	$water->Enable(); // on for all

	header( 'Content-Type: text/html; charset=iso-8859-7' );

    if ( !$user->IsSysOp() ) {
        die( "fuck off" );
    }
	
	$sql = "SELECT COUNT( * ) AS `images_num` FROM `$images`;";
	$res = mysql_query( $sql );
	$row = mysql_fetch_array( $res );
	
	$totalimages = $row[ 'images_num' ];
	$length = 100;
	if ( isset( $_GET[ 'start' ] ) ) {
		$start = $_GET[ 'start' ];
	}
	else {
		$start = 0;
	}
	$sql = "SELECT 
			*
			FROM
				`$images`
			LIMIT ".$start." , ".$length.";";
	$res = mysql_query( $sql );
	$num_rows = mysql_num_rows( $res );
	
	
	?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
		<head>
			<link href="../css/main.css" type="text/css" rel="stylesheet" />
			<title>Image transformation</title>
		</head>
		<body>
		<h2>Images</h2><br /><br /><?php
		if ( $start + $length < $totalimages ) {
			?><a href="images_transformation.php?start=<?php
			echo $start + $length;
			?>">Next >></a><?php
		}
		else {
			 ?>All images have been transformed<?php
		}
		?><br /><br />
		<b>Resources dir:</b> <?php
		echo $rabbit_settings[ 'resourcesdir' ];
		?><br /><b>Total images number:</b> <?php
		echo $totalimages;
		?><br /><br /><?php
		for ( $i = 0; $i < $num_rows; ++$i ) {
			$row = mysql_fetch_array( $res );
			$name = $row[ 'image_name' ];
			$userid = $row[ 'image_userid' ];
			$id = $row[ 'image_id' ];
			$path = $rabbit_settings[ 'resourcesdir' ] . '/' . $userid . '/' . $id;
			$isfile = file_exists( $path );
			if ( $isfile ) {
				$size = filesize( $path );
				
				if ( $size <= 1024*1024 ) {
					$fp = fopen( $path , "r" );
					$binary = fread( $fp , $size );
					fclose( $fp );
					
					$img_src = imagecreatefromstring( $binary );
					$width = ImageSX( $img_src );
					$height = ImageSY( $img_src );
					
					$sql2 = "UPDATE `$images` SET `image_width`='$width' , `image_height`='$height' , `image_size`='$size' WHERE `image_id`='$id' LIMIT 1;";
					$res2 = mysql_query( $sql2 );
				}
				else {
					$width = "out of range";
					$height = "out of range";
					$size = "out of range";
				}
			}
			else {
				$width = 0;
				$height = 0;
				$size = 0;
			}
			?><b>File:</b> <?php
			echo $name;
			?><br /><b>Path:</b><?php
			echo $path;
			?><br />
			<b>Exists:</b><?php
			if ( $isfile ) {
				?>Yes<?php
			}
			else {
				?>No<?php
			}
			?><br />
			<b>Width:</b> <?php
			echo $width;
			?><br />
			<b>Height:</b> <?php
			echo $height;
			?><br />
			<b>Size:</b> <?php
			echo $size;
			?><br />
			<b>SQL query:</b> <?php
			echo $sql2;
			?><br />
			<b>Transformed:</b> <?php
			if ( $res2 ) {
				?>successfully<?php
			}
			else {
				?>unsuccussfully<?php
			}
			?><br/><br /><?php
		}
		
		?>
		
		</body>
	</html>
    <?php
    
    Rabbit_Destruct();
?>
