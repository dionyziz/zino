<?php
	set_include_path( '../../:./' );
	include 'models/db.php';
	include 'models/user.php';

	if ( !isset( $_SESSION[ 'user' ] ) ) {
		die( "not logged in" );
	}
	$user = User::Item( $_SESSION[ 'user' ][ 'id' ] );
	if ( $user[ 'rights' ] < 60 ) {
		die( "No rights" );
	}
		

	include 'etc/libchart/libchart.php';
	$chart = new VerticalBarChart(500, 250);
	$dataSet = new XYDataSet();
	$dataSet->addPoint(new Point("Jan 2005", 273));
	$dataSet->addPoint(new Point("Feb 2005", 321));
	$dataSet->addPoint(new Point("March 2005", 442));
	$dataSet->addPoint(new Point("April 2005", 711));
	$chart->setDataSet($dataSet);
	$chart->setTitle("Monthly usage for www.example.com");
	
	header( 'Content-type: image/png' );
	$chart->render("generated/demo1.png");
?>
