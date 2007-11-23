<?php

	return;
	
	global $water;

	require '../header.php';

	$water->Enable(); // on for all

	header( 'Content-Type: text/html; charset=iso-8859-7' );
	
	?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
		<head>
			<link href="../css/main.css" type="text/css" rel="stylesheet" />
			<link href="../css/water.css" type="text/css" rel="stylesheet" />
			<title>Live2Sandbox</title>
		</head>
		<body><?php
		
			global // keep it a-z
			$articles,
			$bans,
			$bulk,
			$categories,
			$chats,
			$comments,
			$exvars,
			$images,
			$logs,
			$places,
			$pmcondition,
			$pmsfolders,
			$pms,
			$polloptions,
			$polls,
			$profiles,
			$questions,
			$relations,
			$revisions,
			$ricons,
			$searches,
			$shoutbox,
			$starring,
			$stories,
			$templates,
			$userbans,
			$users,
			$usershout,
			$votes;

			?>Connecting to Sandbox... <?php
			$sandbox = New Database( 'chitchat' );
			$sandbox->Connect( 'localhost' );
			$sandbox->Authenticate( 'chitchat' , '7paz?&aS' );
			$sandbox->SetCharset( 'DEFAULT' );
			$sandboxdb = 'chitchat';

			?>OK. <br />Connecting to Live... <?php
			$cclive = New Database( 'excalibur-sandbox' );
			$cclive->Connect( 'localhost' );
			$cclive->Authenticate( 'excalibursandbox' , 'viuhluqouhoa' );
			$cclive->SetCharset( 'DEFAULT' );
			$cclivedb = 'excalibur-sandbox';
			?>OK. <br /><?php
			
			$tables = array(
				$comments
			);
			
			/*
			$tables = array(
				$articles,
				$bans,
				$bulk,
				$categories,
				$chats,
				$exvars,
				$images,
				$pageviews,
				$places,
				$pmcondition,
				$pmsfolders,
				$polloptions,
				$polls,
				$profiles,
				$questions,
				$relations,
				$revisions,
				$ricons,
				$searches,
				$shoutbox,
				$starring,
				$templates,
				$userbans,
				$users,
				$usershout,
				$votes
			);
			*/
			
			foreach ( $tables as $table ) {
				?>Updating <?php
				echo $table;
				?>...<?php
				
				$sql = "TRUNCATE `$table`";
				$sandbox->Query( $sql );
				
				$sql = "SELECT * FROM `$cclivedb`.`$table`;";
				$res = $cclive->Query( $sql );
				
				if ( !$res->Results() ) {
					?> (no rows)<br /><?php
					continue;
				}
				
				$rows = $res->MakeArray();
				$changes = array();
				
				if ( $table == $bulk || $table == $comments ) {
					$realrows = array_chunk( $rows, 2 );
					foreach ( $realrows as $rows ) {
						$curchanges = $sandbox->Insert( $rows, array( $sandboxdb, $table ) );
						foreach ( $curchanges as $curchange ) {
							$changes[] = $curchange;
						}
					}
				}
				else {
					$changes = $sandbox->Insert( $rows , array( $sandboxdb , $table ) );
				}
				
				$update = true;
				$i = 1;
				foreach ( $changes as $change ) {
					if ( !$change->Impact() ) {
						$update = false;
						break;
					}
					++$i;
				}
				
				if ( $update ) {
					?>done (<?php
					echo $i;
					?> rows changed).<br /><?php	
				}
				else {
					?>error in row <?php
					echo $i;
					?>!<br /><?php
				}
			}
			
		?></body>
	</html>