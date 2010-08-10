<?php

    class TestAlbum extends ModelTestCase {
        protected $mAppliesTo = 'models/album';
        protected $mCovers = 'Album';

        public function SetUp() {
            clude( 'models/album.php' );
            clude( 'models/types.php' );
            clude( 'models/user.php' );

            $this->GenerateTestUsers( 3 );
        }
        public function TearDown() {
            $this->DeleteTestUsers();
        }
        public function PreConditions() {
            $this->AssertClassExists( 'Album' );
            $this->AssertMethodExists( 'Album', 'Item' );
            $this->AssertMethodExists( 'Album', 'Create' );
            $this->AssertMethodExists( 'Album', 'Delete' );
        }
        /**
         * @dataProvider ValidIds
         */
        public function TestItem( $id ) {
            $album = Album::Item( $id );
            $this->AssertArrayHasKeys( $album, array( 'id', 'ownerid', 'mainimageid' ) );
            $this->AssertArrayValues( $album, array( 'id' => $id ) );
        }
        /**
         * @dataProvider ExampleData
         */
        public function TestCreate( $userid, $name, $description ) {
			return;
            $album = Album::Create( $userid, $name, $description );
            $this->AssertArrayHasKeys( $album, array( 'id', 'url', 'created' ) );
            $this->AssertArrayValues( $album, array(
                'ownerid' => $userid,
                'name' => $name,
                'description' => $description,
                'mainimageid' => 0,
                'delid' => 0,
                'numcomments' => 0,
                'numphotos' => 0
            ) );

            $id = $album[ 'id' ];
            $album = Album::Item( $id );
            $this->Called( "Album::Item" );
            $this->AssertArrayValues( $album, array(
                'id' => $id,
                'ownerid' => $userid,
                'name' => $name,
                'description' => $description
            ) );

            return $album; // pass to TestUpdate
        }
        /**
         * @dataProvider ExampleData
         */
        public function TestUpdate( $ownerid, $name, $description, $albumid, $mainimageid = 0  ) {
            // ignore ownerid 
			echo "In update";
			$album = Album::Item( $albumid );
            $id = $album[ 'id' ];
			$oldname = $album[ 'name' ];
            $success = Album::Update( $id, $name, $description, $mainimageid );
            $this->Assert( $success, 'Album::Update failed' );

            $album = Album::Item( $album[ 'id' ] );
            $this->Called( "Album::Item" );
			if ( User::GetEgoAlbumId( $ownerid )  != $albumid ) {
		        $this->AssertArrayValues( $album, array(
		            'id' => $id,
		            'name' => $name,
		            'description' => $description,
		            'mainimageid' => $mainimageid
		        ) );
			}
			else {
				$this->AssertArrayValues( $album, array(
		            'id' => $id,
		            'name' => $oldname,
		            'description' => $description,
		            'mainimageid' => $mainimageid
		        ) );
			}
			echo "Off update";
            return array( $album[ 'ownerid' ], $album );
        }
        public function TestListByUser( $info ) {
			return;
            $userid = $info[ 0 ];
            $thealbum = $info[ 1 ];

            $albums = Album::ListByUser( (int)$userid );
            $this->AssertIsArray( $albums );

            $found = false;
            foreach ( $albums as $album ) {
                $this->AssertEquals( $userid, $album[ 'ownerid' ] );
                if ( $album[ 'id' ] == $thealbum[ 'id' ] ) {
                    $this->AssertArrayValues( $album, array(
                        'ownerid' => (int)$thealbum[ 'ownerid' ],
                        'name' => $thealbum[ 'name' ],
                        'numphotos' => $thealbum[ 'numphotos' ],
                        'mainimageid' => $thealbum[ 'mainimageid' ]
                    ) );
                    $found = true;
                }
            }

            $this->Assert( $found, 'Album::ListByUser failed to list all albums for userid ' . $userid . ' ' . $thealbum[ 'id' ] );
        }
        /**
         * @producer TestUpdate
         */
        public function TestDelete( $info ) {
			return;
            $album = $info[ 1 ];

            $id = (int)$album[ 'id' ];
            $success = Album::Delete( $id );
            $this->Assert( $success, 'Album::Delete failed' );

            $album = Album::Item( $id );
            $this->Called( "Album::Item" );
            $this->AssertFalse( $album, 'Album::Item should return false on deleted item' );

            // test on invalid album
            $success = Album::Delete( 0 );
            $this->AssertFalse( $success, 'Album::Delete succeeded on non-existing album' );
        }
        public function ValidIds() {
            $res = db( 'SELECT `album_id` FROM `albums` ORDER BY RAND() LIMIT 3;' );
            $ret = array();
            while ( $row = mysql_fetch_array( $res ) ) {
                $ret[] = (int)$row[ 0 ];
            }
            return $ret;
        }
        public function ExampleData() {
            $users = $this->GetTestUsers();
            $userid = (int)( $users[ 0 ][ 'id' ] );
            $userid1 = (int)( $users[ 1 ][ 'id' ] );
            $userid2 = (int)( $users[ 2 ][ 'id' ] );
			$egoalbum1 = User::GetEgoAlbumId( $user[ 'id' ] );
			return $this->RandomValues( array(
                array( $userid, 'barcelona', 'I love this place', $egoalbum1  )
				)
			);

            return $this->RandomValues( array(
                array( $userid, 'kamibu summer meeting', 'photos from our meeting at ioannina' ),
                array( $userid, 'barcelona', 'I love this place' ),
                array( $userid1, 'rome', '' ),
                array( $userid1, 'test', 'haha' ),
                array( $userid2, 'foobar', '' ),
                array( $userid2, 'red green', 'blue' ),
                array( $userid1, 'hello', 'world' )
            ), 3 );
        }
    }

    return New TestAlbum();

?>
