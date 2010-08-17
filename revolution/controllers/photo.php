<?php
    class ControllerPhoto {
        public static function View( $id, $commentpage = 1, $verbose = 3 ) {
            $id = ( int )$id;
            $commentpage = ( int )$commentpage;
            $commentpage >= 1 or die;
            clude( 'models/db.php' );
            clude( 'models/photo.php' );
            $photo = Photo::Item( $id );
            $album = $photo[ 'album' ];
            if ( $photo[ 'user' ][ 'deleted' ] === 1 || $photo === false ) { 
                include 'views/itemdeleted.php';
                return;
            }
            if ( $verbose >= 1 ) {
                $user = $photo[ 'user' ];
            }
            if ( $verbose >= 3 ) {
                clude( 'models/comment.php' );
				clude( 'models/imagetag.php' );
                $commentdata = Comment::ListByPage( TYPE_PHOTO, $id, $commentpage );
                $numpages = $commentdata[ 0 ];
                $comments = $commentdata[ 1 ];
                $countcomments = $photo[ 'numcomments' ];
				$imagetags = ImageTag::ListByPhoto( $id );

            }
            if ( $verbose >= 2 ) {
                clude( 'models/favourite.php' );
                $favourites = Favourite::ListByTypeAndItem( TYPE_PHOTO, $id );
            }
            Template( 'photo/view', compact( 'id', 'commentpage', 'photo', 'numpages', 'comments', 'countcomments', 'favourites', 'user', 'album', 'imagetags' ) );
        }
        public static function Listing( $subdomain = '', $page = 1, $limit = 100 ) {
            $page = ( int )$page;
            $limit = ( int )$limit;
            clude( 'models/db.php' );
            clude( 'models/photo.php' );
            $offset = ( $page - 1 ) * $limit;
            if ( $subdomain != '' ) {
                clude( 'models/user.php' );
                $user = User::ItemByName( $subdomain );
                $photos = Photo::ListByUser( $user[ 'id' ], $offset, $limit );
                Template( 'photo/listing', compact( 'user', 'photos' ) );
            }
            else {
		        clude( 'models/spot.php' );
                if( $offset != 0 ) {
			        $photos = Photo::ListRecent( $offset, $limit );
		        }
		        else {
			        $ids  = Spot::GetImages( 4005, 100, $offset );
		            if ( is_array( $ids ) ) {
			            $images = Photo::ListByIds( $ids );

						$keys = array();
						$i = 1;
						foreach ( $ids as $id ) {
						    $keys[ $id ] = $i;
						    $i = $i + 1;
						}
						$photos = array();
						foreach ( $images as $image ) {
							$photos[ $keys[ $image[ 'id' ] ] ] = $image;
						}
						ksort( $photos );
		            }
		            else {
			            $photos = Photo::ListRecent( $offset, $limit );
		            }
	            }
                Template( 'photo/listing', compact( 'photos' ) );
            }
        }
        public static function Create( $albumid ) {
            global $settings;

            isset( $_SESSION[ 'user' ] ) or die( 'You must be logged in to upload a picture' );
            isset( $_FILES[ 'uploadimage' ] ) or die( 'No image specified' );

            clude( 'models/db.php' );
            clude( 'models/photo.php' );
            clude( 'models/album.php' );
			clude( 'models/user.php' );
    
            $user = $_SESSION[ 'user' ];
            $userid = $_SESSION[ 'user' ][ 'id' ];

            if ( !$userid ) {
                return;
            }

            $albumid = ( int )$albumid;
            if ( !( $albumid > 0 ) ) {
                clude( 'models/user.php' );
                $albumid = ( int )User::GetEgoAlbumId( $userid );
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
                if ( !in_array( strtolower( $extension ), array( 'jpg', 'jpeg', 'png', 'gif' ) ) ) {
                    $error = "wrongextension";
                    include 'views/photo/create.php';
                    return;
                }
                $tempname = $uploadimage[ 'tmp_name' ];
            }
            
            $photo = Photo::Create( $userid, $albumid, $tempname );
            $photo[ 'userid' ] = $userid;
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

			if ( $album[ 'mainimageid' ] == 0 ) {
				Album::Update( $album[ 'id' ], $album[ 'name' ], $photo[ 'id' ] );
				$egoalbumid = User::GetEgoAlbumId( $photo[ 'userid' ] );
				if ( $egoalbumid == $album[ 'id' ] ) {					
                    User::UpdateAvatarid( $photo[ 'userid' ], $photo[ 'id' ] );
				}
			}
    
            ++$album[ 'numphotos' ]; // updated on db by trigger
            include 'views/photo/create.php';
        }
        public static function Update( $id, $title, $albumid = 0 ) {
            isset( $_SESSION[ 'user' ] ) or die( 'You must be logged in to update a photo' );
            clude( 'models/db.php' );
            clude( 'models/photo.php' );
            clude( 'models/comment.php' );
            clude( 'models/favourite.php' );
            
            $photo = Photo::Item( $id );
            if ( $photo[ 'user' ][ 'id' ] != $_SESSION[ 'user' ][ 'id' ] ) {
                die( 'not your photo' );
            }
            if ( $photo[ 'user' ][ 'deleted' ] === 1 ) { 
                include 'views/itemdeleted.php';
                return;
            }

            if ( $albumid == 0 ) {
                $albumid = $photo[ 'albumid' ];
            }
            else {
                clude( 'models/album.php' );
                clude( 'models/types.php' );
                
                $album = Album::Item( $albumid );
                if ( $album[ 'ownerid' ] != $_SESSION[ 'user' ][ 'id' ] || $album[ 'ownertype' ] != TYPE_USERPROFILE ) {
                    die( 'not your album' );
                }
            }

            if ( empty( $title ) ) {
                $title = $photo[ 'title' ];
            }

            Photo::UpdateDetails( $id, $title, $albumid );

            $photo[ 'title' ] = $title;
            $photo[ 'albumid' ] = $albumid;

            $user = $photo[ 'user' ];

            include 'views/photo/view.php';
        }
        public static function Delete( $id ) {
            isset( $_SESSION[ 'user' ] ) or die( 'You must be logged in to delete a photo' );
            clude( 'models/db.php' );
            clude( 'models/photo.php' );
			clude( 'models/album.php' );
			clude( 'models/user.php' );

            $photo = Photo::Item( $id );
            if ( $photo[ 'user' ][ 'id' ] != $_SESSION[ 'user' ][ 'id' ] ) {
                die( 'not your photo' );
            }
            Photo::Delete( $id );

			$album = Album::Item( $photo[ 'albumid' ] );
			$egoalbumid = User::GetEgoAlbumId( $photo[ 'user' ][ 'id' ] );
			if ( $album[ 'mainimageid' ] == $id ) {
				$album_photos = Photo::ListByAlbum( $photo[ 'albumid' ], 0, 1 );
				$mainimageid = 0;
				if ( empty( $album_photos ) ) {
					$mainimageid = 0;
					Album::Update( $album[ 'id' ], $album[ 'name' ], $mainimageid ); //description missing from model --ted
				}
				else {
					$mainimageid = $album_photos[ 0 ][ 'id' ];
					Album::Update( $album[ 'id' ], $album[ 'name' ], $mainimageid ); //description missing from model --ted
				}
				if ( $photo[ 'albumid' ] == $egoalbumid ) {
					User::UpdateAvatarid( $photo[ 'user' ][ 'id' ], $mainimageid );
				}
			}
        }
    }
?>
