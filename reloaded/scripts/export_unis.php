<?php
	set_include_path( '../:./' );
	
	require '../libs/rabbit/rabbit.php';
	
    Rabbit_Construct();
		
	$water->Enable(); // on for all

    global $db, $universities;

    $res = $db->Query(
        "SELECT 
            `uni_id`, `uni_name`, `uni_typeid`, `uni_placeid`, `uni_createdate`, `uni_delid`
        FROM 
            $universities;" );

    header( 'Content-type: text/html; charset=utf8' );

    ?>INSERT INTO `universities` VALUES <?php
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
