<?php
	set_include_path( '../:./' );
	
	global $page;
	global $libs;
    global $user;
    
	require_once 'libs/rabbit/rabbit.php';
    Rabbit_Construct( 'HTML' );
    $req = $_GET;
    
	Rabbit_ClearPostGet();

    if ( !$user->CanModifyCategories() ) {
        return false;
    }
	
    global $db;
    global $votes;
    
    ob_start();

    $sql = "SELECT
                `vote_userid`, `vote_pollid` AS optionid, `polloption_pollid`
            FROM 
                `$votes` LEFT JOIN `$polloptions`
                    ON `polloption_id` = `vote_pollid`
            ;";

    $res = $db->Query( $sql );

    while ( $row = $res->FetchArray() ) {
        $sql = "UPDATE
                    `$votes`
                SET
                    `vote_pollid` = '" . $row[ 'polloption_pollid' ] . "'
                WHERE
                    `vote_userid` = '" . $row[ 'vote_userid' ] . "' AND
                    `vote_pollid` = '" . $row[ 'optionid' ] . "'
                LIMIT 1;";

        $db->Query( $sql );

        echo "Updated poll with userid = '" . $row[ 'vote_userid' ] . "' AND pollid = '" . $row[ 'optionid' ];
    }

    Rabbit_Destruct();
	
?>
