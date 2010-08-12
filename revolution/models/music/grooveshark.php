<?php
    clude( 'models/music/GSAPI.php' );
    
	function Grooveshark_SearchSong( $query ){
		$gsapi = GSAPI::getInstance( array( 'APIKey' => "1100e42a014847408ff940b233a39930" ) );
		return $gsapi->searchSongs( $query, 70 );
	}

	function Grooveshark_AboutSongs( $ids ) {
		if ( !is_array( $ids ) ) {
			return false;
		}
		$gsapi = GSAPI::getInstance( array( 'APIKey' => "1100e42a014847408ff940b233a39930" ) );
		$info = array();
		foreach ( $ids as $id ) {
			$info[] = $gsapi->songAbout( $id );
		}
		return $info;
	}
	
    /*
	function Grooveshark_SetSong( $songid ){
        global $libs;
        global $user;
		
		$gsapi = GSAPI::getInstance(array('APIKey' => "1100e42a014847408ff940b233a39930" ) );
		
        $libs->Load( "user/profile" );    
        $libs->Load( "music/song" );

        $user->Profile->Songid = $songid;
        $user->Profile->Save();
		
		$info = $gsapi->songAbout( $songid );

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
        
        $user->Profile->Songid = -1;
        $user->Profile->Save();        
    
		return true;
	}
    */
?>
