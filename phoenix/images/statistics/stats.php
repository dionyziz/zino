<?php
	set_include_path( '../../:./' );
	require_once 'libs/rabbit/rabbit.php';
	Rabbit_Construct( 'plain' );

	global $libs;
	global $user;

	if ( !$user->HasPermission( PERMISSION_STATISTICS_VIEW ) ) {
	return;
	}

	$libs->Load( 'libchart/classes/libchart' );
	$libs->Load( 'statistics' );

	$ttle = "";
	
	switch ( $_GET['days'] ) {
	case 30: $x = 500;
		  break;
	case 60: $x = 750;
		  break;
	case 90: $x = 1000;
		  break;
	default: $_GET['days'] = 30;
		 $x = 500;
	}

	$stat = Statistics_Get($_GET[ 'name' ],$_GET['days']);
	$title = 'new '.$_GET[ 'name' ].' per day';	

	$chart=new LineChart( $x, $x-250 );	//1000 for 90 days,750 for 60 days 500 for 30 days		
	$dataSet=new XYDataSet();	
	
	$i=0;
	$lastday="";
	$lastmonth;
	foreach ( $stat as $row ) {	
		if ( $i % 10 == 0 ) {
			$date=new DateTime( $row[ 'day' ] );
			$label=$date->format( 'm-d' );
		}
		else {
			$label="";
			$date=new DateTime( $row[ 'day' ] );
		}

		if ( $lastday != "")
		{
			$empty_days=(int)$date->format( 'd' ) - $lastday + ( (int)$date->format( 'm' ) - $lastmonth ) * 30 - 1;
			for( $e=0; $e<$empty_days; $e++)
			$dataSet->addPoint( new Point( "", 0 ) ); 

		}
		
		$dataSet->addPoint( new Point( $label, $row[ 'count' ] ) ); 

		++$i;
	
		$lastday = (int)$date->format( 'd' );
		$lastmonth = (int)$date->format( 'm' );
	}
	$empty_days = (int)date( 'd' ) - $lastday + ( (int)date( 'm' ) - $lastmonth ) * 30 - 1;
	for ( $e=0; $e<$empty_days; $e++)
	$dataSet->addPoint( new Point( "", 0 ) ); 
	

	$chart->setDataSet( $dataSet );
	$chart->SetTitle( $title );

	header( 'Content-type: image/png' );
	$chart->render();
			
	Rabbit_Destruct();
?>
