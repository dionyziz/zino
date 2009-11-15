<?php
	function Groove_SearchSong( $name ) {
	//http://widgets.grooveshark.com/search
	//q=ssssssssssss&type=song&page=1&domain=widgets
		$ch = curl_init();
		$data = array(
		    'q' => urlencode( $name ),
		    'type' => urlencode( 'song' ),
	  	    'page' => urlencode( '1' ),
	  	    'domain' => urlencode( 'widgets' )
		);

		foreach ( $data as $key=>$value) { 
			$data_string .= $key . '=' . $value . '&'; 
		}
		rtrim( $data_string, '&' );

		curl_setopt( $ch, CURLOPT_URL, "http://widgets.grooveshark.com/search.php" );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_HEADER, true );
		curl_setopt( $ch, CURLOPT_POST, count( $data ) );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $data_string );
		curl_setopt( $ch, CURLOPT_FRESH_CONNECT, true );

		if ( ! $res = curl_exec( $ch ) ) {
			return "ERROR";
		}
		else {
			return $res . "data";
		}
		return;
	}

	function Groove_MakeNewWidget( $id ) {
		/*$ch = curl_init();
		http://widgets.grooveshark.com/make?widgetid=16551254
		$data = array(
		    'USER' => $user,
		    'PWD' => $pwd,
		    'SIGNATURE' => $signature,
		    'VERSION' => $version,
		    'METHOD' => "POST"
		);

		curl_setopt( $curl, CURLOPT_URL, "http://widgets.grooveshark.com/make?widgetid=16551254" );
		curl_setopt( $curl, CURLOPT_USERAGENT, "Zino/21290 <ads@zino.gr>" );
		curl_setopt( $curl, CURLOPT_HTTPHEADER, $header );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $curl, CURLOPT_POST, true );
		curl_setopt( $curl, CURLOPT_POSTFIELDS, $data );
*/

	}

	function Groove_GetWidgetId( $id ) {
		$ch = curl_init();

		curl_setopt( $ch, CURLOPT_URL, "http://widgets.grooveshark.com/make?new" );
		curl_setopt( $ch, CURLOPT_HEADER, true );
		curl_setopt( $ch, CURLOPT_NOBODY, true );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_FRESH_CONNECT, true );
		

		$output = array( "header" => "", "widgetid" => "" ); 
		if ( ! $res = curl_exec( $ch ) ) {
			$output[ "header" ] = "ERROR";
		}
		else {
			$output[ "header" ] = $res;

			if ( $pos1 = stripos( $res, "http://widgets.grooveshark.com/make?widgetid=" ) ) {
				$output[ "widgetid" ] = substr( $res, $pos1+45, 8 );//widget id 	
			}
			else {
				$output[ "widgetid" ] = "ERROR";
			}
		}

		curl_close( $ch );
		return output;
	}

	

?>
