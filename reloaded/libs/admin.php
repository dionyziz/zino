<?php

	function CountUserAgents() {
		global $logs;
		global $db;
		
		$sql = "SELECT
					COUNT(DISTINCT `log_userid`) AS agentcount
				FROM
					`$logs`
				WHERE
					`log_useragent` != '' AND
					`log_userid` != 0;";
		
		$res = $db->Query( $sql );
		$fetched = $res->FetchArray();
		
		return $fetched;
	}
	
	function ListUserAgents() {
		global $logs;
		global $db;
		
		$sql = "SELECT 
					`log_useragent`,`log_userid`
				FROM
					`$logs`
				WHERE
					`log_useragent`!='' AND
					`log_userid`!='0'
				GROUP BY
					`log_userid`
				ORDER BY
					`log_date` DESC;";
		
		$res = $db->Query( $sql );
		$ret = $res->MakeArray();
		
		return $ret;
	}

?>