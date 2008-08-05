<?php
	class ElementStatisticsView extends Element
	{
			
		
		public function Render(tInteger $daysBefore) {
			global $page;

			$page->setTitle( 'Daily statistics' );

			$daysBefore=$daysBefore->Get();
		
			echo '<h2>Daily statistics</h2>';

			echo '<ul>
				 <a href="https://beta.zino.gr/phoenix/?p=statistics&amp;daysBefore=30">30</a> 
				 <a href="https://beta.zino.gr/phoenix/?p=statistics&amp;daysBefore=60">60</a>
				 <a href="https://beta.zino.gr/phoenix/?p=statistics&amp;daysBefore=90">90</a>
			      </ul>';

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
