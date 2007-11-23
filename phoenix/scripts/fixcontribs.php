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
	
    global $db;
    
    $sql = "SELECT `user_id` FROM `merlin_users`;";
    $res = $db->Query( $sql );
    
    $ret = array();
    while ( $row = $res->FetchArray() ) {
        $ret[] = $row[ "user_id" ];
    }

    foreach ( $ret as $i ) {
        echo "User $i: ";

        $sql = "SELECT COUNT( * ) AS contribs FROM `merlin_comments` WHERE `comment_userid` = '$i' AND `comment_delid` = '0';";
        $fetched = $db->Query( $sql )->FetchArray();
        $contribs = $fetched[ "contribs" ];

        echo $contribs;

        $sql = "UPDATE `merlin_users` SET `user_contribs` = '$contribs'  WHERE `user_id` = '$i' LIMIT 1;";
        $change = $db->Query( $sql );

        echo " .." . ( $change->Impact() ? "OK" : "NO IMPACT" ) . "<br />";

        ob_flush();
    }

    $page->Output();

    Rabbit_Destruct();
	
?>
