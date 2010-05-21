<?php
    
    class Album {
        public static function Create( $name, $description ) {
            isset( $_SESSION[ 'user' ] ) or die( 'You must be logged in to create an album' );

            clude( 'models/db.php' );
            clude( 'models/album.php' );

            $album = Album::Create( $_SESSION[ 'user' ][ 'id' ], $name, $description );
            $user = User::Item( $_SESSION[ 'user' ][ 'id' ] );

            include 'views/album/view.php';
        }
        public static function Update( $albumid, $name, $description ) {
            isset( $_SESSION[ 'user' ] ) or die( 'You must be logged in to create an album' );

            clude( 'models/db.php' );
            clude( 'models/album.php' );

            $user = $_SESSION[ 'user' ];

            $album = Album::Item( $albumid );
            $album[ 'user' ][ 'id' ] == $user[ 'id' ] or die( 'This is not your album' );

            $details = Album::Update( $albumid, $name, $description );

            // update array details for viewing
            $album[ 'name' ] = $details[ 'name' ];
            $album[ 'url' ] = $details[ 'url' ];
            $album[ 'description' ] = $details[ 'description' ];
            
            include 'views/album/view.php';
        }
        public static function Delete( $albumid ) {
            isset( $_SESSION[ 'user' ] ) or die( 'You must be logged in to create an album' );

            clude( 'models/db.php' );
            clude( 'models/album.php' );

            $user = $_SESSION[ 'user' ];
            $album = Album::Item( $albumid );
            $album[ 'user' ][ 'id' ] == $user[ 'id' ] or die( 'This is not your album' );
            Album::Delete( $albumid );

            include 'view/album/list.php';
        }
    }

?>
