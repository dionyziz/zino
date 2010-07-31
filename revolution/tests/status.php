<?php

    class TestStatus extends ModelTestcase {
		protected $mUsers;
		protected $mStatusData;


		public function SetUp() {
            clude( 'models/user.php' );
			clude( 'models/status.php' );

            $this->mUsers = $this->GenerateTestUsers( 1 );
			$userid = $this->mUsers[ 0 ][ 'id' ];
			$this->mStatusData = array( 
				array( $userid, "Aoua?" ),
				array( $userid, "What?" )
			);
        }
        public function TearDown() {
            $this->DeleteTestUsers();
        }
		public function PreConditions() {
            $this->AssertClassExists( 'Status' );
            $this->AssertMethodExists( 'Status', 'Create' );
        }
        /**
         * @dataProvider GetStatusData
         */
        public function TestCreate( $userid, $text ) {			
			Status::Create( $userid, $text );
			$details = User::ItemDetails( $userid );
			$this->AssertEquals( $details[ "status" ], $text, "Status : Wrong Text" );
        }

		public function GetStatusData() {
			return $this->mStatusData;	
		}
	}
	
	return New TestStatus();
?>
