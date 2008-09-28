<?php
	function MakeUsershout( $text, $shoutowner ) {
		global $usershout;
		global $user;
		global $db;
        
		$nowdate = NowDate();
		$ip = UserIp();
		$text = myescape( $text );
		$shoutowner = myescape( $shoutowner );

		$sql = "INSERT INTO
					`$usershout`
				(      `usershout_id` ,      `usershout_userid` ,       `usershout_shoutowner`,    `usershout_text` , `usershout_created` , `usershout_userip` )
				VALUES( '' , '" . $user->Id() . "' , '$shoutowner' , '$text' , '$nowdate' ,     '$ip' );";

		$db->Query( $sql );
	}
	
	function UpdateUsershout( $text , $id ) {
		global $usershout;
		global $user;
		global $db;
        
		$nowdate = NowDate();
		$ip = UserIp();
		$text = myescape( $text );
		$id = myescape( $id );
		$sql = "UPDATE
					`$usershout`
				SET
					`usershout_text`='$text'
				WHERE
					`usershout_id`='$id'
				LIMIT 1;";

		$db->Query( $sql );
	}
	
	function getUsershouts( $shoutowner ) {
		global $db;
		global $usershout;
		
		$shoutowner = myescape( $shoutowner );
		
		$sql = "SELECT * FROM `$usershout` WHERE `usershout_shoutowner` = '$shoutowner' ORDER BY `usershout_created` DESC LIMIT 4;";
		
		$res = $db->Query( $sql );
		$ret = $res->MakeArray();
		
		return $ret;
	}

	class Usershout {
		var $mText;
		var $mUserId;
		var $mDate;
		var $mHost;
		var $mUsername;
		var $mUsernameLoaded;
		var $mSubmitDate;
		var $mId;
		
		function Id() {
			return $this->mId;
		}
		function Date() {
			return $this->mSubmitDate;
		}
		function SQLDate() {
			return $this->mDate;
		}
		function Text() {
			return $this->mText;
		}
		function Username() {
			global $users;
			
			if ( !$this->mUsernameLoaded ) {
				$sql = "SELECT `usershout_name` FROM
							`$users`
						WHERE
							`id`='" . $this->mUserId . "'
						LIMIT 1;";
				$sqlr = mysql_query( $sql ) or mdie( mysql_error() );
				if ( mysql_num_rows( $sqlr ) ) {
					$sqluser = mysql_fetch_array( $sqlr );
					$theuser = New User( $sqluser ); // this constructor and call should change
					$this->mUsername = $theuser->Username();
				}
				else {
					$this->mUsername = "Anonymous";
				}
				$this->mUsernameLoaded = true;
			}
			return $this->mUsername;
		}
		function UserId() {
			return $this->mUserId;
		}
		function Usershout( $fetched_array ) {
			$this->mText = $fetched_array[ "usershout_text" ];
			$this->mUserId = $fetched_array[ "usershout_userid" ];
			$this->mDate = $fetched_array[ "usershout_created" ];
			$this->mHost = $fetched_array[ "usershout_userip" ];
			$this->mId = $fetched_array[ "usershout_id" ];
			$this->mSubmitDate = MakeDate( $this->mDate );
		}
	}
?>