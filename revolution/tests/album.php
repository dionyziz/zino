<?php
    // $this->AssertArrayHasKeys( $ret, array( 'id', 'ownerid', 'mainimageid', 'name', 'description', 'delid', 'numcomments', 'numphotos' ) );

    class TestAlbum extends TestCase {
        protected $mAppliesTo = 'models/album';

        public function SetUp() {
            echo "setting up...";
            clude( 'models/album.php' );
            clude( 'models/types.php' );
            echo "ok\n";
        }
        public function PreConditions() {
            echo "testing preconditions...";
            $this->AssertClassExists( 'Album' );
            $this->AssertMethodExists( 'Album', 'Item' );
            $this->AssertMethodExists( 'Album', 'Create' );
            echo "ok\n";
        }
        /**
         * @dataProvider ValidIds
         */
        public function TestItem( $id ) {
            echo "testing item...";
            $ret = $this->Call( 'Album::Item', array( $id ) );
            $this->AssertArrayHasKeys( $ret, array( 'id', 'ownerid', 'mainimageid' ) );
            $this->AssertArrayValues( $ret, array( 'id' => (string)$id ) );
            echo "ok\n";
        }
        /**
         * @dataProvider ExampleData
         */
        public function TestCreate( $userid, $name, $description ) {
            echo "calling...";
            $album = $this->Call( 'Album::Create', array( $userid, $name, $description ) );
            echo "ok\n";
            echo "checking keys...\n";
            $this->AssertArrayHasKeys( $album, array( 'id', 'url', 'created' ) );
            echo "ok\n";
            
            echo "checking values...\n";
            $this->AssertArrayValues( $album, array(
                'ownerid' => $userid,
                'name' => $name,
                'description' => $description,
                'mainimageid' => 0,
                'delid' => 0,
                'numcomments' => 0,
                'numphotos' => 0
            ) );
            echo "ok\n";

            return $album;
        }
        /**
         * @producer TestCreate
         */
        public function TestDelete( $album ) {
            echo "testing delete...";
            Album::Delete( $album[ 'id' ] );
            echo "ok\n";
        }
        public function ValidIds() {
            echo "getting ids\n";
            $res = db( 'SELECT `album_id` FROM `albums` ORDER BY RAND() LIMIT 3;' );
            $ret = array();
            while ( $row = mysql_fetch_array( $res ) ) {
                $ret[] = (int)$row[ 0 ];
            }
            echo "got ids\n";
            return $ret;
        }
        public function ExampleData() {
            return array(
                array( 1, 'kamibu summer meeting', 'photos from our meeting at ioannina' ),
                // array( 658, 'barcelona', 'I love this place' ),
                // array( 658, 'rome', '' ),
            );
        }
    }

    return New TestAlbum();

?>
