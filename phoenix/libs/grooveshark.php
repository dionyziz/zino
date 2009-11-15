<?php
	function Groove_SearchSong( $name ) {
	

		return;
	}

	function Groove_GetWidgetId( $id ) {
		$ch = curl_init();

		curl_setopt( $ch, CURLOPT_URL, "http://widgets.grooveshark.com/make?new" );
		//curl_setopt( $ch, CURLOPT_HEADER, true );
		//curl_setopt( $ch, CURLOPT_NOBODY, true );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_FRESH_CONNECT, true );

		if ( !$res = curl_exec( $ch ) ) {
			return "ERROR";
		}
		else {
			return $res;
		}

		return;
	}

	

?>
