<?php

return; 

set_include_path( '../:./' );

global $water;

require 'header.php';

$water->Enable(); // on for all

if ( isset( $_POST[ 'step' ] ) ) {
	$step = $_POST[ 'step' ];
}
else {
	$step = -1;
}

header( "Content-Type: application/xhtml+xml; charset=utf-8" );

echo '<?xml version="1.0" encoding="utf-8"?>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
	<head>
		<title>Wazzup2Reloaded Upgrade Script</title>
		<link href="../css/main.css" type="text/css" rel="stylesheet" />
		<link href="../css/water.css" type="text/css" rel="stylesheet" />
	</head>
	<body><div class="axel">
		<h2>Wazzup2Reloaded</h2>
		Welcome to Wazzup2Reloaded upgrade script.<br /><br />
		<form action="wazzup2reloaded.php" method="post">
			Step: <select name="step">
				<?php
					for ( $i = 0 ; $i <= 7 ; ++$i ) {
						?><option value="<?php
						echo $i;
						?>"<?php
						if ( $step == $i - 1 ) {
							?> selected="selected"<?php
						}
						?>>Step <?php
						echo $i;
						?></option><?php
					}
				?>
			</select> <input type="submit" value="Upgrade!" />
		</form>
		<br />
<?php

flush();

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

?><b>Upgrade Logs</b><br /><br />Connecting to Wazzup... <?php
flush();
$wazzup = New Database( 'chitchat' );
$wazzup->Connect( 'localhost' );
$wazzup->Authenticate( 'chitchat' , '7paz?&aS' );
$wazzup->SetCharset( 'greek' );

?>OK. <br />Connecting to Reloaded... <?php
flush();
// $reloaded = New Database( 'ccreloaded' );
$reloaded = New Database( 'excalibur-sandbox' );
$reloaded->Connect( 'localhost' );
// $reloaded->Authenticate( 'ccreloaded' , 'net31future' );
$reloaded->Authenticate( 'excalibursandbox' , 'viuhluqouhoa' );
$reloaded->SetCharset( 'DEFAULT' );

?>OK. <br /><?php
if ( $step >= 0 ) {
	?>Applying step <?php
	echo $step;
	?>... <?php
	flush();
	$water->Profile( 'Upgrade Step ' . $step );
	if ( $step == 4 || $step == 6 ) {
		if ( isset( $_POST[ 'offset' ] ) ) {
			$offset = $_POST[ 'offset' ];
		}
		else {
			$offset = 0;
		}
		?> (offset <?php
		echo $offset;
		?>)... <?php
		switch ( $step ) {
			case 4:
				$processed = Wazzup2Reloaded_Step4( $offset );
				break;
			case 6:
				$processed = Wazzup2Reloaded_Step6( $offset );
				break;
		}
	}
	else {
		call_user_func( 'Wazzup2Reloaded_Step' . $step );
	}
	$time = $water->ProfileEnd();
	?><br />Step <?php
	echo $step;
	?> applied successfully in <?php
	echo $time;
	?> seconds.<br /><br /><?php
}

if ( isset( $processed ) && $processed > 0 ) {
	?>Reapplying with higher offset (please hold)...<br />
	<form id="f" action="wazzup2reloaded.php" method="post">
		<input type="hidden" name="step" value="<?php
		echo $step;
		?>" />
		<input type="hidden" name="offset" value="<?php
		echo $offset + 3000;
		?>" />
	</form>
	<script type="text/javascript">
		document.getElementById( "f" ).submit();
	</script><?php
}
?><br />
(dumping water logs - <a href="" onclick="Water.OpenWindow();return false;">debug</a>) <script type="text/javascript"> <![CDATA[ <?php
$water->GenerateJS();
?>]]> </script><br /><br /></div></body>
</html><?php

