<?php

    class ImageTag {
        public static function ListByPhoto( $photoid ) {
			$photoid = ( int )$photoid;
			return db_array( 'SELECT 
							`tag_id` as id, `tag_imageid` as imageid, `tag_personid` as personid, `tag_ownerid` as ownerid, `tag_created` as created, `tag_left` as tagleft, `tag_top` as tagtop, `tag_width` as width, `tag_height` as height, `user_name` as name
						FROM `imagetags`
						LEFT JOIN
								`users`
						ON
							`tag_personid` = `user_id`
						WHERE `tag_imageid` = :photoid',
						compact(  "photoid" ) );
			
        }
        public static function Create( $personid, $photoid, $ownerid, $top, $left, $width, $height ) {
            $person = User::Item( $personid );
            if ( empty( $person ) ) {
                throw Exception( 'Invalid personid' );
            }
            /*$photo = Photo::Item( $photoid ); //Being done in the controller 
            if ( empty( $photo ) ) {
                throw Exception( 'Invalid photo' );
            }*/
			if ( $width < 45 || $height < 45 ) {
				throw New Exception( 'Invalid value of width or height.Too small' );
			}

            $info = compact( 'personid', 'photoid', 'ownerid', 'top', 'left', 'width', 'height' );
            db( 'INSERT INTO `imagetags` ( 
                `tag_personid`,  `tag_imageid`, 
                `tag_ownerid`, `tag_top`, 
                `tag_left`, `tag_width`, 
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
		public static function ItemMulti( $ids ) {
			if ( !is_array( $ids ) ) {
				return array();
			}
			return db_array( 
				'SELECT 
					`tag_id` as id, `tag_imageid` as imageid, `tag_personid` as personid, `tag_ownerid` as ownerid, `tag_created` as created, `tag_left` as tagleft, `tag_top` as tagtop, `tag_width` as width, `tag_height` as height, `user_name` as ownername, `user_deleted` as ownerdeleted, `user_avatarid` as owneravatarid, `user_gender` as ownergender, `image_userid` as imageownerid, `image_name` as imagename
				FROM
					`imagetags`
				LEFT JOIN
					`users`
				ON `user_id` = `tag_ownerid`
				LEFT JOIN
					`images`
				ON `image_id` = `tag_imageid`
				WHERE `tag_id` IN :ids
				LIMIT 1,100', compact( 'ids' )
			);
		}
        public static function Delete( $id ) {
        }
    }

?>
