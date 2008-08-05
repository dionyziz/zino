<?php
	class ElementStatisticsView extends Element
	{
			
		
		public function Render()
		{
			global $libs;	
			global $page;

			$libs->Load( 'statistics' );
			$page->setTitle( 'Statistics' );
		
			echo '<h1>Statistics</h1>';
			$stats=Statistics_Get();
			echo '<p>'.$stats['day']." ".$stats['count'].'</p>'; 
		}
	}
?>
