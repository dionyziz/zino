<?php
	return;
	
	set_include_path( '../:./' );
	
	global $users;
	
	require '../libs/rabbit/rabbit.php';
	
    Rabbit_Construct();
		
	$water->Enable(); // on for all

	header( 'Content-Type: text/html; charset=iso-8859-7' );

	$sql = "SELECT 
				* 
			FROM 
				`merlin_users` 
			WHERE 
				`user_name` LIKE '% %';";
	$res = mysql_query( $sql );
	$num_rows = mysql_num_rows( $res );
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
		<head>
			<title>Transform usernames</title>
		</head>
		<body style="text-align:left;padding-left:10px;">
		<h2>Usernames with ' ':</h2><?php
		for ( $i = 0; $i < $num_rows; ++$i ) {
			$row = mysql_fetch_array( $res );
			$username = $row[ 'user_name' ];
			$userid = $row[ 'user_id' ];
			?><b>Starting username:</b> <?php
			echo $username;
			?><br />
			<b>New username:</b> <?php
			$newname = str_replace( ' ' , '_' , $username );
			echo $newname;
			$newname = addslashes( $newname );
			$sql2 = "UPDATE `$users` SET `user_name` = '$newname' WHERE `user_id` = '$userid' LIMIT 1;";
			$res2 = mysql_query( $sql2 );
			?><br /><?php
			if ( $res2 ) {
				?>Name changed successfully<?php
			}
			else {
				?>Name not changed<?php
			}
			?><br /><br /><?php
		}
		?></body>
	</html>