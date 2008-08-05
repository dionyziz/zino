<?php
	class ElementStatisticsView extends Element
	{
			
		
		public function Render() {
			global $page;

			$page->setTitle( 'Statistics' );
		
			echo '<h2>Statistics</h2>';

			echo '<img src="images/statistics/stats.php?name=shouts" alt="img"/>';
			echo '<img src="images/statistics/stats.php?name=users" alt="img"/>';
			echo '<img src="images/statistics/stats.php?name=images" alt="img"/>';
			echo '<img src="images/statistics/stats.php?name=polls" alt="img"/>';
			echo '<img src="images/statistics/stats.php?name=comments" alt="img"/>';
			echo '<img src="images/statistics/stats.php?name=albums" alt="img"/>';
			echo '<img src="images/statistics/stats.php?name=journals" alt="img"/>';
		}
	}
?>
