<?php
	set_include_path( '../:./' );
	
	require '../libs/rabbit/rabbit.php';
	
    Rabbit_Construct();
		
	$water->Enable(); // on for all

    global $db, $places;

    $res = $db->Query(
        "SELECT 
            `place_id`, `place_name`, `place_x`, `place_y`, `place_delid`
        FROM 
            $places;" );

    header( 'Content-type: text/html; charset=utf8' );

    ?>INSERT INTO `places` VALUES <?php
    $inserts = array();
    while ( $row = $res->FetchArray() ) {
        $fields = array();
        foreach ( $row as $field ) {
            $fields[] = "'" . addslashes( $field ) . "'";
        }
        $inserts[] = '(' . implode( ',', $fields ) . ')';
    }
    echo implode( ',', $inserts );
?>
