<?php
	set_include_path( '../../:./' );
	require_once 'libs/rabbit/rabbit.php';
	Rabbit_Construct('plain');

	global $libs;

	$libs->Load( 'libchart/classes/libchart' );
	$libs->Load( 'statistics' );

	$chart=new LineChart(500,250);
			
	$dataSet=new XYDataSet();
	
	$stat=Statistics_Get(1,1);
	foreach($stat as $row)
	$dataSet->addPoint(new Point($row['day'],$row['count'])); 

	/*$dataSet->addPoint(new Point("2008-06-24",5));
	$dataSet->addPoint(new Point("2008-07-7",0));	
	$dataSet->addPoint(new Point("2008-07-8",22));
	$dataSet->addPoint(new Point("2008-07-9",0));
	$dataSet->addPoint(new Point("2008-07-10",20));
	$dataSet->addPoint(new Point("2008-07-11",1));	*/

	$chart->setDataSet($dataSet);

	$chart->SetTitle("new Shouts per day");

	header( 'Content-type: image/png' );
	$chart->render();
			
	Rabbit_Destruct();
?>
