<?php
	class ElementStatisticsView extends Element
	{
			
		
		public function Render()
		{
			global $libs;	

			$libs->Load( 'statistics' );
		
			echo '<h1>Statistics</h1>';
			$stats=Statistics_Get();
		}
	}
?>
