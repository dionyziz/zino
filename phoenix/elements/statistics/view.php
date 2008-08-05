<?php
	class ElementStatisticsView extends Element
	{
			
		
		public function Render(tInteger $daysBefore,tText $graphType) {
			global $page;

			$page->setTitle( 'Daily statistics' );

			$daysBefore=$daysBefore->Get();
			$graphType=$graphType->Get();

			if($daysBefore==0) $daysBefore=30;
			if($graphType=="") $graphType="Shoutbox";
		
			echo '<h2>Daily statistics</h2>';

			echo '<ul>';
				echo '<li>';
					echo '<a href="https://beta.zino.gr/phoenix/?p=statistics&amp;daysBefore=30">';
					if($daysBefore==30) echo'<strong>';
					echo '30 days before'; 
					if($daysBefore==30) echo'</strong>';
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

			echo '<ul>';
			echo '<li><a href="https://beta.zino.gr/phoenix/?p=statistics&amp;daysBefore=30&amp;graphType=Shoutbox">Shoutbox</a></li>';
			echo '</ul>';
	
			if ($graphType=="Shoutbox") echo '<img src="images/statistics/stats.php?name=shouts&amp;days='.$daysBefore.'" alt="img"/>';
			else if ($graphType=="Users") echo '<img src="images/statistics/stats.php?name=users&amp;days='.$daysBefore.'" alt="img"/>';
			else if ($graphType=="Images") echo '<img src="images/statistics/stats.php?name=images&amp;days='.$daysBefore.'" alt="img"/>';
			else if ($graphType=="Polls") echo '<img src="images/statistics/stats.php?name=polls&amp;days='.$daysBefore.'" alt="img"/>';
			else if ($graphType=="Comments") echo '<img src="images/statistics/stats.php?name=comments&amp;days='.$daysBefore.'" alt="img"/>';
			else if ($graphType=="Albums") echo '<img src="images/statistics/stats.php?name=albums&amp;days='.$daysBefore.'" alt="img"/>';
			else if ($graphType=="Journals") echo '<img src="images/statistics/stats.php?name=journals&amp;days='.$daysBefore.'" alt="img"/>';
		}
	}
?>
