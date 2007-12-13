<?php
	/*
	Class and Functions responsible for logging login attempts
	*/

	function LoginAttempt_checkBot( $ip ) {
		global $db;
		global $loginattempts;
		
		w_assert( is_string( $ip ), 'LoginAttempt_checkBot accepts only a string argument' );
		
		$now = NowDate();
		
		$sql = "SELECT * FROM `$loginattempt`
				WHERE `loginattempt_ip` = '" . $ip . "'
				AND `loginattempt_time` >= ( '$now' - INTERVAL 15 MINUTE )
				AND `loginattempt_success` = '0'
				LIMIT 3;";
				
		$res = $db->Query( $sql );
		
		if ( $res->NumRows() != 3 ) {
			return false;
		}
		return true;
	}	

	final class LoginAttempt extends Satori {
		protected $mId;
		protected $mTime;
		protected $mUserName;
		protected $mIP;
		protected $mPassword;
		protected $mSuccess;
		
		public function LoginAttempt( $construct = false ) {
			global $db;
			global $loginattempts;
			
			$this->mDb = $db;
			$this->mDbTable = $loginattempt;
			$this->SetFields( array(
				'loginattempt_id'		=> 'Id',
				'loginattempt_time'		=> 'Time',
				'loginattempt_username'	=> 'UserName',
				'loginattempt_ip'		=> 'IP',
				'loginattempt_password'	=> 'Password',
				'loginattempt_success'	=> 'Success'
			) );
			
			$this->Satori( $construct );
		}
		public function SetDefaults() {
			$this->IP = UserIp();
			$this->Time = NowDate();
			$this->UserName = '';
			$this->Password = '';
			$this->Success = 0;
		}
	}
?>
