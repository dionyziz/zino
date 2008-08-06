<?php
	class ElementStatisticsView extends Element
	{
			
		
		public function Render(tInteger $daysBefore,tText $graphType) {
			global $page;
			global $user;

			if ( !$user->HasPermission( PERMISSION_STATISTICS_VIEW ) ) {
			?> Permission denied <?php
			return;
			}

			$page->setTitle( 'Daily statistics' );

			$daysBefore = $daysBefore->Get();
			$graphType = $graphType->Get();

			if( $daysBefore == 0 ) {
			$daysBefore = 30;
			}
			if( $graphType == "" ) {
			$graphType = "Shoutbox";
			}
		
			?> <h2>Daily statistics</h2> <?php

			?> <ul> <?php
				
				foreach ( array( 30, 60, 90 ) as  $days ) {
					
				?> <li> <?php
					?> <a href="?p=statistics&amp;daysBefore=<?php echo $days;?>&amp;graphType=<?php echo $graphType ?> "> <?php
					if( $daysBefore == $days)  echo'<strong>';
					echo $days.' days before'; 
					if( $daysBefore == $days ) echo'</strong>';
					echo '</a>';
				echo '</li>';			
				}

				
			echo '</ul>';

			echo '<ul>';
				echo '<li><a href="?p=statistics&amp;daysBefore='.$daysBefore.'&amp;graphType=Shoutbox">';
				if($graphType=="Shoutbox") echo '<strong>';
				echo 'Shoutbox';
				if($graphType=="Shoutbox") echo '</strong>';
				echo '</a></li>';
			
				echo '<li><a href="?p=statistics&amp;daysBefore='.$daysBefore.'&amp;graphType=Users">';
				if($graphType=="Users") echo '<strong>';
				echo 'Users';
				if($graphType=="Users") echo '</strong>';
				echo '</a></li>';

				echo '<li><a href="?p=statistics&amp;daysBefore='.$daysBefore.'&amp;graphType=Images">';
				if($graphType=="Images") echo '<strong>';
				echo 'Images';
				if($graphType=="Images") echo '</strong>';
				echo '</a></li>';
				
				echo '<li><a href="?p=statistics&amp;daysBefore='.$daysBefore.'&amp;graphType=Polls">';
				if($graphType=="Polls") echo '<strong>';
				echo 'Polls';
				if($graphType=="Polls") echo '</strong>';
				echo '</a></li>';

				echo '<li><a href="?p=statistics&amp;daysBefore='.$daysBefore.'&amp;graphType=Comments">';
				if($graphType=="Comments") echo '<strong>';
				echo 'Comments';
				if($graphType=="Comments") echo '</strong>';
				echo '</a></li>';
			
				echo '<li><a href="?p=statistics&amp;daysBefore='.$daysBefore.'&amp;graphType=Journals">';
				if($graphType=="Journals") echo '<strong>';
				echo 'Journals';
				if($graphType=="Journals") echo '</strong>';
				echo '</a></li>';

				echo '<li><a href="?p=statistics&amp;daysBefore='.$daysBefore.'&amp;graphType=Albums">';
				if($graphType=="Albums") echo '<strong>';
				echo 'Albums';
				if($graphType=="Albums") echo '</strong>';
				echo '</a></li>';

				echo '<li><a href="?p=statistics&amp;daysBefore='.$daysBefore.'&amp;graphType=All">';
				if($graphType=="All") echo '<strong>';
				echo 'All';
				if($graphType=="All") echo '</strong>';
				echo '</a></li>';	
			echo '</ul>';
	
			if ($graphType=="Shoutbox" || $graphType=="All") echo '<img src="images/statistics/stats.php?name=shoutbox&amp;days='.$daysBefore.'" alt="img"/>';
			if ($graphType=="Users" || $graphType=="All") echo '<img src="images/statistics/stats.php?name=users&amp;days='.$daysBefore.'" alt="img"/>';
			if ($graphType=="Images" || $graphType=="All") echo '<img src="images/statistics/stats.php?name=images&amp;days='.$daysBefore.'" alt="img"/>';
			if ($graphType=="Polls" || $graphType=="All") echo '<img src="images/statistics/stats.php?name=polls&amp;days='.$daysBefore.'" alt="img"/>';
			if ($graphType=="Comments" || $graphType=="All") echo '<img src="images/statistics/stats.php?name=comments&amp;days='.$daysBefore.'" alt="img"/>';
			if ($graphType=="Albums" || $graphType=="All") echo '<img src="images/statistics/stats.php?name=albums&amp;days='.$daysBefore.'" alt="img"/>';
			if ($graphType=="Journals" || $graphType=="All") echo '<img src="images/statistics/stats.php?name=journals&amp;days='.$daysBefore.'" alt="img"/>';
		}
	}
?>
