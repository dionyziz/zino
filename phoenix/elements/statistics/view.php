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
					echo '<a href="https://beta.zino.gr/phoenix/?p=statistics&amp;daysBefore=30&amp;graphType='.$graphType.'">';
					if($daysBefore==30) echo'<strong>';
					echo '30 days before'; 
					if($daysBefore==30) echo'</strong>';
					echo '</a>';
				echo '</li>';

				echo '<li>';
					echo '<a href="https://beta.zino.gr/phoenix/?p=statistics&amp;daysBefore=60&amp;graphType='.$graphType.'">';
					if($daysBefore==60) echo'<strong>';
					echo '60 days before'; 
					if($daysBefore==60) echo'</strong>';
					echo '</a>';
				echo '</li>';			
	
				echo '<li>';
					echo '<a href="https://beta.zino.gr/phoenix/?p=statistics&amp;daysBefore=90&amp;graphType='.$graphType.'">';
					if($daysBefore==90) echo'<strong>';
					echo '90 days before'; 
					if($daysBefore==90) echo'</strong>';
					echo '</a>';
				echo '</li>';
			echo '</ul>';

			echo '<ul>';
				echo '<li><a href="https://beta.zino.gr/phoenix/?p=statistics&amp;daysBefore='.$daysBefore.'&amp;graphType=Shoutbox">';
				if($graphType=="Shoutbox") echo '<strong>';
				echo 'Shoutbox';
				if($graphType=="Shoutbox") echo '</strong>';
				echo '</a></li>';

				echo '<li><a href="https://beta.zino.gr/phoenix/?p=statistics&amp;daysBefore='.$daysBefore.'&amp;graphType=Comments">';
				if($graphType=="Comments") echo '<strong>';
				echo 'Comments';
				if($graphType=="Comments") echo '</strong>';
				echo '</a></li>';
			
				echo '<li><a href="https://beta.zino.gr/phoenix/?p=statistics&amp;daysBefore='.$daysBefore.'&amp;graphType=Users">';
				if($graphType=="Users") echo '<strong>';
				echo 'Users';
				if($graphType=="Users") echo '</strong>';
				echo '</a></li>';

				echo '<li><a href="https://beta.zino.gr/phoenix/?p=statistics&amp;daysBefore='.$daysBefore.'&amp;graphType=Images">';
				if($graphType=="Images") echo '<strong>';
				echo 'Users';
				if($graphType=="Images") echo '</strong>';
				echo '</a></li>';
				
				echo '<li><a href="https://beta.zino.gr/phoenix/?p=statistics&amp;daysBefore='.$daysBefore.'&amp;graphType=Polls">';
				if($graphType=="Polls") echo '<strong>';
				echo 'Users';
				if($graphType=="Polls") echo '</strong>';
				echo '</a></li>';
			
				echo '<li><a href="https://beta.zino.gr/phoenix/?p=statistics&amp;daysBefore='.$daysBefore.'&amp;graphType=Journals">';
				if($graphType=="Journals") echo '<strong>';
				echo 'Users';
				if($graphType=="Journals") echo '</strong>';
				echo '</a></li>';

				echo '<li><a href="https://beta.zino.gr/phoenix/?p=statistics&amp;daysBefore='.$daysBefore.'&amp;graphType=Albums">';
				if($graphType=="Albums") echo '<strong>';
				echo 'Users';
				if($graphType=="Albums") echo '</strong>';
				echo '</a></li>';

				echo '<li><a href="https://beta.zino.gr/phoenix/?p=statistics&amp;daysBefore='.$daysBefore.'&amp;graphType=All">';
				if($graphType=="All") echo '<strong>';
				echo 'Users';
				if($graphType=="All") echo '</strong>';
				echo '</a></li>';	
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
