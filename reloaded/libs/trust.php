<?php

	function Trust_CreateHash() {
		$hash = "";
		for ( $i = 0; $i < 32; ++$i ) {
			$hash .= dechex( rand( 0, 15 ) );
		}

		return $hash;
	}

	function Trust_GetUntrusted() {
		global $db;
		
		$sql = "SELECT 
					*, COUNT( * ) AS howmany
				FROM 
					`merlin_ddos`
				WHERE 
					`session_jsconfirmed` = 'no'
				GROUP BY 
					`session_ip`
				HAVING 
					howmany > 10
				ORDER BY 
					`howmany` DESC
				LIMIT 100;";
		$res = $db->Query( $sql );
		
		$ips = array();
		while ( $row = $res->FetchArray() ) {
			$ips[] = long2ip( $row[ 'session_ip' ] );
		}
		return $ips;
	}
	
	function Trust_HashInUse( $hash ) {
		global $db;

		$sql = "SELECT * FROM `merlin_ddos` WHERE `session_hash` = '$hash' LIMIT 1;";

		$res = $db->Query( $sql );

		return $res->Results();
	}

	function Trust_NewSession() {
		global $db;
	
		$ip = ip2long( UserIp() );
		$hash = Trust_CreateHash();

		while ( Trust_HashInUse( $hash ) ) {
			$hash = Trust_CreateHash();
		}
		
		$insert = array(
			'session_hash' => $hash,
			'session_ip' => $ip,
			'session_jsconfirmed' => 'no',
			'session_querystring' => $_SERVER[ 'REQUEST_URI' ],
			'session_date' => NowDate()
		);

		$db->Insert( $insert, 'merlin_ddos' );

		return $hash;
	}

	function Trust_Confirm( $hash ) {
		global $db;

		w_assert( is_string( $hash )) ;
		w_assert( strlen( $hash ) == 32 );

		$sql = "UPDATE `merlin_ddos` SET `session_jsconfirmed` = 'yes' AND `session_date`=NOW() WHERE `session_hash` = '$hash' LIMIT 1;";

		return $db->Query( $sql )->Impact();
	}

?>
