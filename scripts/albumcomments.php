<?php
	set_include_path( '../:./' );
	
	global $page;
	global $libs;
    
	require_once 'libs/rabbit/rabbit.php';
    Rabbit_Construct( 'HTML' );
    $req = $_GET;
    
	Rabbit_ClearPostGet();
	
	$libs->Load( 'albums' );

    function GetAllAlbums() {
        global $db;
        global $albums;

        $sql = "SELECT
                    *
                FROM
                    `$albums`
                ;";

        $res = $db->Query( $sql );
        $ret = array();
        while ( $row = $res->FetchArray() ) {
            $ret[] = new Album( $row );
        }

        return $ret;
    }

    function CountAlbumComments( $album ) {
        global $db;
        global $images;
        global $comments;

        $sql = "SELECT
                    COUNT( * ) AS count
                FROM 
                    `$images` RIGHT JOIN `$comments`
                        ON `image_id` = `comment_storyid`
                WHERE
                    `image_albumid` = '" . $album->Id() . "' AND
                    `image_delid` = '0' AND
                    `comment_typeid` = '2' AND
                    `comment_delid` = '0';";
        
        $fetched = $db->Query( $sql )->FetchArray();
        return $fetched[ "count" ];
    }

    function UpdateAlbumComments( $album, $numcomments ) {
        global $db;
        global $albums;

        $sql = "UPDATE
                    `$albums`
                SET
                    `album_numcomments` = '$numcomments'
                WHERE
                    `album
                    `album_id` = '" . $album->Id() . "'
                LIMIT 1;";

        $change = $db->Query( $sql );

        return $change;
    }

    ob_start();

    $ret = GetAllAlbums();

    foreach ( $ret as $album ) {
        ?>Updating album <?php
        echo $album->Id()
        ?>... <?php

        $comments = CountAlbumComments( $album );

        echo $comments;
        ?> comments... <?php

        UpdateAlbumComments( $album, $comments );

        ?>OK<br /><?php
    }
	
    $page->Output();

    Rabbit_Destruct();
	
?>
