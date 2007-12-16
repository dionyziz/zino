<?php
	function LogThis() {
		global $user;
		
        $log             = New Log();
        $log->Date       = NowDate();
        $log->Host       = UserIp();
        $log->HostPort   = $_SERVER[ 'REMOTE_PORT' ];
        $log->UserId     = $user->Id();
        $log->RequestUri = $_SERVER[ 'REQUEST_URI' ];
        $log->Query      = $_SERVER[ 'QUERY_STRING' ];
        $log->UserAgent  = $_SERVER[ 'HTTP_USER_AGENT' ];
        
        $log->Save();
    }
	function TotalUserLogs() {
		global $db;
		global $logs;
		
		// Prepared query
		$query = $db->Prepare("
			SELECT 
				COUNT(*) AS logscount
			FROM
				`$logs`
			WHERE
				`log_userid`= :LogUserId
			;
		");
		
		// Assign query values
		$query->Bind( 'LogUserId', $id );
		
		// Execute query
		$res = $query->Execute();
		$fetched = $res->FetchArray();
		
		return $fetched;
	}
	function TotalLogsCount() {
		global $db;
		global $logs;
		
		// Prepared query
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
		
		// Prepared query
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
		
		// Assign query values
		$query->Bind( 'LogUserId', $uid );
		$query->Bind( 'Offset', $offset );
		$query->Bind( 'Limit' , 25 );
	
		// Execute query
		$res = $query->Execute();
		$ret = $res->MakeArray();
		
		return $ret;		
	}
	
	function LogDatesByDate() {
		global $db;
		global $logs;
		
		// Prepared query
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
        protected $mId;
		protected $mDate;
        protected $mHost;
        protected $mHostPort;
		protected $mUserId;
		protected $mRequestUri;
		protected $mQuery;
		protected $mUserAgent;
		
        public function Save() {
            global $db;
            global $logs;
            
            $insert = array(
                'log_id'         => $this->mId,
                'log_date'       => $this->mDate,
                'log_host'       => $this->mHost,
                'log_hostport'   => $this->mHostPort,
                'log_userid'     => $this->mUserId,
                'log_requesturi' => $this->mRequestUri,
                'log_query'      => $this->mQuery,
                'log_useragent'  => $this->mUserAgent
            );
            
            $db->Insert(
                $insert, $logs, false, true
            );
            $this->mExists = true;
            
            return true;
        }
		public function Log( $construct = false ) {
            global $db;
            global $logs;
            
            $this->mDb = $db;
            $this->mDbTable = $logs;
            $this->SetFields( array(
                'log_id'         => 'Id',
                'log_date'       => 'Date',
                'log_host'       => 'Host',
                'log_hostport'   => 'HostPort',
                'log_userid'     => 'UserId',
                'log_requesturi' => 'RequestUri',
                'log_query'      => 'Query',
                'log_useragent'  => 'UserAgent'
            ) );
            
            $this->Satori( $construct );
		}
	}
?>
