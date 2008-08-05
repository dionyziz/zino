<?php
	class ElementStatisticsView extends Element
	{
			
		
		public function Render()
		{
			global $libs;	
			global $page;

			$libs->Load( 'statistics' );
			$libs->Load( 'libchart/classes/libchart' );
			$page->setTitle( 'Statistics' );
		
			echo '<h1>Statistics</h1>';
			$stat=Statistics_Get();
			foreach($stat as $row)
			echo '<p>'.$row['day']." ".$row['count'].'</p>'; 
		}
	}
?>
