<?php 
	function Statistics_Get() {

		global  $db;

		$query=$db->Prepare( "SELECT DATE(shout_created) AS day,COUNT(*) AS count  FROM `shoutbox` WHERE shout_created>NOW()-INTERVAL 30 DAY GROUP BY day ORDER BY day ASC" );
		$query->BindTable( 'shoutbox' );
		$res=$query->Execute();
		
		$array = $res->MakeArray();		

		return $array;
	}
?>
