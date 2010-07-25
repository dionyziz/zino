<?php
    // $this->AssertArrayHasKeys( $ret, array( 'id', 'ownerid', 'mainimageid', 'name', 'description', 'delid', 'numcomments', 'numphotos' ) );

    class TestAlbum extends TestCase {
        protected $mAppliesTo = 'models/album';

        public function SetUp() {
            clude( 'models/album.php' );
            clude( 'models/types.php' );
            clude( 'models/user.php' );

            // $this->GenerateTestUsers( 2 );
        }
        public function PreConditions() {
            $this->AssertClassExists( 'Album' );
            $this->AssertMethodExists( 'Album', 'Item' );
            $this->AssertMethodExists( 'Album', 'Create' );
            $this->AssertMethodExists( 'Album', 'Delete' );
        }
        /**
         * @dataProvider ValidIds
         * @covers Album::Item
         */
        public function TestItem( $id ) {
            $album = Album::Item( $id );
            $this->AssertArrayHasKeys( $album, array( 'id', 'ownerid', 'mainimageid' ) );
            $this->AssertArrayValues( $album, array( 'id' => $id ) );
        }
        /**
         * @dataProvider ExampleData
         * @covers Album::Create
         */
        public function TestCreate( $userid, $name, $description ) {
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
         * @covers Album::Update
         */
        public function TestUpdate( $ownerid, $name, $description, $album ) {
            $id = $album[ 'id' ];
            $mainimageid = 0;
            $success = Album::Update( $album, $name, $description, $mainimageid );
            $this->Assert( $success, 'Album::Update failed' );

            $album = Album::Item( $album[ 'id' ] );
            $this->Called( "Album::Item" );
            $this->AssertArrayValues( $album, array(
                'id' => $id,
                'name' => $name,
                'description' => $description,
                'mainimageid' => $mainimageid
            ) );
            return $album;
        }
        /**
         * @dataProvider RandomUsers
         * @covers Album::ListItemsByUser
        public function TestListItemsByUser( $user ) {
            $albums = $this->ListItemsByUser( $user );
            $this->AssertIsArray( $albums );
        }
         */

        public function TestDelete( $album ) {
            $id = (int)$album[ 'id' ];
            $success = Album::Delete( $id );
            $this->Assert( $success, 'Album::Delete failed' );
            $this->AssertIsArray( $success );

            $album = Album::Item( $id );
            $this->Called( "Album::Item" );
            // $this->AssertFalse( $album, 'Album::Item should return false on deleted item' );
            // $this->AssertIsArray( $album );
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
            return $this->RandomValues( array(
                array( 1, 'kamibu summer meeting', 'photos from our meeting at ioannina' ),
                array( 658, 'barcelona', 'I love this place' ),
                array( 658, 'rome', '' ),
                array( 658, 'test', 'haha' ),
                array( 658, 'foobar', '' ),
                array( 658, 'red green', 'blue' ),
                array( 658, 'hello', 'world' )
            ), 3 );
        }
    }

    return New TestAlbum();

?>
