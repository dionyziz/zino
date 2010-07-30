<?php

    class TestActivity extends ModelTestcase {
		protected $mUsers;
		protected $mStatusData;


		public function SetUp() {
            clude( 'models/activity.php' );
            clude( 'models/types.php' );
            clude( 'models/user.php' );
			clude( 'models/favourite.php' );
			clude( 'models/status.php' );

            $this->mUsers = $this->GenerateTestUsers( 1 );
			$userid = $this->mUsers[ 0 ][ 'id' ];
			$mStatusData = array( 
				array( $userid, "Aoua?" ),
				array( $userid, "Pou kai pote" )
			);
        }
        /*public function TearDown() {
            $this->DeleteTestUsers();
        }*/
		public function PreConditions() {
            $this->AssertClassExists( 'Activity' );
            $this->AssertMethodExists( 'Activity', 'ListByUser' );
        }
        /**
         * @dataProvider GetStatusData
         */
        public function TestCreate( $userid, $text ) {			
			Status::Create( $userid, $text );
			$act = Activity::ListByUser( $userid, 1 );
			$this->AssertArrayHasKeys( $act[ 0 ], array( 'status', 'typeid', 'user' ) );
			$this->AssertArrayHasKeys( $act[ 0 ][ 'status' ], array( 'message' ) );
			$this->AssertEquals( $act[ 0 ][ 'status' ][ 'message' ], $text );
			$this->AssertEquals( $act[ 0 ][ 'typeid' ], ACTIVITY_STATUS );
        }

		public function GetStatusData() {
			return $mStatusData;	
		}
	}
	
	return New TestActivity();
?>
