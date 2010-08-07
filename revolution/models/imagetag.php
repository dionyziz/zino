<?php

    class ImageTag {
        public static function ListByPhoto( $photoid ) {
			$photoid = ( int )$photoid;
			return db_array( 'SELECT 
							`tag_id` as id, `tag_imageid` as imageid, `tag_personid` as personid, `tag_ownerid` as ownerid, `tag_created` as created, `tag_left` as tagleft, `tag_top` as tagtop, `tag_width` as width, `tag_height` as height
						FROM `imagetags`
						WHERE `tag_imageid` = :photoid',
						compact(  "photoid" ) );
			
        }
        public static function Create( $personid, $photoid, $ownerid, $top, $left, $width, $height ) {
            $person = User::Item( $personid );
            if ( empty( $person ) ) {
                throw Exception( 'Invalid personid' );
            }
            $photo = Photo::Item( $photoid );
            if ( empty( $photo ) ) {
                throw Exception( 'Invalid photo' );
            }

            $info = compact( 'personid', 'photoid', 'ownerid', 'top', 'left', 'width', 'height' );
            db( 'INSERT INTO `imagetags` ( 
                `tag_personid`,  `tag_photoid`, 
                `tag_ownerid`, `tag_top`, 
                `tag_left`, `tag_width, 
                `tag_height`, `tag_created` )
                 VALUES ( 
                    :personid, :photoid, 
                    :ownerid, 
                    :top, :left, 
                    :width, :height, NOW() )',
                 $info );

            $info[ 'id' ] = mysql_insert_id();
            return $info;
        }
        public static function Delete() {
        }
    }

?>
