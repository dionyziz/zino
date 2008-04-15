<?php

	function Trust_CreateHash() {
		$hash = "";
		for ( $i = 0; $i < 32; ++$i ) {
			$hash .= dechex( 0, 15 );
		}

		return $hash;
	}

	function Trust_HashInUse( $hash ) {
		global $db;

		$sql = "SELECT * FROM `ddos` WHERE `session_hash` = '$hash' LIMIT 1;";

		$res = $db->Query( $sql );

		return $res->Results();
	}

	function Trust_NewSession() {
		global $db;
	
		$ip = UserIp();
		$hash = Trust_CreateHash();

		while ( Trust_HashInUse( $hash ) ) {
			$hash = Trust_CreateHash();
		}
		
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

		w_assert( is_string( $hash )) ;
		w_assert( strlen( $hash ) == 32 );

		$sql = "UPDATE `ddos` SET `session_jsconfirmed` = 'yes' AND `session_date`='" . NowDate() . "' WHERE `session_hash` = '$hash' LIMIT 1;";

		return $db->Query( $sql )->Impact();
	}

?>
