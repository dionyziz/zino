<?php 
	function Statistics_Get($dbname,$date_field) {

		global  $db;

		$query=$db->Prepare( "SELECT DAY(".$date_field.") AS day,COUNT(*) AS count  FROM ".$dbname." WHERE ".$date_field.">NOW()-INTERVAL 30 DAY GROUP BY day ORDER BY ".$date_field." ASC" );
		$query->BindTable( $dbname );
		$res=$query->Execute();
		
		$array = $res->MakeArray();		

		return $array;
	}
?>
