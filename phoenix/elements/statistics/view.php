<?php
	class ElementStatisticsView extends Element
	{
			
		
		public function Render()
		{
			global $page;

			$page->setTitle( 'Statistics' );
		
			echo '<h1>Statistics</h1>';

			echo '<img src="images/statistics/stats.php" alt="img"/>';
			echo '<img src="images/statistics/stats.php" alt="img"/>';
			echo '<img src="images/statistics/stats.php" alt="img"/>';
		}
	}
?>
