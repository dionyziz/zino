<?php

	set_include_path( '../:./' );
	
	global $water;
    global $db;
	global $page;
	
	require '../libs/rabbit/rabbit.php';
	
    Rabbit_Construct();
		
	$water->Enable(); // on for all

	header( 'Content-Type: text/html; charset=utf-8' );
	
    $sql = "UPDATE
                `$users`
            SET
                `user_rights` = 20
            WHERE
                `user_rights` < 20 AND
                `user_contribs` > 50 AND
                `user_created` < NOW() - INTERVAL 7 DAY
            ;";
