<?php
	return;
	
	set_include_path( '../:./' );
	
	global $page;
	global $libs;
    
	require_once 'libs/rabbit/rabbit.php';
    Rabbit_Construct( 'HTML' );
    $req = $_GET;
    
	Rabbit_ClearPostGet();
	
	$libs->Load( 'shoutbox' );
	
    global $db;
    
    $sql = "SELECT `shout_id` AS id, `shout_text` AS text FROM `merlin_shoutbox`;";
    $res = $db->Query( $sql );
	
	$shouts = array();
	while ( $row = $res->FetchArray() ) {
		$shouts[ $row[ "id" ] ] = $row[ "text" ];
	}
	
	$formatted = mformatshouts( $shouts );
	
	foreach ( $formatted as $id => $text ) {
		global $db;
		
		echo "Shout $i ....";
		
		$text = myescape( $text );
		
		$sql = "UPDATE `merlin_shoutbox` SET `shout_textformatted` = '$text' WHERE `shout_id` = '$id' LIMIT 1;";
		echo( $sql );
		$change = $db->Query( $sql );
	
		if ( $change->Impact() ) {
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
