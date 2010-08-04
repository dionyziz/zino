<?php

    class TestActivity extends ModelTestcase {
		protected $mUsers;
		protected $mData;


		public function SetUp() {
            clude( 'models/activity.php' );
            clude( 'models/types.php' );
            clude( 'models/user.php' );
			clude( 'models/favourite.php' );
			clude( 'models/status.php' );
			clude( 'models/comment.php' );
			clude( 'models/journal.php' );

            $this->mUsers = $this->GenerateTestUsers( 2 );
			$userid = $this->mUsers[ 0 ][ 'id' ];
			$userid2 = $this->mUsers[ 1 ][ 'id' ];
			$this->mData = array( 
				array( $userid, "Aoua?", $userid2 )
			);
        }
        public function TearDown() {
            $this->DeleteTestUsers();
        }
		public function PreConditions() {
            $this->AssertClassExists( 'Activity' );
            $this->AssertMethodExists( 'Activity', 'ListByUser' );
        }
        /**
         * @dataProvider GetData
         */
        public function TestCreate( $userid, $text, $userid2 ) {	
			//Status		
			Status::Create( $userid, $text );
			$act = Activity::ListByUser( $userid, 100 );
			$this->AssertIsArray( $act );
			$this->AssertEquals( ( int )$act[ 0 ][ 'typeid' ], ACTIVITY_STATUS, "Activity should be status type" ); 
			$this->AssertEquals( $act[ 0 ][ 'status' ][ 'message' ], $text, "Status Activity: Wrong Text" );

			//Journal create
			$info = Journal::Create( $userid, "Skata", "Methysmena Kswtika" );
			$this->AssertIsArray( $info );
			$journalid = $info[ 'id' ];
			$act = Activity::ListByUser( $userid, 100 );
			$this->AssertIsArray( $act );
			$this->AssertEquals( ( int )$act[ 0 ][ 'typeid' ], ACTIVITY_ITEM, "Activity should be of item type" ); 
			$this->AssertEquals( ( int )$act[ 0 ][ 'item' ][ 'id' ], ( int )$journalid, "Item Activity: Wrong item id" );
			$this->AssertEquals( $act[ 0 ][ 'item' ][ 'title' ], "Skata", "Item Activity: Wrong title" );
			$this->AssertEquals( ( int )$act[ 0 ][ 'item' ][ 'typeid' ], TYPE_JOURNAL, "Item Activity: Wrong item type" );


			//Comment

			$info = Comment::Create( $userid, $text, TYPE_JOURNAL, $journalid, 0 );
			$this->AssertIsArray( $info );
			$id = $info[ 'id' ];
			$text2 = $info[ 'text' ];
			var_dump( $info );
			$act = Activity::ListByUser( $userid, 100 );
			$this->AssertIsArray( $act );
			$this->AssertEquals( ( int )$act[ 0 ][ 'typeid' ], ACTIVITY_COMMENT, "Activity should be of comment type" ); 
			$this->AssertEquals( $act[ 0 ][ 'comment' ][ 'id' ], $id, "Comment Activity: Wrong Text" );
			$this->AssertEquals( $act[ 0 ][ 'comment' ][ 'text' ], $text2, "Comment Activity: Wrong Id" );
			$this->AssertEquals( $act[ 0 ][ 'item' ][ 'typeid' ], TYPE_JOURNAL, "Comment Activity: Wrong Type" );

        }

		public function GetData() {
			return $this->mData;	
		}
	}
	
	return New TestActivity();
?>
