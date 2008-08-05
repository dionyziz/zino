<?php
	set_include_path( '../../:./' );
	require_once 'libs/rabbit/rabbit.php';
	Rabbit_Construct('plain');

	global $libs;

	$libs->Load( 'libchart/classes/libchart' );
	$libs->Load( 'statistics' );

	$ttle="";

	if($_GET['name']=='shouts')
	{
		$stat=Statistics_Get('shoutbox','shout_created');
		$title="new Shouts per day";	
	}
	else if($_GET['name']=='users')
	{
		$stat=Statistics_Get('users','user_created');
		$title="new Users per day";
	}
	else if($_GET['name']=='images')
	{
		$stat=Statistics_Get('images','image_created');
		$title="new Users per day";
	}
	else if($_GET['name']=='polls')
	{
		$stat=Statistics_Get('polls','poll_created');
		$title="new Polls per day";
	}
	else if($_GET['name']=='comments')
	{
		$stat=Statistics_Get('comments','comment_created');
		$title="new Comments per day";
	}
	else if($_GET['name']=='journals')
	{
		$stat=Statistics_Get('journals','journal_created');
		$title="new Journals per day";
	}
	else if($_GET['name']=='albums')
	{
		$stat=Statistics_Get('albums','album_created');
		$title="new Albums per day";
	}

	if($title=="") exit(0);//Not valid get name

	$chart=new LineChart(500,250);			
	$dataSet=new XYDataSet();	
	
	foreach($stat as $row)
	{	
		$date=new DateTime($row['day']);
		$dataSet->addPoint(new Point($date->format('m-d'),$row['count'])); 
	}

	$chart->setDataSet($dataSet);
	$chart->SetTitle( $title );

	header( 'Content-type: image/png' );
	$chart->render();
			
	Rabbit_Destruct();
?>
