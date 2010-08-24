<?php

    class ImageTagTest extends ModelTestcase {
        protected $mTestUsers;

        public function SetUp() {
            clude( 'models/user.php' );
            clude( 'models/album.php' );
            clude( 'models/photo.php' );
            clude( 'models/imagetag.php' );

            $this->DownloadFileTo( 'http://www.google.com/images/logos/logo.gif', 'googlelogo.gif' );
            $this->DownloadFileTo( 'http://static.zino.gr/phoenix/logo-trans.png', 'zinologo.png' );

            $users = $this->GenerateTestUsers( 2 );
            foreach ( $this->mTestUsers as $i => $user ) {
                $album = Album::Create( $user[ 'id' ], 'testalbum', '' );
                $users[ $i ][ 'album' ] = $album;

                $imagename = $i == 0 ? 'googlelogo.gif' : 'zinologo.png';

                $users[ $i ][ 'photo' ] = Photo::Create( $user[ 'id' ], $album[ 'id' ], $imagename );
            }

            $this->mTestUsers = $users;
        }
        public function TearDown() {
            unlink( 'googlelogo.gif' );
            unlink( 'zinologo.png' );
            unlink( 'twitterlogo.png' );

            $this->DeleteTestUsers();
        }
        public function PreConditions() {
            $this->AssertClassExists( 'ImageTag' );
            $this->AssertMethodExists( 'ImageTag', 'Create' );
            $this->AssertMethodExists( 'ImageTag', 'ListByPhoto' );
            $this->AssertMethodExists( 'ImageTag', 'Delete' );
        }
        /**
         * @dataProvider ExampleData
         */
        public function TestCreate( $personid, $photoid, $ownerid, $top, $left, $width, $height ) {
            $tag = ImageTag::Create( $personid, $photoid, $ownerid, $top, $left, $width, $height );
            $this->AssertIsArray( $details );
            $this->AssertArrayHasKeys( $tag, array( 'id', 'personid', 'person', 'photoid', 'photo', 'top', 'left', 'width', 'height' ) );
            $this->AssertArrayValues( $tag, compact( 'personid', 'photoid', 'top', 'left', 'width', 'height' ) );
        }
        public function TestListByPhoto() {
        }
        public function TestDelete() {
        }
        public function ExampleData() {
            $user0 = $this->mTestUsers[ 0 ];
            $user1 = $this->mTestUsers[ 1 ];
            return array(
                array( $user0[ 'id' ], $user1[ 'photo' ][ 'id' ], $user1[ 'id' ], 0, 0, 100, 10 ),
                array( $user0[ 'id' ], $user0[ 'photo' ][ 'id' ], $user0[ 'id' ], 0, 0, 5, 6 ),
                array( $user1[ 'id' ], $user0[ 'photo' ][ 'id' ], $user1[ 'id' ], 10, 10, 50, 50 )
            );
        }
        public function DownloadFileTo( $file, $to ) {
            $ch = curl_init();
            $timeout = 5;
            curl_setopt( $ch, CURLOPT_URL, $file );
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
            curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, $timeout );
            $data = curl_exec( $ch );
            if ( !$data ) {
                throw New Exception( curl_error( $ch ) );
            }
            curl_close( $ch );
            file_put_contents( $to, $data );
        }
    }

    return New ImageTagTest();

?>
