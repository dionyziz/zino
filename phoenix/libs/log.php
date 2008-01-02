<?php
	function TotalUserLogs() {
		global $db;
		global $logs;
		
		$query = $db->Prepare("
			SELECT 
				COUNT(*) AS logscount
			FROM
				`$logs`
			WHERE
				`log_userid`= :LogUserId
			;
		");
		$query->Bind( 'LogUserId', $id );
		$res = $query->Execute();
		$fetched = $res->FetchArray();
		
		return $fetched;
	}
	function TotalLogsCount() {
		global $db;
		global $logs;
		
		$query = $db->Prepare("
			SELECT
				COUNT(*) AS lcount 
			FROM 
				`$logs`
			WHERE 
				`log_requesturi` NOT LIKE '%style%' AND
				`log_requesturi` NOT LIKE '%image%';
		");
		
		$res = $query->Query( $sql );
		
		return $res->FetchArray();
	}
	
	function ListUserLogs( $uid, $offset ) {
		global $db;
		global $logs;
		
		$uid = myescape( $uid );
		$offset = myescape( $offset );
		
		$query = $db->Prepare("
			SELECT
				*
			FROM 
				`$logs` 
			WHERE
				`log_userid`= :LogUserId
			ORDER BY
				`log_date` DESC
			LIMIT
				:Offset , :Limit
			;
		");
		$query->Bind( 'LogUserId', $uid );
		$query->Bind( 'Offset', $offset );
		$query->Bind( 'Limit' , 25 );
		$res = $query->Execute();
		$ret = $res->MakeArray();
		
		return $ret;		
	}
	
	function LogDatesByDate() {
		global $db;
		global $logs;
		
		$query = $db->Prepare("
			SELECT
				`log_date`
			FROM 
				`$logs`
			WHERE 
				`log_requesturi` NOT LIKE '%style%' AND
				`log_requesturi` NOT LIKE '%image%'
			ORDER BY
				`log_date` ASC 
			LIMIT :Limit
			;
		");
		
		$query->Bind( 'Limit', 1 );
	
		$res = $query->Query( $sql );
		
		return $res->FetchArray();
	}
	
	final class Log extends Satori {
        protected $mDbTable = 'logs';

        protected function LoadDefaults() {
            global $user;

            $this->Date       = NowDate();
            $this->Host       = UserIp();
            $this->HostPort   = $_SERVER[ 'REMOTE_PORT' ];
            $this->UserId     = $user->Id();
            $this->RequestUri = $_SERVER[ 'REQUEST_URI' ];
            $this->Query      = $_SERVER[ 'QUERY_STRING' ];
            $this->UserAgent  = $_SERVER[ 'HTTP_USER_AGENT' ];
        }
	}
?>
