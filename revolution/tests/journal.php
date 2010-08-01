<?php

    class TestJournal extends ModelTestcase {
		protected $mUsers;
		protected $mData;


		public function SetUp() {
            clude( 'models/journal.php' );
            clude( 'models/types.php' );
            clude( 'models/user.php' );


            $this->mUsers = $this->GenerateTestUsers( 1 );
			$userid = $this->mUsers[ 0 ][ 'id' ];
			$this->mData = array( 
				array( $userid, "Eftase hwra?", "Na fygoume" ),
				array( $userid, "Pws", "Alla kai gt" )
			);
        }
        public function TearDown() {
            $this->DeleteTestUsers();
        }
		public function PreConditions() {
            $this->AssertClassExists( 'Journal' );
            $this->AssertMethodExists( 'Journal', 'Create' );
        }
        /**
         * @dataProvider GetData
         */
        public function TestCreate( $userid, $title, $text ) {		
			$info = Journal::Create( $userid, $title, $text );
			$this->AssertIsArray( $info );
			$id = $info[ 'id' ];
			$item = Journal::Item( $id );
			$this->AssertIsArray( $item );
			$this->AssertEquals( $item[ "title" ], $title, "Wrong Title" );
			$this->AssertEquals( $item[ "text" ], $text, "Wrong Text" );
			$this->AssertEquals( ( int )$item[ "user" ][ "id" ], $userid, "Wrong Creator" );
			return $item;
        }
		/**
         * @producer TestCreate
         */
        public function TestDelete( $info ) {
            $id = (int)$info[ 'id' ];
            $success = Journal::Delete( $id );
            $this->Assert( $success, 'Journal::Delete failed' );

            $journal = Journal::Item( $id );
            $this->Called( "Journal::Item" );
            $this->AssertFalse( $journal, 'Journal::Item should return false on deleted item' );

            // test on invalid album
            $success = Journal::Delete( 0 );
            $this->Assert( $success, 'Journal::Delete didnt succeeded on non-existing journal' );
        }

		public function GetData() {
			return $this->mData;	
		}
	}
	
	return New TestJournal();
?>
