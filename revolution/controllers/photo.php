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
    function Listing( $page = 1, $limit = 100 ) {
        $page = ( int )$page;
        $limit = ( int )$limit;
        include 'models/db.php';
        include 'models/photo.php';
        $offset = ( $page - 1 ) * $limit;
        $photos = Photo::ListRecent( $offset, $limit );
        include 'views/photo/listing.php';
    }
    function Create( $albumid, $typeid ) {
        global $settings;
        include 'models/db.php';
        include 'models/photo.php';
        include 'models/album.php';

        $user = $_SESSION[ 'user' ];
        $userid = $user[ 'id' ];
        if ( !$userid || $albumid <= 0 ) {
            return;
        }

        $album = Album::Item( $albumid );
        if ( !is_array( $album ) || $album[ 'delid' ] || $album[ 'ownerid' ] != $userid ) {
            die( 'not allowed' );
        }

        $error = 0;

        $uploadimage = $_FILES[ 'uploadimage' ];
        $realname = $uploadimage[ 'name' ];
        if ( !empty( $uploadimage ) ) {
            $extension = substr( $realname, strrpos( $realname, "." ) + 1 );
            if ( !in_array( $extension, array( 'jpg', 'jpeg', 'png', 'gif' ) ) ) {
                $error = "wrongtype";
                include 'views/photo/create.php';
                return;
            }
            $tempname = $uploadimage[ 'tempname' ];
        }
        
        $photo = Photo::Create( $userid, $albumid, $typeid );
        unlink( $tempname );

        if ( !is_array( $photo ) ) {
            if ( $photo == -1 ) {
                $error = "largefile";
            }
            else {
                $error = "fileupload";
            }
            include 'views/photo/create.php';
            return;
        }

        $album[ 'numphotos' ] += 1; // updated on db by trigger
        include 'views/photo/create.php';
    }
    function Update() {
    }
    function Delete() {
    }

?>
