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
					echo '<a href="https://beta.zino.gr/phoenix/?p=statistics&amp;daysBefore=30&amp;'.$graphType.'">';
					if($daysBefore==30) echo'<strong>';
					echo '30 days before'; 
					if($daysBefore==30) echo'</strong>';
					echo '</a>';
				echo '</li>';

				echo '<li>';
					echo '<a href="https://beta.zino.gr/phoenix/?p=statistics&amp;daysBefore=60&amp;'.$graphType.'">';
					if($daysBefore==60) echo'<strong>';
					echo '60 days before'; 
					if($daysBefore==60) echo'</strong>';
					echo '</a>';
				echo '</li>';			
	
				echo '<li>';
					echo '<a href="https://beta.zino.gr/phoenix/?p=statistics&amp;daysBefore=90&amp;'.$graphType.'">';
					if($daysBefore==90) echo'<strong>';
					echo '90 days before'; 
					if($daysBefore==90) echo'</strong>';
					echo '</a>';
				echo '</li>';
			echo '</ul>';

			echo '<ul>';
			echo '<li><a href="https://beta.zino.gr/phoenix/?p=statistics&amp;daysBefore=30&amp;graphType=Shoutbox">Shoutbox</a></li>';
			echo '<li><a href="https://beta.zino.gr/phoenix/?p=statistics&amp;daysBefore=30&amp;graphType=Users">Users</a></li>';
			echo '<li><a href="https://beta.zino.gr/phoenix/?p=statistics&amp;daysBefore=30&amp;graphType=Images">Images</a></li>';
			echo '<li><a href="https://beta.zino.gr/phoenix/?p=statistics&amp;daysBefore=30&amp;graphType=Polls">Polls</a></li>';
			echo '<li><a href="https://beta.zino.gr/phoenix/?p=statistics&amp;daysBefore=30&amp;graphType=Comments">Comments</a></li>';
			echo '<li><a href="https://beta.zino.gr/phoenix/?p=statistics&amp;daysBefore=30&amp;graphType=Albums">Albums</a></li>';
			echo '<li><a href="https://beta.zino.gr/phoenix/?p=statistics&amp;daysBefore=30&amp;graphType=Journals">Journals</a></li>';
			echo '<li><a href="https://beta.zino.gr/phoenix/?p=statistics&amp;daysBefore=30&amp;graphType=All">All</a></li>';
			echo '</ul>';
	
			if ($graphType=="Shoutbox" || $graphType=="All") echo '<img src="images/statistics/stats.php?name=shouts&amp;days='.$daysBefore.'" alt="img"/>';
			else if ($graphType=="Users" || $graphType=="All") echo '<img src="images/statistics/stats.php?name=users&amp;days='.$daysBefore.'" alt="img"/>';
			else if ($graphType=="Images" || $graphType=="All") echo '<img src="images/statistics/stats.php?name=images&amp;days='.$daysBefore.'" alt="img"/>';
			else if ($graphType=="Polls" || $graphType=="All") echo '<img src="images/statistics/stats.php?name=polls&amp;days='.$daysBefore.'" alt="img"/>';
			else if ($graphType=="Comments" || $graphType=="All") echo '<img src="images/statistics/stats.php?name=comments&amp;days='.$daysBefore.'" alt="img"/>';
			else if ($graphType=="Albums" || $graphType=="All") echo '<img src="images/statistics/stats.php?name=albums&amp;days='.$daysBefore.'" alt="img"/>';
			else if ($graphType=="Journals" || $graphType=="All") echo '<img src="images/statistics/stats.php?name=journals&amp;days='.$daysBefore.'" alt="img"/>';
		}
	}
?>
