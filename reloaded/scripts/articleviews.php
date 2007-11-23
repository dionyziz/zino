<?php
	set_include_path( '../:./' );
	
	global $page;
	global $libs;
    
	require_once 'libs/rabbit/rabbit.php';
    Rabbit_Construct( 'HTML' );
    $req = $_GET;
    
	Rabbit_ClearPostGet();
	
	$libs->Load( 'comment' );
	
    global $db;
    
    $sql = "SELECT `article_id` FROM `merlin_articles`;";
    $res = $db->Query( $sql );
    
    $ret = array();
    while ( $row = $res->FetchArray() ) {
        $ret[] = $row[ "article_id" ];
    }

    foreach ( $ret as $i ) {
        echo "Article $i: ";

        $sql = "SELECT COUNT( * ) AS pageviews FROM `merlin_pageviews` WHERE `pageview_type` = 'article' AND `pageview_itemid` = '$i';";

        $fetched = $db->Query( $sql )->FetchArray();
        $pageviews = $fetched[ "pageviews" ];

        echo $pageviews;

        $sql = "UPDATE `merlin_articles` SET `article_numviews` = '$pageviews'  WHERE `article_id` = '$i' LIMIT 1;";
        $change = $db->Query( $sql );

        echo " .." . ( $change->Impact() ? "OK" : "NO IMPACT" ) . "<br />";

        ob_flush();
    }

    $page->Output();

    Rabbit_Destruct();
	
?>
