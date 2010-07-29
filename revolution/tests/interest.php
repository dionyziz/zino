<?php

    class TestInterest extends ModelTestcase {
        protected $mUsers;
        protected $mTagData;

        public function SetUp() {
            clude( 'models/user.php' );
            clude( 'models/interest.php' );

            $this->mUsers = $this->GenerateTestUsers( 2 );    
            $userid1 = $this->mUsers[ 0 ][ 'id' ];
            $userid2 = $this->mUsers[ 1 ][ 'id' ];
            $this->mTagData = array(
                array( $userid1, 'foo', TAG_HOBBIE  ),
                array( $userid1, 'bar', TAG_MOVIE ),
                array( $userid1, '', TAG_BOOK ),
                array( $userid2, 'hello', TAG_SONG ),
                array( $userid2, 'haha', TAG_ARTIST ),
                array( $userid1, 'THE GAME', TAG_GAME ),
                array( $userid1, 'foobar', TAG_SHOW )
            );
        }
        public function TearDown() {
            $this->DeleteTestUsers();
        }
        /**
         * @dataProvider GetTagData
         */
        public function TestCreate( $userid, $text, $typeid ) {
            $tagid = Interest::Create( $userid, $text, $typeid );
            $this->Assert( is_int( $tagid ) );
            $this->Assert( $tagid > 0 );

            return array( $tagid, $userid );
        }
        /**
         * @dataProvider GetTagDataByUser
         */
        public function TestListByUser( $tags ) {
            $userid = $tags[ 0 ][ 0 ];
            $got = Interest::ListByUser( $userid );
            $this->AssertIsArray( $got );
            $this->AssertEquals( count( $tags ), count( $got ) );
        }
        /**
         * @producer TestCreate
         */
        public function TestDelete( $info ) {
            $tagid = $info[ 0 ];
            $userid = $info[ 1 ];

            $success = Interest::Delete( (int)$tagid );
            $this->Assert( $success );

            $tags = Interest::ListByUser( $userid );
            $found = false;
            foreach ( $tags as $tag ) {
                if ( $tag[ 'id' ] == $tagid ) {
                    echo "found $tagid\n";
                    $found = true;
                }
            }

            $this->Assert( !$found, 'Interest::ListByUser returned deleted interest' );
        }
        public function GetTagData() {
            return $this->mTagData;
        }
        public function GetTagDataByUser() {
            $userid1 = $this->mUsers[ 0 ][ 'id' ];
            $userid2 = $this->mUsers[ 1 ][ 'id' ];

            $byUser = array( $userid1 => array(), $userid2 => array() );
            foreach ( $this->mTagData as $tag ) {
                $byUser[ $tag[ 0 ] ][ 0 ][] = $tag;
            }

            return $byUser;
        }
    }

    return New TestInterest();

?>
