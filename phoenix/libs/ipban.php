<?php
	function IPBan_List() {
		global $bans;
		global $users;
		global $db;
		
        $sql = "SELECT
                    `ipban_id`, `ipban_ip`, `ipban_date`,
                    `ipban_expiredate`, `ipban_sysopid`,
                    `user_id`, `user_name`, `user_lastprofedit`, `user_rights`
                FROM
                    `$bans` LEFT JOIN `$users`
                        ON `ipban_sysopid` = `user_id`;";
		
		$res = $db->Query( $sql );
        
        $ret = array();
        while ( $row = $res->FetchArray() ) {
            $ret[] = New IPBan( $row );
        }
		
		return $ret;
	}
    
    final class IPBan extends Satori {
        protected $mId;
        protected $mIp;
        protected $mDate;
        protected $mExpireDate;
        protected $mSysOpId;
        protected $mSysOp;
        
        protected function GetSysOp() {
            return $this->mSysOp;
        }
        protected function LoadDefaults() {
            global $user;
            
            $this->mSysOpId    = $user->Id();
            $this->mDate       = NowDate();
            $this->mExpireDate = gmdate("Y-m-d H:i:s", time() + 7 * 24 * 60 * 60 );
        }
        public function IPBan( $construct = false ) {
            global $db;
            global $bans, $users;
            global $water;
            
            $this->mDb = $db;
            $this->mDbTable = $bans;
            $this->SetFields( array(
                'ipban_id'         => 'Id',
                'ipban_ip'         => 'Ip',
                'ipban_date'       => 'Date',
                'ipban_expiredate' => 'ExpireDate',
                'ipban_sysopid'    => 'SysOpId'
            ) );
            if ( is_int( $construct ) ) { // by banid
                // Prepared query
				$query = $db->Prepare("
					SELECT
					    `ipban_id`, `ipban_ip`, `ipban_date`,
					    `ipban_expiredate`, `ipban_sysopid`,
					    `user_id`, `user_name`, `user_lastprofedit`, `user_rights`
					FROM
					    `$bans` LEFT JOIN `$users`
					        ON `ipban_sysopid` = `user_id`
					WHERE
					    `ipban_id` = :IpBanId
					LIMIT :Limit
					;
				");
				
				// Assign query values
				$query->Bind( 'IpBanid', $construct );
				$query->Bind( 'Limit', '1' );
				
				// Execute query
                $res = $query->Execute();
                if ( !$res->Results() ) {
                    $water->Notice( 'Requested ipban does not exist!' );
                    $construct = false;
                }
                else {
                    $construct = $res->FetchArray();
                }
            }
            else if ( is_string( $construct ) ) { // by ip
                //$construct = addslashes( $construct );
                
				// Prepared query
				$query = $db->Prepare("
					SELECT
					    `ipban_id`, `ipban_ip`, `ipban_date`,
					    `ipban_expiredate`, `ipban_sysopid`,
					    `user_id`, `user_name`, `user_lastprofedit`, `user_rights`
					FROM
					    `$bans` LEFT JOIN `$users`
					        ON `ipban_sysopid` = `user_id`
					WHERE
					    `ipban_id` = :IpBanId
					LIMIT :Limit
					;
				");
				
				// Assign query values
				$query->Bind( 'IpBanid', $construct );
				$query->Bind( 'Limit', '1' );
				
				// Execute query
                $res = $query->Execute();
                if ( !$res->Results() ) {
                    $construct = false;
                }
                else {
                    $construct = $res->FetchArray();
                }
            }
            
            if ( is_array( $construct ) ) {
                $this->mSysOp = New User( $construct );
            }
            
            $this->Satori( $construct );
        }
    }
?>
