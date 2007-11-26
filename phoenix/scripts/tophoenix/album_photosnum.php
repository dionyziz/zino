<?php

    /*
        Developer: Abresas

        Fixes the number of photos for each album.
        It was calculated by counting the number of photos from the database,
        but now it is a database field (album_numphotos).

        Run this script when the new version - Phoenix - is released.
    */
	
    set_include_path( '../:./' );
	
	global $page;
	global $libs;
    global $user;
    
	require_once 'libs/rabbit/rabbit.php';
    Rabbit_Construct( 'HTML' );

    $req = $_GET;
    
	Rabbit_ClearPostGet();

    if ( !$user->CanModifyCategories() ) {
        return false;
    }
   
    $libs->Load( 'album' );

    function Albums_GetPhotosNum() {
		global $db;
		global $images;
		
		if ( !is_array( $keys ) ) {
			$keys = array( $keys );
		}
		
		foreach( $keys as $i => $key ) {
			$keys[ $i ] = myescape( $key );
		}
		
		$sql = "SELECT
					`image_albumid`, COUNT( * ) AS numphotos
				FROM 
					`$images`
				WHERE	
					`image_delid` = '0'
				GROUP BY
					`image_albumid`;";
					
		$res = $db->Query( $sql );
		
		$ret = array();
		while ( $row = $res->FetchArray() ) {
			$ret[ $row[ 'image_albumid' ] ] = $row[ 'numphotos' ];
		}

		return $ret;
	}

    ?>Fetching number of photos for all albums...<?php

    $photosnum = Albums_GetPhotosNum();

    ?>OK.<br /><br /><?php

    foreach ( $photosnum as $id => $num ) {
        ?>Updating album <?php
        echo $id;
        ?>....<?php

        $album = new Album( $id );
        $album->PhotosNum = $num;
        $album->Save();

        ?>OK (<?php
        echo $num;
        ?>).<br /><?php
    }

?>
