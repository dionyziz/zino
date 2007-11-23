<?php
	return;
	
	set_include_path( '../:./' );
	
	global $page;
	global $libs;
	global $xc_settings;
    
	require_once 'libs/rabbit/rabbit.php';
    Rabbit_Construct( 'HTML' );
    $req = $_GET;
    
	Rabbit_ClearPostGet();
	
	$libs->Load( 'comment' );
	
    global $db;
    
    $ids = array( 3877, 3878, 3879, 3880, 3881, 3882, 3883, 3884, 3885, 3887, 3888, 3893, 3894, 3895, 3896, 3897, 3898, 3899 );
	
	$header = "GET " . $xc_settings[ 'imagesupload' ][ 'url' ] . " HTTP/1.1\r\n"
	. "Host: images.chit-chat.gr\r\n"
	. "Connection: close\r\n\r\n";
	
	fputs( fp, $header );
	
	$data = '';
	while ( !feof( $fp ) ) {
		$data .= @fgets( $fp, 1024 );
	}
	
	fclose( $fp );
	
    $page->Output();

    Rabbit_Destruct();
	
?>
