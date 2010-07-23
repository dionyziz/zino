<?php

    function clude( $path ) {
        static $included = array();
        if ( !isset( $included[ $path ] ) ) {
            $included[ $path ] = true;
            return include $path;
        }
        return true;
    }

    global $settings;
    $settings = include 'settings.php';
    include_once 'models/db.php';
    include_once 'models/album.php';
    include_once 'models/types.php';
    
    // require 'PHPUnit/Framework.php';
    
    class AlbumModelTest extends PHPUnit_Framework_Testcase {
        public function setUp() {
        }
        /** 
          * @dataProvider validIds
          */
        public function testItem( $id ) {
            $album = Album::Item( $id );
            $this->assertTrue( is_array( $album ) );
            $this->assertEquals( $album[ 'id' ], $id );
        }
        /**
          * @dataProvider exampleData
          */
        public function testCreate( $ownerid, $name, $description ) {
            $album = Album::Create( $ownerid, $name, $description );
            $this->AssertTrue( is_array( $album ) );
            $this->AssertArrayHasKey( 'ownerid', $album );
            $this->AssertGreaterThan( 0, $album[ 'id' ] );
            $this->AssertEquals( $ownerid, $album[ 'ownerid' ] );
            $this->AssertEquals( $name, $album[ 'name' ] );
            $this->AssertEquals( $description, $album[ 'description' ] );
            $this->AssertEquals( TYPE_USERPROFILE, $album[ 'ownertype' ] );
            $this->AssertEquals( 0, $album[ 'mainimageid' ] );
            $this->AssertEquals( 0, $album[ 'delid' ] );
            $this->AssertEquals( 0, $album[ 'numcomments' ] );
            $this->AssertEquals( 0, $album[ 'numphotos' ] );

            $album = Album::Item( $album[ 'id' ] );
            $this->AssertEquals( $ownerid, $album[ 'ownerid' ] );
            $this->AssertEquals( $name, $album[ 'name' ] );
            $this->AssertEquals( $description, $album[ 'description' ] );

            echo "id: " . $album[ 'id' ];

            return $album;
        }
        /**
          * @depends testCreate
          */
        public function testDelete( array $album ) {
            var_dump( $album );
            $id = $album[ 'id' ];
            $ok = Album::Delete( $id );
            $this->AssertTrue( $ok );
            
            $album = Album::Item( $album[ 'id' ] );
            $this->AssertFalse( $album );
        }
        public function validIds() {
            return db_array( 'SELECT `album_id` FROM `albums` ORDER BY RAND() LIMIT 3;' );
        }
        public function exampleData() {
            return array(
                array( 1, 'kamibu summer meeting', 'photos from our meeting at ioannina' ),
                array( 658, 'barcelona', 'I love this place' ),
                array( 658, 'rome', '' ),
            );
        }
    }

?>
