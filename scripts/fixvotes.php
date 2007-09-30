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
                `vote_userid`, `vote_optionid`, `polloption_pollid`
            FROM 
                `$votes` LEFT JOIN `$polloptions`
                    ON `polloption_id` = `vote_optionid`
            ;";

    $res = $db->Query( $sql );

    while ( $row = $res->FetchArray() ) {
        $sql = "UPDATE
                    `$votes`
                SET
                    `vote_pollid` = '" . $row[ 'polloption_pollid' ] . "'
                WHERE
                    `vote_userid` = '" . $row[ 'vote_userid' ] . "' AND
                    `vote_optionid` = '" . $row[ 'vote_optionid' ] . "'
                LIMIT 1;";

        $db->Query( $sql );

        echo "Updated poll with userid = '" . $row[ 'vote_userid' ] . "' AND optionid = '" . $row[ 'vote_optionid' ] . "'<br />";

        ob_flush();
    }

    Rabbit_Destruct();
	
?>
