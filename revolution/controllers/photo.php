<?php
    function View( $id, $commentpage = 1 ) {
        $id = ( int )$id;
        $commentpage = ( int )$commentpage;
        $commentpage >= 1 or die;
        include 'models/db.php';
        include 'models/comment.php';
        include 'models/photo.php';
        include 'models/favourite.php';
        $photo = Photo::Item( $id );
        $photo !== false or die;
		if ( $photo[ 'userdeleted' ] === 1 ) { 
			include 'views/itemdeleted.php';
			return;
		}
        $commentdata = Comment::FindByPage( TYPE_IMAGE, $id, $commentpage );
        $numpages = $commentdata[ 0 ];
        $comments = $commentdata[ 1 ];
        $countcomments = $photo[ 'numcomments' ];
        $favourites = Favourite::Listing( TYPE_IMAGE, $id );
        include 'views/photo/view.php';
    }
    function Listing( $commentpage = 1 ) {
        include 'models/db.php';
        include 'models/photo.php';
        $photos = Photo::ListRecent();
        include 'views/photo/listing.php';
    }
    function Create( $albumid, $typeid, $uploadimage, $filencoded ) {
        global $settings;
        include 'models/photo.php';
        include 'models/album.php';

        $user = $_SESSION[ 'user' ];
        $userid = $user[ 'id' ];
        if ( !$userid || $albumid <= 0 ) {
            return;
        }

        $album = Album::Item( $albumid ); // TODO
        if ( !is_array( $album ) || $album[ 'delid' ] || $album[ 'ownerid' ] != $userid ) {
            die( 'not allowed' );
        }

        $realname = $uploadimage[ 'name' ];
        if ( !empty( $uploadimage ) ) {
            $extension = substr( $realname, strrpos( $realname, "." ) + 1 );
            if ( !in_array( $extension, array( 'jpg', 'jpeg', 'png', 'gif' ) ) ) {
                ?><error>Αυτός ο τύπος εικόνας δεν υποστηρίζεται</error><?php
                return;
            }
            $tempname = $uploadimage[ 'tempname' ];
        }
        else if ( empty( $fileencoded ) ) {
            ?><error>Υπήρξε πρόβλημα κατά την αποθήκευση. Προσπάθησε ξανά</error><?php
            return;
        }
        else {
            $tempname = tempnam( '/tmp', 'zinoupload' );
            file_put_contents( $tempname, base64_decode( $fileencoded ) );
        }
        
        $photo = Photo::Create( $userid, $albumid, $typeid );
        unlink( $tempname );

        if ( !is_array( $photo ) ) {
            ?><error><?php
            if ( $photo == -1 ) {
                ?>H φωτογραφία σου δεν πρέπει να ξεπερνάει τα 4MB<?php
            }
            else {
                ?>Παρουσιάστηκε πρόβλημα κατά τη μεταφορά της εικόνας<?php
            }
            ?></error><?php
        }

        $album[ 'numphotos' ] += 1; // updated on db by trigger
        include 'views/photo/create.php';
    }
    function Update() {
    }
    function Delete() {
    }

?>
