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
			$this->mStatusData = array( 
				array( $userid, "Aoua?" )
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
         * @dataProvider GetStatusData
         */
        public function TestCreate( $userid, $text ) {			
			Status::Create( $userid, $text );
			$act = Activity::ListByUser( $userid, 100 );
			$this->AssertIsArray( $act );
			$this->AssertArrayHasKeys( $act[ 0 ], array( 'typeid', 'user' ), 'array returned doesnt have the correct keys' );
			$success = false;
			foreach ( $act as $sam ) {
				if ( $sam[ 'typeid' ] == ACTIVITY_STATUS ) {
						//&& strcmp( $sam[ 'status' ][ 'message' ], $text ) == 0 ) {
					$success = true;
				}
			}
			$this->AssertEquals( $sucess, true );//activity exists
        }

		public function GetStatusData() {
			return $this->mStatusData;	
		}
	}
	
	return New TestActivity();
?>
