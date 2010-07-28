<?php

    class TestPhoto extends ModelTestcase {
        protected $mCovers = 'Photo';
        protected $mAlbumsByUser = array();
        protected $mImages;

        public function SetUp() {
            clude( 'models/user.php' );
            clude( 'models/album.php' );
            clude( 'models/photo.php' );

            $this->DownloadFileTo( 'http://www.google.com/images/logos/logo.gif', 'googlelogo.gif' );
            $this->DownloadFileTo( 'http://static.zino.gr/phoenix/logo-trans.png', 'zinologo.png' );
            $this->DownloadFileTo( 'http://a3.twimg.com/a/1279901730/images/fronts/logo.png', 'twitterlogo.png' );
            $users = $this->GenerateTestUsers( 3 );
            foreach ( $users as $user ) {
                $album = Album::Create( $user[ 'id' ], $user[ 'name' ] . 'album', '' );
                $this->mAlbumsByUser[ $user[ 'id' ] ] = $album;
            }

            $this->mImages = array( 
                array( 'googlelogo.gif', 103, 40, 1991 ),
                array( 'zinologo.png', 92, 57, 2425 ),
                array( 'twitterlogo.png', 224, 55, 5021 )
            );
        }
        public function TearDown() {
            unlink( 'googlelogo.gif' );
            unlink( 'zinologo.png' );
            unlink( 'twitterlogo.png' );

            $this->DeleteTestUsers();
        }
        /**
         * @dataProvider ImageProvider
         */
        public function TestCreate( $userid, $albumid, $image ) {
            $filename = $image[ 0 ];
            $width = $image[ 1 ];
            $height = $image[ 2 ];
            $filesize = $image[ 3 ];
            $data = Photo::Create( $userid, $albumid, $filename );
            $this->AssertIsArray( $data );
            $this->Assert( is_int( $data[ 'id' ] ) );
            $this->AssertEquals( $width, $data[ 'width' ] );
            $this->AssertEquals( $height, $data[ 'height' ] );
            $this->AssertEquals( $filesize, $data[ 'filesize' ] );

            $photo = Photo::Item( $data[ 'id' ] );
            $this->Called( 'Photo::Item' );
            $this->AssertIsArray( $photo );
            $this->AssertArrayHasKeys( $photo, array( 'id', 'w', 'h', 'albumid', 'title', 'user' ) );
            $this->AssertArrayValues( $photo, array(
                'w' => $data[ 'width' ],
                'h' => $data[ 'height' ],
                'albumid' => $albumid,
                'title' => ''
            ) );

            return $photo;
        }
        public function TestUpdateDetails( $photo ) {
            $title = 'testupdatedetails' . rand( 0, 100 );
            $album = Album::Create( $photo[ 'user' ][ 'id' ], 'testphotoalbum', '' );
            $success = Photo::UpdateDetails( $photo[ 'id' ], $title, $album[ 'id' ] );
            $this->Assert( is_bool( $success ) );
            $this->Assert( $success );

            $photo = Photo::Item( $photo[ 'id' ] );
            $this->AssertArrayHasKeys( $photo, array( 'id', 'w', 'h', 'albumid', 'title', 'user' ) );
            $this->AssertArrayValues( $photo, array(
                'w' => $photo[ 'w' ],
                'h' => $photo[ 'h' ],
                'albumid' => $album[ 'id' ],
                'title' => $title
            ) );

            return $photo;
        }
        public function TestUpdateFileInformation( $photo ) {
            $width = 100;
            $height = 200;
            $filesize = 2000;
            $mime = 'image/png';

            $success = Photo::UpdateFileInformation( $photo[ 'id' ], $width, $height, $filesize, $mime );
            $this->Assert( $success );

            $photo = Photo::Item( $photo[ 'id' ] );
            $this->AssertIsArray( $photo );
            $this->AssertArrayHasKeys( $photo, array( 'id', 'w', 'h', 'albumid', 'title', 'user' ) );
            $this->AssertArrayValues( $photo, array(
                'w' => $width,
                'h' => $height,
                'albumid' => $photo[ 'albumid' ],
                'title' => $photo[ 'title' ],
            ) );

            return $photo;
        }
        public function TestDelete( $photo ) {
            $success = Photo::Delete( $photo[ 'id' ] );
            $this->Assert( $success );

            $photo = Photo::Item( $success );
            $this->AssertFalse( $photo );
        }
        public function TestDeleteNonExistent() {
            $success = Photo::Delete( 0 );
            $this->AssertFalse( $success );
        }
        public function TestListByAlbum() {
            $user = $this->GetRandomTestUser();
            $images = $this->mImages;
            $album = Album::Create( $user[ 'id' ], 'findbyalbumtest', '' );
            $data = array();
            foreach ( $images as $image ) {
                $data[] = Photo::Create( $user[ 'id' ], $album[ 'id' ], $image[ 0 ] );
            }
         
            $photos = Photo::ListByAlbum( $album[ 'id' ] );
            $this->AssertIsArray( $photos );
            $this->AssertEquals( count( $images ), count( $photos ) );
            // TODO: better testing here

            $photos = Photo::ListByAlbum( $album[ 'id' ], $offset = 1, $limit = 1 );
            $this->AssertIsArray( $photos );
            $this->AssertEquals( 1, count( $photos ) );

            foreach ( $data as $photo ) {
                Photo::Delete( $photo[ 'id' ] );
            }
            Album::Delete( $album[ 'id' ] );
        }
        public function TestListRecent() {
            $user = $this->GetRandomTestUser();
            $images = $this->mImages;
            $album = Album::Create( $user[ 'id' ], 'findbyalbumtest', '' );
            $data = array();
            foreach ( $images as $image ) {
                $data[] = Photo::Create( $user[ 'id' ], $album[ 'id' ], $image[ 0 ] );
            }

            $photos = Photo::ListRecent();
            $this->AssertIsArray( $photos );
            // $this->AssertEquals( 100, count( $photos ) );
            $this->AssertEquals( $data[ 2 ][ 'id' ], (int)$photos[ 0 ][ 'id' ] );
            $this->AssertEquals( $data[ 1 ][ 'id' ], (int)$photos[ 1 ][ 'id' ] );
            $this->AssertEquals( $data[ 0 ][ 'id' ], (int)$photos[ 2 ][ 'id' ] );

            foreach ( $data as $photo ) {
                Photo::Delete( $photo[ 'id' ] );
            }
            Album::Delete( $album[ 'id' ] );
        }
        /**
         * @dataProvider UserProvider
         */
        public function TestListByUser( $userid, $name, $email ) {
            $images = $this->mImages;
            $album = Album::Create( $userid, 'findbyalbumtest', '' );
            $data = array();
            foreach ( $images as $image ) {
                $data[] = Photo::Create( $userid, $album[ 'id' ], $image[ 0 ] );
            }

            $photos = Photo::ListByUser( $userid );
            $this->AssertIsArray( $photos );
            $this->AssertEquals( count( $data ), count( $photos ) );
            // TODO

            foreach ( $data as $photo ) {
                Photo::Delete( $photo[ 'id' ] );
            }
            Album::Delete( $album[ 'id' ] );
        }
        public function ImageProvider() {
            $params = array();
            foreach ( $this->mImages as $image ) {
                $user = $this->GetRandomTestUser();
                $album = $this->mAlbumsByUser[ $user[ 'id' ] ];
                $params[] = array( $user[ 'id' ], $album[ 'id' ], $image );
            }
            
            return $params;
        }
        public function DownloadFileTo( $file, $to ) {
            $ch = curl_init();
            $timeout = 5;
            curl_setopt( $ch, CURLOPT_URL, $file );
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
            curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, $timeout );
            $data = curl_exec( $ch );
            if ( !$data ) {
                die( curl_error( $ch ) );
            }
            curl_close( $ch );
            file_put_contents( $to, $data );
        }
    }

    return New TestPhoto();

?>
