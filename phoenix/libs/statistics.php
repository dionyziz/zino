<?php 
	function Statistics_Get($dbname,$date_field,$days_passed) {

		global  $db;

		if($days_passed==0) $days_passed=30;

		$query=$db->Prepare( "SELECT DATE(".$date_field.") AS day,COUNT(*) AS count  FROM ".$dbname." WHERE ".$date_field.">NOW()-INTERVAL 30 DAY GROUP BY day ORDER BY day ASC" );
		$query->BindTable( $dbname );
		$res=$query->Execute();
		
		$array = $res->MakeArray();		

		return $array;
	}
?>
