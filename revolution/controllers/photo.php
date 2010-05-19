<?php
    class ControllerPhoto {
        public static function View( $id, $commentpage = 1, $verbose = 3 ) {
            $id = ( int )$id;
            $commentpage = ( int )$commentpage;
            $commentpage >= 1 or die;
            clude( 'models/db.php' );
            clude( 'models/photo.php' );
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
                clude( 'models/comment.php' );
                $commentdata = Comment::FindByPage( TYPE_IMAGE, $id, $commentpage );
                $numpages = $commentdata[ 0 ];
                $comments = $commentdata[ 1 ];
                $countcomments = $photo[ 'numcomments' ];
            }
            if ( $verbose >= 2 ) {
                clude( 'models/favourite.php' );
                $favourites = Favourite::Listing( TYPE_IMAGE, $id );
            }
            include 'views/photo/view.php';
        }
        public static function Listing( $username = 0, $page = 1, $limit = 100 ) {
            $page = ( int )$page;
            $limit = ( int )$limit;
            clude( 'models/db.php' );
            clude( 'models/photo.php' );
            $offset = ( $page - 1 ) * $limit;
            if ( $username != '' ) {
                clude( 'models/user.php' );
                $user = User::ItemByName( $username );
                $photos = Photo::ListByUser( $user[ 'id' ], $offset, $limit );
            }
            else {
                $photos = Photo::ListRecent( $offset, $limit );
            }
            include 'views/photo/listing.php';
        }
        public static function Create( $albumid ) {
            global $settings;

            isset( $_SESSION[ 'user' ] ) or die( 'You must be logged in to upload a picture' );
            isset( $_FILES[ 'uploadimage' ] ) or die( 'No image specified' );

            clude( 'models/db.php' );
            clude( 'models/photo.php' );
            clude( 'models/album.php' );
    
            $userid = $_SESSION[ 'user' ][ 'id' ];

            if ( !$userid ) {
                return;
            }

            $albumid = ( int )$albumid;
            if ( !( $albumid > 0 ) ) {
                clude( 'models/user.php' );
                $albumid = User::GetEgoAlbumId( $userid );
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
                $tempname = $uploadimage[ 'tmp_name' ];
            }
            
            $photo = Photo::Create( $userid, $albumid, $tempname );
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
    
            ++$album[ 'numphotos' ]; // updated on db by trigger
            include 'views/photo/create.php';
        }
        public static function Update() {
        }
        public static function Delete() {
        }
    }
?>
