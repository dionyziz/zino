<?php
	set_include_path( '../../:./' );
	require_once 'libs/rabbit/rabbit.php';
	Rabbit_Construct('plain');

	global $libs;
	global $user;

	if ( !$user->HasPermission( PERMISSION_STATISTICS_VIEW ) ) {
	echo 'Permission denied';	
	return;
	}

	$libs->Load( 'libchart/classes/libchart' );
	$libs->Load( 'statistics' );

	$ttle="";
	
	if((int)$_GET['days']!=60 && (int)$_GET['days']!=90)
        $_GET['days']=30;

	$stat=Statistics_Get($_GET[ 'name' ],$_GET['days']);
	$title="new ".$_GET[ 'name' ]." per day";

	if($_GET['days']==30) $x=500;
	else if($_GET['days']==60) $x=750;
	else if($_GET['days']==90) $x=1000;
	

	$chart=new LineChart($x,$x-250);	//1000 for 90 days,750 for 60 days 500 for 30 days		
	$dataSet=new XYDataSet();	
	
	$i=0;
	$lastday="";
	$lastmonth;
	foreach ($stat as $row) {	
		if ($i%10==0) {
			$date=new DateTime($row['day']);
			$label=$date->format('m-d');
		}
		else 
		{
			$label="";
			$date=new DateTime($row['day']);
		}

		if($lastday!="")
		{
			for($e=0;$e<((int)$date->format('d')-$lastday+((int)$date->format('m')-$lastmonth)*30)-1;$e++)
			$dataSet->addPoint(new Point("",0)); 

		}
		
		$dataSet->addPoint(new Point($label,$row['count'])); 

		$i++;
	
		$lastday=(int)$date->format('d');
		$lastmonth=(int)$date->format('m');
	}
	for($e=0;$e<((int)date('d')-$lastday+((int)date('m')-$lastmonth)*30)-1;$e++)
	$dataSet->addPoint(new Point("",0)); 
	

	$chart->setDataSet($dataSet);
	$chart->SetTitle( $title );

	header( 'Content-type: image/png' );
	$chart->render();
			
	Rabbit_Destruct();
?>
