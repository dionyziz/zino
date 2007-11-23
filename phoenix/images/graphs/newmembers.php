<?php
	global $water;
	global $users;

	include "../../header.php";
	include "../../libs/graph.php";

	
	if ( false ) { // for now
			header( 'HTTP/1.1 304 Not Modified' );
	}
		
	header( 'Cache-Control: Public' );
	header( "Expires: " . gmdate( "D, d M Y H:i:s", time() + 60 * 60 * 24 * 7 ) . " GMT" );
	header( "Last-Modified: " . gmdate( "D, d M Y H:i:s", time() - 60 * 60 * 24 * 7 ) . " GMT" );
	header( "Pragma: " );
	header( "Content-Type: image/png" );
	
    $livedb = New Database( 'excalibur-sandbox' );
	$livedb->Connect( 'localhost' );
	$livedb->Authenticate( 'excalibursandbox' , 'viuhluqouhoa' );
	$livedb->SetCharset( 'DEFAULT' );
	
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
	$res = $livedb->Query( $sql );
	for ( $i = 0; $i < 92; ++$i ) {
		$days[ 91 - $i ] = 0;
	}
    $nowday = floor( $nowstamp / ( 24 * 60 * 60 ) );

	while ( $row = $res->FetchArray() ) {
        $numdays = $nowday - floor( $row[ 'diffseconds' ] / ( 24 * 60 * 60 ) );
		$days[ $numdays ] = $row[ 'newusers' ];
	}
	
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
	
	$graph = New Graph( "New Members" );
	$graph->SetData( $days );
	$graph->SetSize( $width, $height );
	$graph->SetTime( $numdays );
	$graph->SetSmoothing( $smooth );
	$graph->Render();
?>
