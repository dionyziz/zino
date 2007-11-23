<?php
	set_include_path( '../../:./' );
	
	global $water;
	global $libs;
    global $db;
    
	require 'header.php';

	$libs->Load( 'graph' );
	
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
	
	$sql = "SELECT
				COUNT( * ) AS numcomments, 
				DATE( `comment_created` ) AS date
			FROM
				`$comments`
			WHERE
				`comment_created` > NOW() - INTERVAL 120 DAY
			GROUP BY
				date
			ORDER BY
				date ASC
			";

	$days = array();
	$res = $livedb->Query( $sql );
	while ( $row = $res->FetchArray() ) {
		$days[] = $row[ 'numcomments' ];
	}
	
	$smooth = isset( $_GET[ 'smoothing' ] ) ? $_GET[ 'smoothing' ] : 3;
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
	$graph->SetTime( 120 );
	$graph->SetSmoothing( $smooth );
	$graph->Render();
	
    // ob_get_clean();
    
	//$water->GenerateJS();
?>

