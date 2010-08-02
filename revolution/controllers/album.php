<?php
    
    class ControllerAlbum {
        public static function View( $id ) {
            clude( 'models/db.php' );
            clude( 'models/album.php' );
            $album = Album::Item( $id );
            if ( $album === false || $album[ "delid" ] == 1 ) {
                clude( 'views/album/deleted.php' );
                return;   
            }
            clude( 'models/user.php' );
            $photos = Photo::ListByAlbum( $albumid, $offset, $limit );
            $user = User::Item( $album[ 'ownerid' ] );
            Template( 'album/view', compact( 'album', 'photos', 'user' ) );
        }
        public static function Create( $name, $description ) {
            isset( $_SESSION[ 'user' ] ) or die( 'You must be logged in to create an album' );

            clude( 'models/db.php' );
            clude( 'models/album.php' );

            $album = Album::Create( $_SESSION[ 'user' ][ 'id' ], $name, $description );
            $user = User::Item( $_SESSION[ 'user' ][ 'id' ] );

            include 'views/album/view.php';
        }
        public static function Update( $albumid, $name, $description, $mainimageid ) {
            isset( $_SESSION[ 'user' ] ) or die( 'You must be logged in to create an album' );

            clude( 'models/db.php' );
            clude( 'models/album.php' );

            $user = $_SESSION[ 'user' ];

            $album = Album::Item( $albumid );
            $album[ 'user' ][ 'id' ] == $user[ 'id' ] or die( 'This is not your album' );

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

            include 'views/album/listing.php';
        }
        public static function Delete( $albumid ) {
            isset( $_SESSION[ 'user' ] ) or die( 'You must be logged in to create an album' );

            clude( 'models/db.php' );
            clude( 'models/album.php' );

            $user = $_SESSION[ 'user' ];
            $album = Album::Item( $albumid );
            $album[ 'user' ][ 'id' ] == $user[ 'id' ] or die( 'This is not your album' );
            Album::Delete( $albumid );
        }
    }

?>