function Wazzup2Reloaded_Step0() {
	// convert images to the new format
	global $images;
	global $wazzup;
	global $reloaded;
	global $water;
	
	$water->SetSetting( 'calltraces' , false ); // don't keep calltraces in this step; might get us out of memory

	$offset = 0;
	$limit = -1;
	
	$resources_dir = '/home/virtual/excalibur.qlabs.gr/httpdocs/resources/';
	
	?><b>Converting Images</b>... <br />Removing old images... <?php
	flush();
	
	rmdirr( $resources_dir , false ); // clear all existing images
	
	if ( !file_exists( $resources_dir ) ) {
		$water->Notice( 'Resources directory doesn\'t exist!' , $resources_dir );
		if ( mkdir( $resources_dir ) == false ) {
			?>ERROR: Failed to create non-existing resources directory. Terminating execution.<br /><?php
			return;
		}
		chmod( $resources_dir , 0777 );
	}
	
	?>OK.<br />Loading images from database...<?php
	
	$sql = "SELECT 
				`id` , `userid` , `image` 
			FROM 
				`$images`";
	
	if ( $limit != -1 ) {
		$sql .= ' LIMIT ' . $offset . ',' . $limit;
	}
	
	$sql .= ';';
	
	$res = $wazzup->Query( $sql );
	?>OK.<br /><?php
	flush();
	$num_rows = $res->NumRows();
	for ( $i = 0; $i < $num_rows; ++$i ) {
		$thisimage = $res->FetchArray();
		$imgid = $thisimage[ 'id' ];
		$userid = $thisimage[ 'userid' ];
		if ( $userid > 0 ) {
			$binary = base64_decode( $thisimage[ 'image' ] );
			$folder = "/home/virtual/excalibur.qlabs.gr/httpdocs/resources/$userid";
			if ( !file_exists( $folder ) ) {
				mkdir( $folder );
				chmod( $folder , 0777 );
			}
			?>Processing <?php
			$file = "$folder/$imgid";
			echo $file;
			?>... <?php
			flush();
			$fp = fopen( $file , 'w' );
			fwrite( $fp , $binary );
			fclose( $fp );
			chmod( $file , 0777 );
			echo round( ( $i + 1 ) / $num_rows * 100 );
			?>% completed...<br /><?php
			flush();
		}
	}
}

function Wazzup2Reloaded_Step1() {
	// TRUNCATE tables from reloaded database
	global $reloaded;
	
	global $logs;
	global $comments;
	global $articles;
	global $bulk;
	global $revisions;
	
	?><b>Truncating existing tables</b>... <br /><?php
	flush();
	
	$tables = $reloaded->Tables();
	foreach( $tables as $table ) {
		switch ( strtolower( $table->Name() ) ) {
			case $logs: // will do those later
			case $comments:
			case $articles:
			case $bulk:
			case $revisions:
				break;
			default:
				?>Truncating <?php
				echo $table->Name();
				?>... <?php
				flush();
				$table->Truncate();
				?>OK.<br /><?php
				flush();
		}
	}
	
	return;
}

