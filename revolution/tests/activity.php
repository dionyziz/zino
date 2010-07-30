<?php

    class TestActivity extends ModelTestcase {
		public function SetUp() {
            clude( 'models/activity.php' );
            clude( 'models/types.php' );
            clude( 'models/user.php' );
			clude( 'models/favourite.php' );

            $this->GenerateTestUsers( 1 );
        }
        public function TearDown() {
            $this->DeleteTestUsers();
        }
		public function PreConditions() {
            $this->AssertClassExists( 'Activity' );
            $this->AssertMethodExists( 'Activity', 'ListByUser' );
        }
        /**
         * @dataProvider ExampleData
         */
        public function TestCreate( $userid, $typeid, $itemid ) {
			//create favourite
			//get last asctivity
			//assert has keys and values
			//return favorite		
			//Favourite::Create( $userid, $typeid, $itemid );
			$act = Activity::ListByUser( $userid, 1 );
			$this->AssertArrayHasKeys( $act, array( 'favourite', 'item' ) );
			$this->AssertArrayHasKeys( $act[ 'item' ], array( 'typeid', 'bulkid', 'title', 'url' ) );
        }


	}
?>
