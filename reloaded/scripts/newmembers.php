<?php
	return;
	
	global $water;
	global $users;

	include "../header.php";
	include "../libs/graph.php";

	$wazzup = New Database( 'chitchat' );
	$wazzup->Connect( 'localhost' );
	$wazzup->Authenticate( 'chitchat' , '7paz?&aS' );
	
    $sql = 'SELECT
                UNIX_TIMESTAMP() AS nowstamp';
                
    $res = $db->Query( $sql );
    $row = $res->FetchArray();
    $nowstamp = $row[ 'nowstamp' ];
    
	$sql = "SELECT
				COUNT(*) AS newusers,
				DATE( `user_created` ) AS day,
                UNIX_TIMESTAMP( `user_created` ) AS diffseconds
			FROM
				`$users`
			WHERE
				`user_created` >= NOW() - INTERVAL 3 MONTH
			GROUP BY
				day
			ORDER BY
				day ASC
			";

	$days = array();
	$res = $wazzup->Query( $sql );
	for ( $i = 0; $i < 92; ++$i ) {
		$days[ 91 - $i ] = 0;
	}
    $nowday = floor( $nowstamp / ( 24 * 60 * 60 ) );

	while ( $row = $res->FetchArray() ) {
        $numdays = $nowday - floor( $row[ 'diffseconds' ] / ( 24 * 60 * 60 ) );
		$days[ $numdays ] = $row[ 'newusers' ];
	}
        
	header( 'Content-type: image/png' );
	
	$smooth = isset( $_GET[ 'smoothing' ] ) ? $_GET[ 'smoothing' ] : 0;
	$size = isset( $_GET[ 'size' ] ) ? $_GET[ 'size' ] : 1;
	
	switch ( $size ) {
		case 2:
			$width = 800;
			$height = 400;
			break;
		case 0:
			$width = 400;
			$height = 200;
			break;
		case 1:
		default:
			$width = 600;
			$height = 300;
			break;
	}
	
	$graph = New Graph( "Comments" );
	$graph->SetData( $days );
	$graph->SetSize( $width, $height );
	$graph->SetTime( $numdays );
	$graph->SetSmoothing( $smooth );
	$graph->Render();
    
	// $water->GenerateJS();
?>
