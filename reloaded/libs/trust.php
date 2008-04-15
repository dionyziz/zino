<?php

	function Trust_CreateHash() {
	}

	function Trust_NewSession() {
		global $db;
	
		$ip = UserIp();
		$hash = Trust_CreateHash();
		
		$insert = array(
			'session_hash' => $hash,
			'session_ip' => $ip,
			'session_confirmed' => 'no'
		);

		$db->Insert( 'ddos', $insert );

		return $hash;
	}

	function Trust_Confirm( $hash ) {
		global $db;

		$sql = "UPDATE `ddos` SET `session_jsconfirmed` = 'yes' AND `session_date`='" . NowDate() . "' WHERE `session_hash` = '$hash' LIMIT 1;";

		return $db->Query( $sql )->Impact();
	}

?>
