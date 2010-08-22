<?php
    class ControllerImagetag {
        public static function Create( $photoid, $name, $ownerid, $top, $left, $width, $height ) {
			clude( "models/db.php" );
			clude( "models/user.php" );
			$top = ( int )$top;
			$left = ( int )$left;
			$width = ( int )$width;
			$height = ( int )$height;
			if ( !isset( $_SESSION[ 'user' ] ) ) {				
				throw New Exception( "Imagetag::Create - You are not logged in" );
			}
			if ( $ownerid != $_SESSION[ 'user' ][ 'id' ] ) {
				throw New Exception( "imagetag::Create - Owner should be logged in" );
			}
			$user = User::ItemByName( $name );
			if( $user == false ) {
				throw New Exception( "Imagetag::Create - This user doesn't exist" );
			}
			$photo = Photo::Item( $photoid );
            if ( empty( $photo ) ) {
                throw Exception( 'Invalid photo' );
            }
			// check if user is owner of photo or friend of owner; you can't tag some unknown person's photos
			if ( $photo[ 'userid' ] != $ownerid 
             && Friend::Strength( $photo[ 'userid' ], $ownerid ) != FRIENDS_BOTH ) {
		         throw Exception( 'You are not related to the owner of the image' );
		    }
			// now check that the tagged person is the friend of the user; you can't tag who doesn't know you
			if ( Friend::Strength( $ownerid, $user[ 'id' ] ) != FRIENDS_BOTH 
				&& $ownerid != $user[ 'id' ] ) {
            	throw Exception( 'You are not related to the person you are going to tag' );
	        }
			ImageTag::Create( $user[ 'id' ], $photoid, $ownerid, $top, $left, $width, $height );
			return;
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
