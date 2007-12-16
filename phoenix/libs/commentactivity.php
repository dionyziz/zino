<?php

function CommentActivity_Increase( $userid, $typeid, $storyid, $commentid ) {
	global $db;
	global $comments;
	global $comments_queue;
	
	$water->Profile( 'CommentActivity_Increase' );
	
	$theuser = New User( array( 'user_id' => $userid ) );

	// Prepared query
	$query = $db->Prepare("
		SELECT
			`cq_created`
		FROM
			`$comments_queue` CROSS JOIN `$comments`
				ON `cq_commentid` = `comment_id`
		WHERE
			`comment_userid`	= :UserId AND
			`comment_typeid` 	= :TypeId AND
			`comment_storyid` 	= :StoryId
		ORDER BY 
			`cq_created` DESC
		LIMIT :Limit
		;
	");

	// Assign query values
	$query->Bind( 'UserId' , $userid );
	$query->Bind( 'TypeId' , $typeid );
	$query->Bind( 'StoryId', $storyid );
	$query->Bind( 'Limit', 1 );
	
	$res = $query->Execute();
	$numdifferentcomments = 1 - $res->NumRows();
	$count = 1;

	$theuser->UpdateScore( $count, $numdifferentcomments );
	
	$lastdate = "0000-00-00 00:00:00";
	
	if ( $res->NumRows() > 0 ) {
		$row = $res->FetchArray();
		$lastdate = $row[ 'cq_created' ];
	}
	
	$change = $db->Insert( array(
					'cq_commentid' => $commentid,
					'cq_created' => NowDate(),
					'cq_lastsimilardate' => $lastdate
				 ) , $comments_queue );
	
	$water->EndProfile();

	return $change->Impact();
}

function CommentActivity_Decrease() {
	global $db;
	global $comments;
	global $comments_queue;
	global $water;
	
	$water->Profile( 'CommentActivity_Decrease' );
	
	$sql = "SELECT
			`comment_id`, `comment_userid`, (`cq_lastsimilardate` + INTERVAL 7 DAY < NOW()) AS unique
		FROM
			`$comments_queue` CROSS JOIN `$comments`
				ON `cq_commentid` = `comment_id`
		WHERE
			`cq_created` < NOW() - INTERVAL 7 DAY;";

	$uniquecomments = array();
	$commentscounts = array();
	$res = $db->Query( $sql );
	$numcomments = $res->NumRows();
	while ( $row = $res->FetchArray() ) {
		if ( !isset( $uniquecomments[ $row[ 'comment_userid' ] ] ) ) {
			$uniquecomments[ $row[ 'comment_userid' ] ] = $row[ 'unique' ];
		}
		else {
			$uniquecomments[ $row[ 'comment_userid' ] ] += $row[ 'unique' ];
		}
		if ( !isset( $commentscounts[ $row[ 'comment_userid' ] ] ) ) {
			$commentscounts[ $row[ 'comment_userid' ] ] = 1;
		}
		else {
			++$commentscounts[ $row[ 'comment_userid' ] ];
		}
	}

	foreach ( $commentscounts as $userid => $count ) {
		$numdifferentcomments = $uniquecomments[ $userid ];
		$theuser = New User( array( 'user_id' => $userid ) );
		$theuser->UpdateScore( -$count, -$numdifferentcomments );
	}

	$sql = "DELETE FROM
				`$comments_queue`
		   ORDER BY
				`cq_commentid` ASC
		   LIMIT $numcomments;";
		   
	$change = $db->Query( $sql );

	$water->EndProfile();
	
	return $change->AffectedRows();
}

?>
