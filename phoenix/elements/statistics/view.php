<?php
	class ElementStatisticsView extends Element
	{
			
		
		public function Render(tInteger $daysBefore) {
			global $page;

			$page->setTitle( 'Daily statistics' );

			$daysBefore=$daysBefore->Get();
		
			echo '<h2>Daily statistics</h2>';

			echo '<ul>';
			echo '<li>';
			echo '<a href="https://beta.zino.gr/phoenix/?p=statistics&amp;daysBefore=30">';
			if($daysBefore==30 || $daysBefore==0) echo'<strong>';
			echo '30 days before'; 
			if($daysBefore==30 || $daysBefore==0) echo'</strong>';
			echo '</a>';
			echo '</li>';

			echo '<li>';
			echo '<a href="https://beta.zino.gr/phoenix/?p=statistics&amp;daysBefore=60">';
			if($daysBefore==60) echo'<strong>';
			echo '60 days before'; 
			if($daysBefore==60) echo'</strong>';
			echo '</a>';
			echo '</li>';			

			echo '<li>';
			echo '<a href="https://beta.zino.gr/phoenix/?p=statistics&amp;daysBefore=90">';
			if($daysBefore==90) echo'<strong>';
			echo '90 days before'; 
			if($daysBefore==90) echo'</strong>';
			echo '</a>';
			echo '</li>';
			echo '</ul>';

			echo '<img src="images/statistics/stats.php?name=shouts&amp;days='.$daysBefore.'" alt="img"/>';
			echo '<img src="images/statistics/stats.php?name=users&amp;days='.$daysBefore.'" alt="img"/>';
			echo '<img src="images/statistics/stats.php?name=images&amp;days='.$daysBefore.'" alt="img"/>';
			echo '<img src="images/statistics/stats.php?name=polls&amp;days='.$daysBefore.'" alt="img"/>';
			echo '<img src="images/statistics/stats.php?name=comments&amp;days='.$daysBefore.'" alt="img"/>';
			echo '<img src="images/statistics/stats.php?name=albums&amp;days='.$daysBefore.'" alt="img"/>';
			echo '<img src="images/statistics/stats.php?name=journals&amp;days='.$daysBefore.'" alt="img"/>';
		}
	}
?>
