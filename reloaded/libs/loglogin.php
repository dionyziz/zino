<?php
	function LogLogin_checkBot( $ip ) {
		global $db;
		global $loglogin;
		
		w_assert( is_string( $ip ), 'LogLogin_checkBot accepts only a string argument' );
		
		$sql = "SELECT * FROM `$loglogin`
				WHERE `loglogin_ip` = '" . $ip . "'
				AND `loglogin_time` >= ( NOW() - INTERVAL 15 MINUTE )
				AND `loglogin_success` = '0'
				LIMIT 3;";
				
		$res = $db->Query( $sql );
		
		if ( $res->NumRows() != 3 ) {
			return true;
		}
		return false;
	}	

	final class LogLogin extends Satori {
		protected $mId;
		protected $mTime;
		protected $mUserId;
		protected $mIP;
		protected $mPassword;
		protected $mSuccess;
		
		public function LogLogin( $construct = false ) {
			global $db;
			global $loglogin;
			
			$this->mDb = $db;
			$this->mDbTable = $loglogin;
			$this->SetFields( array(
				'loglogin_id'		=> 'Id',
				'loglogin_time'		=> 'Time',
				'loglogin_userid'	=> 'UserId',
				'loglogin_ip'		=> 'IP',
				'loglogin_password'	=> 'Password',
				'loglogin_success'	=> 'Success'
			) );
			
			$this->Satori( $construct );
		}
		public function SetDefaults() {
			$this->IP = UserIp();
			$this->Time = NowDate();
			$this->UserId = 0;
			$this->Password = '';
			$this->Success = false;
		}
	}
?>
