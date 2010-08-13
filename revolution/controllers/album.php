<?php
    class ControllerAlbum {
        public static function View( $id, $page = 1, $limit = 100 ) {
            clude( 'models/db.php' );
            clude( 'models/album.php' );
            clude( 'models/photo.php' );
            $offset = ( $page - 1 ) * $limit;
            $album = Album::Item( $id );
            if ( $album === false || $album[ "delid" ] == 1 ) {
                Template( 'album/view', compact( 'album' ) );
                return;   
            }
            clude( 'models/user.php' );
            $photos = Photo::ListByAlbum( $id, $offset, $limit );
            $user = User::Item( $album[ 'ownerid' ] );
            $egoalbumid = User::GetEgoAlbumId( $user[ 'id' ] );
            $album[ 'egoalbum' ] = $album[ 'id' ] == $egoalbumid;
            Template( 'album/view', compact( 'album', 'photos', 'user' ) );
        }
        public static function Create( $name, $description ) {
            isset( $_SESSION[ 'user' ] ) or die( 'You must be logged in to create an album' );

            clude( 'models/db.php' );
            clude( 'models/album.php' );
            clude( 'models/user.php' );

            $album = Album::Create( $_SESSION[ 'user' ][ 'id' ], $name, $description );
            $user = User::Item( $_SESSION[ 'user' ][ 'id' ] );

            include 'views/album/view.php';
        }
        public static function Update( $albumid, $name, $description, $mainimageid ) {
            isset( $_SESSION[ 'user' ] ) or die( 'You must be logged in to create an album' );

            clude( 'models/db.php' );
            clude( 'models/album.php' );
			clude( 'models/user.php' );

            $user = $_SESSION[ 'user' ];

			$egoalbumid = User::GetEgoAlbumId( $user[ 'id' ] );

            $album = Album::Item( $albumid );
            $album[ 'ownerid' ] == $user[ 'id' ] or die( 'This is not your album' );

			if ( $egoalbumid == $albumid ) {
				if ( !empty( $name ) ) {
					$name = $album[ 'name' ];
				}
				if ( $mainimageid != 0 ) { //change useravatarid when egoalbums mainimageid is changed 
					User::UpdateAvatarid( $user[ 'id' ], $mainimageid );
				}
			}

            if ( empty( $name ) ) {
                $name = $album[ 'name' ];
            }
            if ( empty( $description )  ) {
                $description = $album[ 'description' ];
            }
            if ( $mainimageid == 0 ) {
                $mainimageid = $album[ 'mainimageid' ];
            }

            $details = Album::Update( $album, $name, $description, $mainimageid );

            // update array details for viewing
            $album[ 'name' ] = $details[ 'name' ];
            $album[ 'url' ] = $details[ 'url' ];
            $album[ 'description' ] = $details[ 'description' ];
            $album[ 'mainimageid' ] = $details[ 'mainimageid' ];

            include 'views/album/view.php';
        }
        public static function Listing( $username ) {
            clude( 'models/db.php' );
            clude( 'models/album.php' );
            clude( 'models/user.php' );
            clude( 'models/types.php' );

            $user = User::ItemByName( $username );
            $albums = Album::ListByUser( $user[ 'id' ] );
            $egoalbumid = User::GetEgoAlbumId( $user[ 'id' ] );
            $albums[ $egoalbumid ][ 'egoalbum' ] = true;

            include 'views/album/listing.php';
        }
        public static function Delete( $albumid ) {
            isset( $_SESSION[ 'user' ] ) or die( 'You must be logged in to create an album' );

            clude( 'models/db.php' );
            clude( 'models/album.php' );

            $user = $_SESSION[ 'user' ];
            $album = Album::Item( $albumid );
            $album[ 'ownerid' ] == $user[ 'id' ] or die( 'This is not your album' );
            Album::Delete( $albumid );
        }
    }

?>
