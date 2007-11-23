<?php
	return;
	
	set_include_path( '../:./' );
	
	global $water;
	global $images;
	global $albums;
	global $page;
	
	require '../libs/rabbit/rabbit.php';
	
    Rabbit_Construct();
		
	$water->Enable(); // on for all

	header( 'Content-Type: text/html; charset=iso-8859-7' );
	
	$sql2 = "SELECT COUNT( * ) AS images_num FROM `$images` WHERE `image_delid` = '0';";
	$sql3 = "SELECT COUNT( * ) AS images_num FROM `$images` WHERE `image_delid` = '1';";
	$sql4 = "SELECT COUNT( * ) AS images_num FROM `$images` WHERE `image_albumid` <> '0' AND `image_delid` = '0';";
	$sql5 = "SELECT COUNT( * ) AS images_num FROM `$images` WHERE `image_albumid` <> '0' AND `image_delid` = '1';";
	$sql6 = "SELECT COUNT( * ) AS albums_num FROM `$albums` WHERE `album_delid` = '0';";
	$sql7 = "SELECT COUNT( * ) AS albums_num FROM `$albums` WHERE `album_delid` = '1';";
	
	$res2 = mysql_query( $sql2 );
	$row2 = mysql_fetch_array( $res2 );
	$nondelimg = $row2[ 'images_num' ];
	
	$res3 = mysql_query( $sql3 );
	$row3 = mysql_fetch_array( $res3 );
	$delimg = $row3[ 'images_num' ];
	$totalimg = $nondelimg + $delimg;
	
	$res4 = mysql_query( $sql4 );
	$row4 = mysql_fetch_array( $res4 );
	$nondelalbimg = $row4[ 'images_num' ];
	
	$res5 = mysql_query( $sql5 );
	$row5 = mysql_fetch_array( $res5 );
	$delalbimg = $row5[ 'images_num' ];
	$totalalbimg = $nondelalbimg + $delalbimg;
	
	$res6 = mysql_query( $sql6 );
	$row6 = mysql_fetch_array( $res6 );
	$nondelalb = $row6[ 'albums_num' ];
	
	$res7 = mysql_query( $sql7 );
	$row7 = mysql_fetch_array( $res7 );
	$delalb = $row6[ 'albums_num' ];
	$totalalb = $nondelalb + $delalb;
	
	
	
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
		<head>
			<title>Images control panel</title>
		</head>
		<body style="text-align:left;padding-left:10px;">
			<h2>Images</h2><br /><br />
			<b>Total images:</b> <?php
			echo $totalimg;
			?><br />
			<b>Active images:</b> <?php
			echo $nondelimg;
			?><br />
			<b>Deleted images:</b> <?php
			echo $delimg;
			?>
			<br /><br />
			<b>Total album images:</b> <?php
			echo $totalalbimg;
			?><br />
			<b>Active album images:</b> <?php
			echo $nondelalbimg;
			?><br />
			<b>Deleted album images:</b> <?php
			echo $delalbimg;
			?>
			<br /><br />
			<ul>
				<li><a href="images_transformation.php">Run images size script</a> (inserts width, height and size in the database for all the images)</li>
				<li>Run cleanup script</li>
			</ul>
		</body>
	</html><?php
	
	Rabbit_Destruct();
?>
