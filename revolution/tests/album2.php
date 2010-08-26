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
            $this->AssertArrayValues( $album, array( 'id' => ( string )$id ) );
        }


        public function ValidIds( $num = 3 ) {
            $res = db( 'SELECT `album_id` FROM `albums` ORDER BY RAND() LIMIT ' . ( string )$num );
            $ret = array();
            while ( $row = mysql_fetch_array( $res ) ) {
                $ret[] = (int)$row[ 0 ];
            }
            return $ret;
        }
    }

    return New TestAlbum();

?>
