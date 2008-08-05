<?php 
	function Statistics_Get() {

		global  $db;

		$query=$db->Prepare( "SELECT DATE(shout_created) AS day,COUNT(*) AS count  FROM `shoutbox` WHERE shout_created>NOW()-INTERVAL 30 DAY GROUP BY day ORDER BY day ASC" );
		$query->BindTable( 'shoutbox' );
		$res=$query->Execute();
		
		$array = $res->MakeArray();		

		return $array;
	}

	function Graph_Image_Get(){
	
		global $libs;	
		$libs->Load( 'libchart/classes/libchart' );

		$chart=new LineChart(500,250);
			
		$dataSet=new XYDataSet();
		$dataSet->addPoint(new Point("2008-06-24",5));
		$dataSet->addPoint(new Point("2008-07-7",0));	
		$dataSet->addPoint(new Point("2008-07-8",22));
		$dataSet->addPoint(new Point("2008-07-9",0));
		$dataSet->addPoint(new Point("2008-07-10",20));
		$dataSet->addPoint(new Point("2008-07-11",1));	

		$chart->setDataSet($dataSet);
		$chart->SetTitle("new Shouts per day");
		$chart->render();
	}
?>
