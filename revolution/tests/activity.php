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
			var_dump( $act );
			$this->AssertEquals( $act[ 0 ][ 'typeid' ], 6, "Activity should be status type" ); 
			$this->AssertEquals( $act[ 0 ][ 'status' ][ 'message' ], $text, "Wrong Text" );
        }

		public function GetStatusData() {
			return $this->mStatusData;	
		}
	}
	
	return New TestActivity();
?>