function Wazzup2Reloaded_Step2() {
	//copy identical tables except from merlin_logs ( step 3 )
	global $reloaded;
	global $wazzup;
	
	global $bans;
	global $categories;
	global $chats;
	global $exvars;
	global $places;
	global $polloptions;
	global $polls;
	global $profiles;
	global $pms;
	global $questions;
	global $searches;
	global $shoutbox;
	global $starring;
	global $shoutbox;
	global $templates;
	global $userbans;
	global $users;
	global $votes;
	global $images;
	
	global $water;
	
	$water->SetSetting( 'calltraces' , false ); // don't keep calltraces in this step; might get us out of memory

	?><b>Copying database table data</b>...<br />Copying bans...<?php
	flush();
	
	$reloaded->Query( "INSERT INTO `excalibur-sandbox`.`$bans`
							SELECT
								`id` AS ipban_id,
								`banip` AS ipban_ip,
								`bandate` AS ipban_date,
								`expiredate` AS ipban_expiredate,
								`sysopid` AS ipban_sysopid
							FROM
								`chitchat`.`$bans`;" );

	?>OK.<br />Copying categories...<?php
	flush();
	
	$res = $wazzup->Query( "SELECT
								`id` AS category_id,
								`creatoruserid` AS category_creatorid,
								`name` AS category_name,
								`description` AS category_description,
								`created` AS category_created,
								`parentcategoryid` AS category_parentid,
								`delid` AS category_delid,
								`icon` AS category_icon
							FROM 
								`$categories`;" );
	$rows = array();
	while ($row = $res->FetchArray() ) {
		$row[ 'category_name' ] = iconv( 'ISO-8859-7' , 'UTF-8' , $row[ 'category_name' ] );
		$row[ 'category_description' ] = iconv( 'ISO-8859-7' , 'UTF-8' , $row[ 'category_description' ] );
		$rows[] = $row;
	}
	$reloaded->Insert( $rows , array( 'excalibur-sandbox' , $categories ) );
	
	?>OK.<br />Copying chat entries...<?php
	flush();

	$res = $wazzup->Query( "SELECT
								`chat_id`,
								`chat_date`,
								`chat_userid`,
								`chat_message`,
								`chat_ip` AS chat_userip
							FROM
								`chitchat`.`$chats`;" );
	$rows = array();
	while ($row = $res->FetchArray() ) {
		$row[ 'chat_message' ] = iconv( 'ISO-8859-7' , 'UTF-8' , $row[ 'chat_message' ] );
		$rows[] = $row;
	}
	$reloaded->Insert( $rows , array( 'excalibur-sandbox' , $chats) );

	?>OK.<br />Copying exvars...<?php
	flush();
		
	$reloaded->Query( "INSERT INTO 
							`excalibur-sandbox`.`$exvars`
							SELECT 
								*
							FROM
								`chitchat`.`$exvars`;" );
	
	?>OK.<br />Copying images...<?php
	flush();

	$reloaded->Query( "INSERT INTO `excalibur-sandbox`.`$images`
							SELECT
								`id` AS image_id, 
								`userid` AS image_userid, 
								`submitdate` AS image_created, 
								`submithost` AS image_userip, 
								`name` AS image_name,
								'image/jpeg' AS image_mime
							FROM
								`chitchat`.`$images`;" );
								
	?>OK.<br />Copying places...<?php
	flush();
	
	$res = $wazzup->Query( "SELECT
								`id` AS place_id,
								`name` AS place_name,
								`x` AS place_x,
								`y` AS place_y,
								`updateuserid` AS place_updateuserid,
								`updatedate` AS place_updatedate,
								`updateip` AS place_updateip,
								'0' AS place_delid
							FROM
								`chitchat`.`$places`;" );

	$rows = array();
	while ( $row = $res->FetchArray() ) {
		$row[ 'place_name' ] = iconv( 'ISO-8859-7' , 'UTF-8' , $row[ 'place_name' ] );
		$rows[] = $row;
	}
	
	$reloaded->Insert( $rows, array( 'excalibur-sandbox', $places ) );
	
	?>OK.<br />Copying pms...<?php
	flush();
	
	$res = $reloaded->Query( "SELECT
								`id` AS pm_id,
								`from` AS pm_from,
								`to` AS pm_to,
								`submitdate` AS pm_created,
								`submithost` AS pm_userip,
								`text` AS pm_text,
								`delid` AS pm_delid
							FROM
								`chitchat`.`$pms`;" );
	
	$rows = array();
	while( $row = $res->FetchArray() ) {
		$row[ 'pm_text' ] = iconv( 'ISO-8859-7', 'UTF-8', $row[ 'pm_text' ] );
		$rows[] = $row;
	}
	
	$reloaded->Insert( $rows, array( 'excalibur-sandbox', $pms ) );
	
	?>OK.<br />Copying polloptions...<?php
	flush();
	
	$reloaded->Query( "INSERT INTO
							`excalibur-sandbox`.`$polloptions`
							SELECT 
								*
							FROM
								`chitchat`.`$polloptions`;" );
	
	?>OK.<br />Copying polls...<?php
	flush();
	
	$reloaded->Query( "INSERT INTO `excalibur-sandbox`.`$polls`
							SELECT
								`poll_id`,
								`poll_question`,
								`poll_userid`,
								`poll_expire`,
								`poll_storyid`,
								`poll_date` AS poll_created
							FROM
								`chitchat`.`$polls`;" );
	
	?>OK.<br />Copying profiles...<?php
	flush();
	
	$res = $wazzup->Query( "SELECT
								`id` AS profile_id,
								`userid` AS profile_userid,
								`answer` AS profile_answer,
								`questionid` AS profile_questionid,
								`date` AS profile_date,
								`deleted` AS profile_delid,
								`submithost` AS profile_userip
							FROM
								`chitchat`.`$profiles`;" );
	
	$rows = array();
	while ( $row = $res->FetchArray() ) {
		$row[ 'profile_answer' ] = iconv( 'ISO-8859-7' , 'UTF-8' , $row[ 'profile_answer' ] );
		$rows[] = $row;
	}
	$reloaded->Insert( $rows , array( 'excalibur-sandbox' , $profiles ) );
	
	?>OK.<br />Copying questions...<?php
	flush();
	
	$res = $wazzup->Query( "SELECT
								`id` AS profileq_id,
								`userid` AS profileq_userid,
								`date` AS profileq_created,
								`question` AS profileq_question,
								`submithost` AS profileq_userip,
								`deleted` AS profileq_delid
							FROM
								`$questions`;" );
								
	$rows = array();
	while ($row = $res->FetchArray() ) {
		$row[ 'profileq_question' ] = iconv( 'ISO-8859-7' , 'UTF-8' , $row[ 'profileq_question' ] );
		$rows[] = $row;
	}
	$reloaded->Insert( $rows , array( 'excalibur-sandbox', $questions ) );
								
	?>OK.<br />Copying search logs...<?php
	flush();
								
	$res = $wazzup->Query( "SELECT
								`search_id`,
								`search_date`,
								`search_host` AS search_userip,
								`search_userid`,
								`search_query`
							FROM
								`$searches`;" );
								
	$rows = array();
	while ($row = $res->FetchArray() ) {
		$row[ 'search_query' ] = iconv( 'ISO-8859-7' , 'UTF-8' , $row[ 'search_query' ] );
		$rows[] = $row;
	}
	$reloaded->Insert( $rows , array( 'excalibur-sandbox', $searches ) );
	
	?>OK.<br />Copying shoutbox..<?php
	flush();
	
	$res = $wazzup->Query( "SELECT
								`id` AS shout_id,
								`userid` AS shout_userid,
								`text` AS shout_text,
								`submitdate` AS shout_created,
								`submithost` AS shout_userip
							FROM
								`$shoutbox`;" );
	
	$rows = array();
	while( $row = $res->FetchArray() ) {
		$row[ 'shout_text' ] = iconv( 'ISO-8859-7', 'UTF-8', $row[ 'shout_text' ] );
		$rows[] = $row;
	}
	$reloaded->Insert( $rows , array( 'excalibur-sandbox', $shoutbox ) );
								
	?>OK.<br />Copying starring information...<?php
	flush();
	
	$reloaded->Query( "INSERT INTO `excalibur-sandbox`.`$starring`
							SELECT
								`starring_id`,
								`starring_userid`,
								`starring_commentid`,
								`starring_stars`,
								`starring_date`,
								`starring_ip` AS starring_userip
							FROM
								`chitchat`.`$starring`;" );
								
		
	?>OK.<br />Copying userbans...<?php
	flush();
	
	$reloaded->Query( "INSERT INTO
							`excalibur-sandbox`.`$userbans`
							SELECT 
								*
							FROM
								`chitchat`.`$userbans`" );
								
	?>OK.<br />Copying users...<?php
	flush();
	
	$res = $wazzup->Query( "SELECT 
								`id` AS user_id, 
								`name` AS user_name, 
								`password` AS user_password,
								`created` AS user_created,
								`registerhost` AS user_registerhost,
								`lastlogon` AS user_lastlogon,
								`rights` AS user_rights,
								`email` AS user_email,
								`signature` AS user_signature,
								`icon` AS user_icon,
								`gender` AS user_gender,
								`msn` AS user_msn,
								`yim` AS user_yim,
								`aim` AS user_aim,
								`icq` AS user_icq,
								`gtalk` AS user_gtalk,
								`dob` AS user_dob,
								`hobbies` AS user_hobbies,
								`subtitle` AS user_subtitle,
								`blogid` AS user_blogid,
								`place` AS user_place, 
								`inchat` AS user_inchat,
								`lastprofedit` AS user_lastprofedit,
								`starpoints` AS user_starpoints,
								`starpointsexpire` AS user_starpointsexpire,
								`locked` AS user_locked, 
								`lastactive` AS user_lastactive,
								0 AS user_templateid,
								'no' AS user_shoutboxactivated,
								0 AS user_contribs,
								0 AS user_respect
							FROM 
								`$users`;" );
								
	$rows = array();
	while ($row = $res->FetchArray() ) {
		$row[ 'user_signature' ] = iconv( 'ISO-8859-7' , 'UTF-8' , $row[ 'user_signature' ] );
		$row[ 'user_msn' ] = iconv( 'ISO-8859-7' , 'UTF-8' , $row[ 'user_msn' ] ); 
		$row[ 'user_yim' ] = iconv( 'ISO-8859-7' , 'UTF-8' , $row[ 'user_yim' ] );
		$row[ 'user_aim' ] = iconv( 'ISO-8859-7' , 'UTF-8' , $row[ 'user_aim' ] );
		$row[ 'user_icq' ] = iconv( 'ISO-8859-7' , 'UTF-8' , $row[ 'user_icq' ] );
		$row[ 'user_gtalk' ] = iconv( 'ISO-8859-7' , 'UTF-8' , $row[ 'user_gtalk' ] );
		$row[ 'user_hobbies' ] = iconv( 'ISO-8859-7' , 'UTF-8' , $row[ 'user_hobbies' ] );
		$row[ 'user_subtitle' ] = iconv( 'ISO-8859-7' , 'UTF-8' , $row[ 'user_subtitle' ] );
		$rows[] = $row;
	}
	$reloaded->Insert( $rows , array( 'excalibur-sandbox', $users ) );
								
							
	?>OK.<br />Copying votes...<?php
	flush();
	
	$reloaded->Query( "INSERT INTO
							`excalibur-sandbox`.`$votes`
							SELECT 
								*
							FROM
								`chitchat`.`$votes`" );							
	?>OK.<br /><?php

	return;
}

function Wazzup2Reloaded_Step3() {
	// copy merlin_logs table from wazzup to rldd
	global $reloaded;
	global $logs;
	
	?><b>Copying logs</b>...<br />Clearing existing logs...<?php
	flush();
	$reloaded->Query( "TRUNCATE TABLE 
							`excalibur-sandbox`.`$logs`" );
	?>OK.<br />Copying logs...<?php
	flush();
	
	$reloaded->Query( "INSERT INTO
							`excalibur-sandbox`.`$logs`
							SELECT 
								*
							FROM
								`chitchat`.`$logs`;" );
	
	?>OK.<br /><?php
	flush();

	return;
}

function Wazzup2Reloaded_Step4( $offset ) {
	// transform comments
	global $reloaded;
	global $wazzup;
	global $comments;
	global $water;
	
	$water->SetSetting( 'calltraces' , false ); // don't keep calltraces in this step; might get us out of memory; or out of time

	?><b>Transforming comments</b>...<br /><?php
	flush();
	
	if ( $offset == 0 ) {
		?>Clearing comments...<?php
		$reloaded->Query( "TRUNCATE TABLE 
								`excalibur-sandbox`.`$comments`" );
		?>OK.<br /><?php
	}
	
	?>Loading comments... <?php
	flush();
	$res = $wazzup->Query( "SELECT * FROM `$comments` WHERE `delid`='0' LIMIT " . $offset . ",3000" );

	?>OK.<br /><?php
	flush();
	
	$numrows = $res->NumRows();
	$inserts = array();
	while ( $row = $res->FetchArray() ) {
		$commentraw = iconv( 'ISO-8859-7', 'UTF-8', $row[ 'comment' ] );
		$commentsassoc = array(
			'comment_id'              => $row[ 'id' ],
			'comment_userid'          => $row[ 'userid' ],
			'comment_created'      	  => $row[ 'submitdate' ],
			'comment_userip'      	  => $row[ 'submithost' ],
			'comment_text'         	  => '',
			'comment_textraw'      	  => $commentraw,
			'comment_storyid'         => $row[ 'storyid' ],
			'comment_parentid' 		  => $row[ 'parentcommentid' ],
			'comment_delid'           => $row[ 'delid' ],
			'comment_stars'           => $row[ 'stars' ],
			'comment_votes'           => $row[ 'votes' ],
			'comment_typeid'          => 0
		);
		$inserts[] = $commentsassoc;
	}
	
	if ( count( $inserts ) ) {
		$reloaded->Insert( $inserts , array( 'excalibur-sandbox' , $comments ) );
	}
	
	return $numrows;
}

function Wazzup2Reloaded_Step5() {
	// convert from stories to articles/revisions/bulk
	global $reloaded;
	global $wazzup;
	
	global $revisions;
	global $bulk;
	global $articles;
	global $comments;
	global $users;
	global $logs;
	global $pageviews;
	
	global $water;
	
	$water->SetSetting( 'calltraces'  , false ); // don't keep calltraces in this step; might get us out of memory; or out of time

	?><b>Transforming articles graph</b>...<br />Truncating...<?php
	flush();
	
	$reloaded->Query( "TRUNCATE `excalibur-sandbox`.`$articles`" );
	$reloaded->Query( "TRUNCATE `excalibur-sandbox`.`$bulk`" );
	$reloaded->Query( "TRUNCATE `excalibur-sandbox`.`$revisions`" );
	$reloaded->Query( "DELETE FROM 
							`excalibur-sandbox`.`$pageviews`
						WHERE
							`pageview_type` = 'article'" );
	
	?>Loading articles...<?php
	flush();

	$sql = "SELECT
				*
			FROM
				`merlin_stories`;";
	$res = $wazzup->Query( $sql );
	?>OK.<br />Trasnsferring buffer into memory...<?php
	flush();
	
	$stories = array();
	while ( $row = $res->FetchArray() ) {
		$stories[ $row[ 'id' ] ] = $row;
	}
 
	w_assert( count( $stories ) > 0 );
 
	?>OK.<br />Creating reverted copy of linked list...<?php
	flush();
	
	// loop through the linked lists and create backward pointers
	foreach ( $stories as $story ) {
		if ( $story[ 'delid' ] > 0 ) { // intermediate revision
			if ( !isset( $stories[ $story[ 'delid' ] ] ) ) {
				die( 'Non-existing pointer in intermediate revision; corrupted database? On storyid ' . $story[ 'delid' ] );
			}
			$stories[ $story[ 'delid' ] ][ 'parentrevision' ] = $story[ 'id' ];
		}
	}
 
	?>OK. <br />Listing first revisions...<br /><?php
	flush();
	
	$firstrevisions = array();
	foreach ( $stories as $story ) {
		if ( !isset( $story[ 'parentrevision' ] ) ) {
			$firstrevisions[] = $story[ 'id' ];
		}
	}
	  
	w_assert( count( $firstrevisions ) > 0 );
	 
	$articleid = 0;
	 
	foreach ( $firstrevisions as $article ) {
		$oldstories = array();
		++$articleid;
		?>Creating article <?php
		echo $articleid;
		?>...<?php
		flush();
		$rootrevision = $currentrevision = $article;
		$revisionid = 0;
		while ( $currentrevision > 0 ) {
			$oldstories[] = $currentrevision;
			++$revisionid;
			$text = iconv( 'ISO-8859-7', 'UTF-8', $stories[ $currentrevision ][ 'story' ] );
			$change = $reloaded->Insert( array( 'bulk_text' => $text ), $bulk );
			w_assert( $change->Impact() );
			$bulkid = $change->InsertId();
			w_assert( is_numeric( $bulkid ) );
			$sqlarray = array(
				'revision_articleid' => $articleid,
				'revision_id' => $revisionid,
				'revision_title' => iconv( 'ISO-8859-7' , 'UTF-8' , $stories[ $currentrevision ][ 'title' ] ),
				'revision_textid' => $bulkid,
				'revision_updated' => $stories[ $currentrevision ][ 'submitdate' ],
				'revision_creatorid' => $stories[ $currentrevision ][ 'userid' ],
				'revision_creatorip' => $stories[ $currentrevision ][ 'submithost' ],
				'revision_minor' => $stories[ $currentrevision ][ 'minor' ],
				'revision_iconid' => $stories[ $currentrevision ][ 'icon' ],
				'revision_categoryid' => $stories[ $currentrevision ][ 'categoryid' ],
				'revision_showemoticons' => 'yes'
			);
			$change = $reloaded->Insert( $sqlarray, $revisions );
			w_assert( $change->Impact() );
			$lastrevision = $currentrevision;
			$currentrevision = $stories[ $currentrevision ][ 'delid' ];
		}
		$typeid = ( $stories[ $rootrevision ][ 'actualstory' ] == 'yes' ) ? 0 : 2;
		$sqlarray = array(
			'article_id' => $articleid,
			'article_creatorid' => $stories[ $rootrevision ][ 'userid' ],
			'article_headrevision' => $revisionid,
			'article_created' => $stories[ $rootrevision ][ 'submitdate' ],
			'article_delid' => $currentrevision,
			'article_typeid' => $typeid
		);
		$reloaded->Insert( $sqlarray, $articles );
		w_assert( $change->Impact() );
		
		$sql = "UPDATE 
					`$comments` 
				SET 
					`comment_storyid` = '$articleid' 
				WHERE 
					`comment_storyid` IN (" . implode( ', ' , $oldstories ) . ");";
		$change = $reloaded->Query( $sql );
		
		$sql = "UPDATE
					`$articles`
				SET
					`article_numcomments` = '" . $change->AffectedRows() . "'
				WHERE
					`article_id` = '" . $articleid . "'
				LIMIT 1;";
		$reloaded->Query( $sql );
		
		$sql = "UPDATE `$users` SET `user_blogid` = '$articleid' WHERE `user_blogid` IN (" . implode( ', ' , $oldstories ) . ");";
		$reloaded->Query( $sql );
		
		$loglike = array();
		foreach ($oldstories as $oldstory) {
			$loglike[] = "`log_requesturi` LIKE '%?p=story&id=" . $oldstory . "%'";
		}
		
		/*
		$sql = "INSERT INTO
					`$pageviews`
				SELECT
					'article' AS pageview_type, 
					'$articleid' AS pageview_itemid, 
					`log_userid` AS pageview_userid, 
					`log_date` AS pageview_date
				FROM
					`$logs`
				WHERE
					" . implode( ' OR ' , $loglike );
		$reloaded->Query( $sql );
		*/
		?>OK.<br /><?php
		flush();
	}
	
	return;
}

function Wazzup2Reloaded_Step6( $offset ) {
	global $comments;
	global $reloaded;
	
	global $water;
	
	$water->SetSetting( 'calltraces' , false ); // don't keep calltraces in this step; might get us out of memory; or out of time

	$sql = "SELECT `comment_id`, `comment_textraw` FROM `$comments` LIMIT $offset,3000;";
	$res = $reloaded->Query( $sql );
	
	$numrows = 0;
	while ( $row = $res->FetchArray() ) {
		$commentid = $row[ 'comment_id' ];
		$commentraw = $row[ 'comment_textraw' ];
		$comment = addslashes( mformatcomment( $commentraw ) );
		$sql = "UPDATE 
					`$comments` 
				SET 
					`comment_text` = '$comment' 
				WHERE 
					`comment_id`='$commentid' 
				LIMIT 1;";
		$reloaded->Query( $sql );
		++$numrows;
	}
	
	return $numrows;
}

function Wazzup2Reloaded_Step7() {
	// set user contributions and respect
	global $reloaded;
	global $comments;
	global $users;
	global $water;
	global $pageviews;
	global $logs;
	
	$water->SetSetting( 'calltraces' , true );
	$water->SetSetting( 'loglevel' , 2 ); // notices and up
	$water->SetSetting( 'loglimit' , 10 );
	
	$reloaded->Query( "DELETE FROM 
							`excalibur-sandbox`.`$pageviews`
						WHERE
							`pageview_type` = 'user'" );

	$sql = "SELECT 
					`comment_userid`,
					COUNT(*) AS commentscount 
				FROM 
					`$comments` 
				WHERE 
					`comment_delid`='0'
				GROUP BY
					`comment_userid`;";
					
	$res = $reloaded->Query( $sql );
	
	while( $row = $res->FetchArray() ) {
		$userid = $row[ 'comment_userid' ];
		$commentscount = $row[ 'commentscount' ];
		
		$sql = "UPDATE `$users` SET `user_contribs` = '$commentscount' WHERE `user_id` = '$userid' LIMIT 1;";
		$reloaded->Query( $sql );
	}
	
	$sql = "SELECT 
					`comment_userid`, SUM( `comment_stars` / `comment_votes` ) AS respect
				FROM 
					`$comments` 
				WHERE 
					`comment_delid`='0' AND
					`comment_votes`!='0'
				GROUP BY 
					`comment_userid`;";
					
	$res = $reloaded->Query( $sql );
	
	while( $row = $res->FetchArray() ) {
		$userid = $row[ 'comment_userid' ];
		$respect = $row[ 'respect' ];
		
		$sql = "UPDATE `$users` SET `user_respect` = '$respect' WHERE `user_id` = '$userid' LIMIT 1;";
		$reloaded->Query( $sql );
	}
	
	/*
	UPDATE
		`$users` INNER JOIN `$comments`
			ON `comment_userid` = `user_id`
	SET
		`user_respect` = SUM( `comments_stars` / `comment_votes` ),
	WHERE
		`comment_delid`='0' AND
		`comment_votes`!='0'
	GROUP BY
		`comment_userid`;
		
		I don't know if it's possible, but if it is, it should optimize this piece of code quite a bit;
		we should try it out --dionyziz.
	*/

	/* $sql = "INSERT INTO
				`$pageviews`
			SELECT
				'user' AS pageview_type, 
				'' AS pageview_itemid, 
				`log_userid` AS pageview_userid, 
				`log_date` AS pageview_date */
				
	/*
	$sql = "SELECT
				`log_date`, `log_userid`, `log_requesturi`
			FROM
				`$logs`
			WHERE
				`log_requesturi` LIKE '%?p=user&id=%'";
	$res = $reloaded->Query( $sql );
	
	$inserts = array();
	while ( $row = $res->FetchArray() ) {
		preg_match( '#\?\=user&id\=([0-9]+)#' , $row[ 'log_requesturi' ] , $matches );
		if ( isset( $matches[ 1 ] ) && is_numeric( $matches[ 1 ] ) ) {
			$inserts[] = array(
							'pageview_type' => 'user',
							'pageview_itemid' => $matches[ 1 ],
							'pageview_userid' => $row[ 'log_userid' ],
							'pageview_date' => $row[ 'log_date' ]
						 );
		}
	}
	$reloaded->insert( $inserts , $pageviews );
	*/
}

function rmdirr( $dir , $contentsonly = false ) {
	if (substr($dir, -1) != "/") {
		$dir .= "/";
	}
	if ( !is_dir( $dir ) ) {
		return false;
	}
	
	if ( ( $dh = opendir( $dir ) ) !== false ) {
		while ( ( $entry = readdir( $dh ) ) !== false ) {
			if ( $entry != '.' && $entry != '..' ) {
				if ( is_file( $dir . $entry ) || is_link( $dir . $entry ) ) {
					unlink( $dir . $entry );
				}
				else if ( is_dir( $dir . $entry ) ) {
					rmdirr( $dir . $entry );
				}
			}
		}
		closedir( $dh );
		if ( !$contentsonly ) {
			rmdir($dir);
		}
		
		return true;
	}
	return false;
}

?>
