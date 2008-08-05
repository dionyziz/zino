<?php
	class ElementStatisticsView extends Element
	{
			
		
		public function Render()
		{
			global $libs;	
			global $page;

			$libs->Load( 'statistics' );
			//$libs->Load( 'libchart/classes/libchart' );
			$page->setTitle( 'Statistics' );
		
			echo '<h1>Statistics</h1>';

			echo '<img src="images/statistics/stats.php" alt="img"/>';
		
			/*$chart=new LineChart(500,250);
			
			$dataSet=new XYDataSet();
			$dataSet->addPoint(new Point("2008-06-24",5));
			$dataSet->addPoint(new Point("2008-07-7",0));	
			$dataSet->addPoint(new Point("2008-07-8",22));
			$dataSet->addPoint(new Point("2008-07-9",0));
			$dataSet->addPoint(new Point("2008-07-10",20));
			$dataSet->addPoint(new Point("2008-07-11",1));	
		
			$chart->setDataSet($dataSet);
		
			$chart->SetTitle("new Shouts per day");
			$chart->render();*/
		}
	}
?>
