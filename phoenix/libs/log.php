<?php
    class LogFinder extends Finder {
        protected $mModel = 'Log';
        
    	public function FindTotalCount() {
    		$query = $this->mDb->Prepare("
    			SELECT
    				COUNT(*) AS lcount 
    			FROM 
    				:logs
    			WHERE 
    				`log_requesturi` NOT LIKE '%style%' AND
    				`log_requesturi` NOT LIKE '%image%';
            ");
    		$query->BindTable( 'logs' );
            
    		$res = $query->Query( $sql );
    		$row = $res->FetchArray();
            
            return array_shift( $row );
    	}
    	public function FindByUser( $uid, $offset ) {
    		$query = $this->mDb->Prepare("
    			SELECT
    				*
    			FROM 
    				:logs
    			WHERE
    				`log_userid`= :LogUserId
    			ORDER BY
    				`log_date` DESC
    			LIMIT
    				:Offset, :Limit;
    		");
            $query->BindTable( 'logs' );
    		$query->Bind( 'LogUserId', $uid );
    		$query->Bind( 'Offset', $offset );
    		$query->Bind( 'Limit' , 25 );
    		$res = $query->Execute();
    		
    		return $this->FindBySQLResult( $res );		
    	}
    	public function FindAll() {
    		$query = $this->mDb->Prepare("
    			SELECT
    				`log_date`
    			FROM 
    				:logs
    			WHERE 
    				`log_requesturi` NOT LIKE '%style%' AND
    				`log_requesturi` NOT LIKE '%image%'
    			ORDER BY
    				`log_date` ASC;
    		");
    		$query->BindTable( 'logs' );
    		$res = $query->Execute();
    		
    		return $this->FindBySQLResult( $res );
    	}
    }
    
	final class Log extends Satori {
        protected $mDbTable = 'logs';

        protected function LoadDefaults() {
            global $user;
            
            $this->Date       = NowDate();
            $this->Host       = UserIp();
            $this->Hostport   = $_SERVER[ 'REMOTE_PORT' ];
            $this->Userid     = $user->Id;
            $this->Requesturi = $_SERVER[ 'REQUEST_URI' ];
            $this->Query      = $_SERVER[ 'QUERY_STRING' ];
            $this->Useragent  = $_SERVER[ 'HTTP_USER_AGENT' ];
        }
	}
?>
