<?php
	return;
	
	set_include_path( '../:./' );
	
	global $page;
	global $libs;
    
	require_once 'libs/rabbit/rabbit.php';
    Rabbit_Construct( 'HTML' );
    $req = $_GET;
    
	Rabbit_ClearPostGet();
	
	$libs->Load( 'comment' );
	
	function GetAllUserIds() {
		global $db;
		global $users;
		
	    $sql = "SELECT `user_id` FROM `$users`;";
	    $res = $db->Query( $sql );
	    
	    $ret = array();
	    while ( $row = $res->FetchArray() ) {
	        $ret[] = $row[ "user_id" ];
	    }
		
		return $ret;
	}
	
	function FindStatistics( $userid ) {
		global $db;
		global $pageviews;
		global $shoutbox;
		global $images;
		
		$sql = "SELECT 
							COUNT(*) AS profviews
						FROM 
							`$pageviews`
						WHERE 
							`pageview_type`='user' 
							AND `pageview_itemid`='$userid'";
		
		$fetched = $db->Query( $sql )->FetchArray();
		$profviews = $fetched[ "profviews" ];
				
		$sql = "SELECT
						COUNT( * ) AS numsmallnews
					FROM 
						`$shoutbox` 
					WHERE 
						`shout_userid` = '$userid' AND 
						`shout_delid` = '0'
				;";
		$fetched = $db->Query( $sql )->FetchArray();
		$numsmallnews = $fetched[ "numsmallnews" ];
		
		
		$sql = "SELECT
						COUNT( * ) AS numimages
					FROM 
						`$images` 
					WHERE
						`image_userid` = '$userid'
				;";
		$fetched = $db->Query( $sql )->FetchArray();
		$numimages = $fetched[ "numimages" ];
		
		return array( 
			"pageviews" => $profviews, 
			"smallnews" => $numsmallnews, 
			"images" 	=> $numimages 
		);
	}
	
	function UpdateStatistics( $userid, $profviews, $numsmallnews, $numimages ) {
		global $db;
		global $users;
		
		$sql = "UPDATE `$users` SET `user_profviews` = '$profviews', `user_numsmallnews` = '$numsmallnews', `user_numimages` = '$numimages' WHERE `user_id` = '$userid' LIMIT 1;";
		
		$change = $db->Query( $sql );
		
		return $change->Impact();
	}
    
	// get all user ids
	$userids = GetAllUserIds();

	// for each user id
    foreach ( $userids as $i ) {
        echo "User $i: ";

		// find statistics
		$statistics = FindStatistics( $i );
		
		echo $statistics[ "pageviews" ] . " " . $statistics[ "smallnews" ] . " " . $statistics[ "images" ] . " .....";

		// update db
		$action = UpdateStatistics( $i, $statistics[ "pageviews" ], $statistics[ "smallnews" ], $statistics[ "images" ] );
		
		if ( $action === true ) {
			echo "DONE";
		}
		else {
			echo "NO IMPACT";
		}
		
		echo "<br />";

        ob_flush();
    }

    $page->Output();

    Rabbit_Destruct();
	
?>
