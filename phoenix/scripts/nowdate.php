<?php
	set_include_path( '../:./' );
	
	global $page;
	global $libs;
    global $user;
    
	require_once 'libs/rabbit/rabbit.php';
    Rabbit_Construct( 'HTML' );
    $req = $_GET;
    
	Rabbit_ClearPostGet();

    global $db;

    ob_start();

    ?>PHP Time: <?php
    echo NowDate();
    ?><br />
    MySQL Time: <?php
    
    $sql = "SELECT NOW();";

    $fetched = $db->Query( $sql )->FetchArray();

    echo $fetched[ "NOW()" ];

    ?><br /><?php
    
    Rabbit_Destruct();
	
?>
