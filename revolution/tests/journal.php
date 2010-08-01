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
				array( $userid, "Eftase h wra", "Na fygoume" )
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
        public function TestCreate( $userid, $text ) {		
			$info = Journal::Create( $userid, $title, $text );
			$this->AssertIsArray( $info );
			$id = $info[ 'id' ];
			$item = Journal::Item( $id );
			$this->AssertIsArray( $info );
			$this->AssertEquals( $item[ "title" ], $title, "Wrong Title" );
			$this->AssertEquals( $item[ "text" ], $text, "Wrong Text" );
			$this->AssertEquals( $item[ "user" ][ "id" ], $userid, "Wrong Creator" );
        }

		public function GetData() {
			return $this->mStatusData;	
		}
	}
	
	return New TestJournal();
?>
