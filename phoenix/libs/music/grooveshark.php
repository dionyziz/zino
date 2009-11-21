<?php
	
	function Grooveshark_CreateUUID(){
		$uid = "";
		for( $i = 0; $i < 8; ++$i ){
			$uid .= dechex( mt_rand  ( 0, 15  ) );
		}
		$uid .= "-";
		for( $i = 0; $i < 4; ++$i ){
			$uid .= dechex( mt_rand  ( 0, 15  ) );
		}
		$uid .= "-";
		for( $i = 0; $i < 4; ++$i ){
			$uid .= dechex( mt_rand  ( 0, 15  ) );
		}
		$uid .= "-";
		for( $i = 0; $i < 4; ++$i ){
			$uid .= dechex( mt_rand  ( 0, 15  ) );
		}
		$uid .= "-";
		for( $i = 0; $i < 12; ++$i ){
			$uid .= dechex( mt_rand  ( 0, 15  ) );
		}
		return strtoupper( $uid );
	}
	
	function Grooveshark_GetSessionID(){
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, "http://listen.grooveshark.com/" );
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec ($ch);
		curl_close ($ch);
		$result = explode( "=", $result );
		$result = $result[ 1 ];
		$result = explode( ";", $result );	
		return $result[ 0 ]; //PHPSESSIONID
	}
	
	function Grooveshark_GetToken( $session, $uid ){
		$secretKey = md5( $session );
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, "https://cowbell.grooveshark.com/service.php" );
		curl_setopt( $ch, CURLOPT_COOKIE, "PHPSESSID=$session" );
		curl_setopt( $ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt( $ch, CURLOPT_POSTFIELDS, '{"header":{"session":"' . $session . '","uuid":"' . $uid . '","client":"gslite","clientRevision":"20091027.09"},"parameters":{"secretKey":"' . $secretKey . '"},"method":"getCommunicationToken"}');
		curl_setopt( $ch,CURLOPT_HTTPHEADER, array( "Content-type: application/json" ) );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec ($ch);
		curl_close ($ch);
		$result = explode( '"', $result );
		return $result[ count( $result ) - 2 ];
	}
	
	
	function Grooveshark_SearchSong( $query ){
		$uuid = Grooveshark_CreateUUID();
		$session = Grooveshark_GetSessionID();
		$token = Grooveshark_GetToken( $session, $uuid );
		
		$specialtoken = "a12345" . sha1( "getSearchResults:$token:theHumansAreDead:a12345");
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, "http://cowbell.grooveshark.com/more.php?getSearchResults" );
		curl_setopt( $ch, CURLOPT_COOKIE, "PHPSESSID=$session" );
		curl_setopt( $ch, CURLOPT_POST, true);
		curl_setopt( $ch, CURLOPT_POSTFIELDS, '{"header":{"token":"' . $specialtoken . '","session":"' . $session . '","uuid":"' . $uuid . '","client":"gslite","clientRevision":"20091027.09"},"parameters":{"query":"' . $query . '","type":"Songs"},"method":"getSearchResults"}');
		curl_setopt( $ch,CURLOPT_HTTPHEADER, array( "Content-type: application/json" ) );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec ($ch);
		curl_close ($ch);
		return $result;
	}
	
	function Grooveshark_SetSong( $id ){
        global $libs;  
        global $user; 
    
        $libs->Load( "user/profile" );    
        $libs->Load( "music/song" );

		$uuid = Grooveshark_CreateUUID();
		$session = Grooveshark_GetSessionID();
		$token = Grooveshark_GetToken( $session, $uuid );
		
		$specialtoken = "a12345" . sha1( "createWidgetIDFromSongIDs:$token:theHumansAreDead:a12345");
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, "http://cowbell.grooveshark.com/service.php?createWidgetIDFromSongIDs" );
		curl_setopt( $ch, CURLOPT_COOKIE, "PHPSESSID=$session" );
		curl_setopt( $ch, CURLOPT_POST, true);
		curl_setopt( $ch, CURLOPT_POSTFIELDS, '{"header":{"token":"' . $specialtoken . '","session":"' . $session . '","uuid":"' . $uuid . '","client":"gslite","clientRevision":"20091027.09"},"parameters":{"songIDs":[' . $id . ']},"method":"createWidgetIDFromSongIDs"}');
		curl_setopt( $ch,CURLOPT_HTTPHEADER, array( "Content-type: application/json" ) );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec ($ch);
		curl_close ($ch);
		$result = json_decode( $result, true );
		
		$widgetID = $result[ "result" ][ "widgetID" ];
		
        w_assert( is_int( $widgetID ) , "WidgetID was not an integer." );
        $user->Profile->Songwidgetid = $widgetID;
        $user->Save();
		
		$specialtoken = "a12345" . sha1( "getQueueSongListFromSongIDs:$token:theHumansAreDead:a12345");		
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, "http://cowbell.grooveshark.com/service.php?getQueueSongListFromSongIDs" );
		curl_setopt( $ch, CURLOPT_COOKIE, "PHPSESSID=$session" );
		curl_setopt( $ch, CURLOPT_POST, true);
		curl_setopt( $ch, CURLOPT_POSTFIELDS, '{"header":{"token":"' . $specialtoken . '","session":"' . $session . '","uuid":"' . $uuid . '","client":"gslite","clientRevision":"20091027.09"},"parameters":{"songIDs":[' . $id . ']},"method":"getQueueSongListFromSongIDs"}');
		curl_setopt( $ch,CURLOPT_HTTPHEADER, array( "Content-type: application/json" ) );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec ($ch);
		curl_close ($ch);
		$result = json_decode( $result, true );
		$info = $result[ "result" ][ 0 ];
        
		//TODO
		//Save songid, artistid, and albumid to database. $info is like this:
		/*
		array
		  'songID' => int 2
		  'songName' => string 'A Living Prayer' (length=15)
		  'albumID' => int 6
		  'albumName' => string 'Lonely Runs Both Ways' (length=21)
		  'artistID' => int 1
		  'artistName' => string 'Alison Krauss & Union Station' (length=29)
		  'artURL' => string 'http://beta.grooveshark.com/static/amazonart/s881ebf588fa6b2a5bf7cbaf49c8d7c5f.png' (length=82)
		  'avgRating' => int 3
		  'estimateDuration' => int 214
	  */

        $song = New Song();
        $song->Songid = $info[ "songID" ];
        $song->Albumid = $info[ "albumID" ];
        $song->Artistid = $info[ "artistID" ];
        $song->Save();

		return true;
	}
	
	function Grooveshark_DeleteSong(){
		global $libs;  
        global $user; 
    
        $libs->Load( "user/profile" );    
        
        $user->Profile->Songwidgetid = -1;
        $user->Profile->Save();        
    
		return true;
	}
?>
