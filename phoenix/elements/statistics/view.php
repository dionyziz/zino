<?php
	class ElementStatisticsView extends Element
	{
			
		
		public function Render(tInteger $daysBefore) {
			global $page;

			$page->setTitle( 'Daily statistics' );

			$daysBefore=$daysBefore->Get();
			echo '<p>'.$daysBefore.'</p>';
		
			echo '<h2>Daily statistics</h2>';

			echo '<img src="images/statistics/stats.php?name=shouts?days='.$daysBefore.'" alt="img"/>';
			echo '<img src="images/statistics/stats.php?name=users?days='.$daysBefore.'" alt="img"/>';
			echo '<img src="images/statistics/stats.php?name=images?days='.$daysBefore.'" alt="img"/>';
			echo '<img src="images/statistics/stats.php?name=polls?days='.$daysBefore.'" alt="img"/>';
			echo '<img src="images/statistics/stats.php?name=comments?days='.$daysBefore.'" alt="img"/>';
			echo '<img src="images/statistics/stats.php?name=albums?days='.$daysBefore.'" alt="img"/>';
			echo '<img src="images/statistics/stats.php?name=journals?days='.$daysBefore.'" alt="img"/>';
		}
	}
?>
