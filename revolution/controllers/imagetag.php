<?php
    class ControllerImagetag {
        public static function Create( $name, $description ) {
        }
        public static function Listing( $photoid ) {
            clude( 'models/db.php' );
            clude( 'models/album.php' );
            clude( 'models/user.php' );
            clude( 'models/types.php' );
			clude( 'models/imagetag.php' );

			$photoid = ( int )$photoid;
			$tags = ImageTag::ListByPhoto( $photoid );
            Template( 'imagetag/listing', compact( 'tags', 'photoid' ) );
        }
        public static function Delete( $albumid ) {
        }
    }

?>
