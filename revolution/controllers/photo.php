<?php
    class ControllerPhoto {
        public static function View( $id, $commentpage = 1, $verbose = 3 ) {
            $id = ( int )$id;
            $commentpage = ( int )$commentpage;
            $commentpage >= 1 or die;
            include 'models/db.php';
            include 'models/photo.php';
            $photo = Photo::Item( $id );
            $photo !== false or die;
            if ( $photo[ 'user' ][ 'deleted' ] === 1 ) { 
                include 'views/itemdeleted.php';
                return;
            }
            if ( $verbose >= 1 ) {
                $user = $photo[ 'user' ];
            }
            if ( $verbose >= 3 ) {
                include 'models/comment.php';
                $commentdata = Comment::FindByPage( TYPE_IMAGE, $id, $commentpage );
                $numpages = $commentdata[ 0 ];
                $comments = $commentdata[ 1 ];
                $countcomments = $photo[ 'numcomments' ];
            }
            if ( $verbose >= 2 ) {
                include 'models/favourite.php';
                $favourites = Favourite::Listing( TYPE_IMAGE, $id );
            }
            include 'views/photo/view.php';
        }
        public static function Listing( $page = 1, $limit = 100 ) {
            $page = ( int )$page;
            $limit = ( int )$limit;
            include 'models/db.php';
            include 'models/photo.php';
            $offset = ( $page - 1 ) * $limit;
            $photos = Photo::ListRecent( $offset, $limit );
            include 'views/photo/listing.php';
        }
        public static function Create( $albumid, $typeid ) {
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
        public static function Update() {
        }
        public static function Delete() {
        }
    }
?>
