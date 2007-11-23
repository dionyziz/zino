<?php
	return;
	
	set_include_path( '../:./' );
	
	global $page;
	global $libs;
    global $user;
    
	require_once 'libs/rabbit/rabbit.php';
    Rabbit_Construct( 'HTML' );
    $req = $_GET;
    
	Rabbit_ClearPostGet();
	
	$libs->Load( 'comment' );

    if ( !$user->IsSysOp() ) {
        die( "Please fuck off" );
    }
	
	echo "first comment...";
	
	$id = MakeComment( "foo bar start", 0, 254, 0 );
	echo "OK<br />";
	
	for ( $i = 0; $i < 99; ++$i ) {
		echo "writing comment $i...";
		$id = MakeComment( "foo bar $i", $id, 254, 0 );
		echo "OK<br />";
	}
	
    $page->Output();

    Rabbit_Destruct();
	
?>
