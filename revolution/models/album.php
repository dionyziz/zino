<?php
    
    class Album {
        public static function Item( $id ) {
            $res = db( 
                'SELECT
                    `album_id` AS id, `album_name` AS name, `album_delid` AS delid, `album_ownerid` AS ownerid, `album_numphotos` AS numphotos,
                    `album_ownertype` AS ownertype, `album_mainimageid` AS mainimageid, `album_description` AS description
                FROM
                    `albums`
                WHERE
                    `album_id` = :id
                LIMIT 1', compact( 'id' )
            );

			return mysql_fetch_array( $res );
        }
        public static function ListByUser( $userid, $offset = 0, $limit = 50 ) {
            is_int( $userid ) or die( 'userid not an integer' );

            $res = db(
                'SELECT
                    `album_id` AS id, `album_name` AS name, `album_delid` AS delid, `album_ownerid` AS ownerid, `album_numphotos` AS numphotos,
                    `album_ownertype` AS ownertype, `album_mainimageid` AS mainimageid,
                FROM
                    `albums` 
                WHERE
                    `album_userid` = :userid
                LIMIT :offset, :limit;',
                compact( 'userid', 'offset', 'limit' )
            );

            $albums = array();
            while ( $row = mysql_fetch_array( $res ) ) {
                $albums[] = $row;
            }

            return $albums;
        }
        public static function Create( $userid, $name, $description ) {
            is_int( $userid ) or die( 'userid not an integer' );

            clude( 'models/agent.php' );
            clude( 'models/url.php' );

            $album = array(
                'ownertype' => TYPE_USERPROFILE,
                'ownerid' => $userid,
                'userip' => UserIp(),
                'name' => $name,
                'url' => URL_FormatUnique( $name, $userid, 'Album::ItemByUrlAndOwner' ),
                'description' => $description
            );

            $res = db( 
                "INSERT INTO `albums` ( `album_id`, `album_ownertype`, `album_ownerid`, `album_created`, `album_userip`, `album_name`, `album_url`, 
                                        `album_mainimageid`, `album_description`, `album_delid`, `album_numcomments`, `album_numphotos` )
                VALUES ( 0, :ownertype, :ownerid, NOW(), 0, :name, :url, 0, :description, 0, 0, 0 );",
                $album
            );

            $album[ 'id' ] = mysql_insert_id();
            $album[ 'created' ] = date( 'Y-m-d H:i:s', time() );
            $album[ 'mainimageid' ] = 0;
            $album[ 'delid' ] = 0;
            $album[ 'numcomments' ] = 0;
            $album[ 'numphotos' ] = 0;

            return $album;
        }
        public static function Update( $album, $name, $description, $mainimageid ) {
            is_int( $id ) or die;

            clude( 'models/url.php' );

            if ( $album[ 'name' ] == $name ) {
                $url = $album[ 'url' ];
            }
            else {
                $url = URL_FormatUnique( $name, $album[ 'userid' ], 'Album::ItemByUrlAndUserid' );
            }

            $details = array(
                'id' => $album[ 'id' ],
                'name' => $name,
                'url' => $url,  
                'description' => $description,
                'mainimageid' => $mainimageid
            );

            $res = db( 
                "UPDATE 
                    `albums` 
                SET 
                    `album_name` = :name,
                    `album_url` = :url,
                    `album_description` = :description,
                    `album_mainimageid` = :mainimageid
                WHERE
                    `album_id` = :id
                LIMIT 1;", $details
            );

            return mysql_affected_rows( $res ) == 1;
        }
        public static function Delete( $id ) {
            is_int( $id ) or die;
            $res = db( "DELETE FROM `albums` WHERE `album_id` = :id LIMIT 1;", array( 'id' => $id ) );
            // return mysql_affected_rows( $res ) == 1;
            return true;
        }
        public static function ItemByUrlAndOwner( $url, $ownerid ) {
            $res = db(
                "SELECT
                    *
                FROM
                    `albums`
                WHERE
                    `album_url` = :url AND
                    `album_ownerid` = :ownerid
                LIMIT 1;",
                compact( 'url', 'ownerid' )
            );

            return mysql_fetch_array( $res );
        }
    }

?>
