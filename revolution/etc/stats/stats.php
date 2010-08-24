<?php
	function clude( $path ) {
        static $included = array();
        if ( !isset( $included[ $path ] ) ) {
            $included[ $path ] = true;
            return include $path;
        }
        return true;
    }	

	set_include_path( '../../:./' );

	session_start();
    error_reporting( E_ERROR | E_WARNING | E_PARSE | E_NOTICE );    
    clude( 'models/water.php' );
    global $settings;
    $settings = include 'settings.php';
	var_dump( $settings );
	if ( file_exists( 'localtest.php' ) ) {
		echo " aosihosahflsaghlakshgl s";
	}

	clude( "models/libchart-1.2.1/libchart/classes/libchart.php" );
	clude( "models/db.php" );
	$chart = new VerticalBarChart(500, 250);
	$dataSet = new XYDataSet();
	$dataSet->addPoint(new Point("Jan 2005", 273));
	$dataSet->addPoint(new Point("Feb 2005", 321));
	$dataSet->addPoint(new Point("March 2005", 442));
	$dataSet->addPoint(new Point("April 2005", 711));
	$chart->setDataSet($dataSet);
	$chart->setTitle("Monthly usage for www.example.com");
	header( 'Content-type: image/png' );
	$chart->render();
?>

