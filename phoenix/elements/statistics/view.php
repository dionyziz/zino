<?php
	class ElementStatisticsView extends Element
	{
			
		
		public function Render() {
			global $page;

			$page->setTitle( 'Statistics' );
		
			echo '<h2>Statistics</h2>';

			echo '<img src="images/statistics/stats.php?name=shout" alt="img"/>';
		}
	}
?>
