<?php
	class ElementStatisticsView extends Element
	{
			
		
		public function Render()
		{
			global $libs;	

			$libs->Load('statistics');
		
			//initalize chart
			/*while(!$row=mysql_fetch_array($sql_resuts))
			{*/
				//add point to the chart
			echo '<h1>Statistics</h1>';
			$stats=Statistics_Get();
			//}
			//output chart
		}
	}
?>
