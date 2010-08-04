<?php

    class TestPlace extends ModelTestcase {
		protected $mUsers;
		protected $mData;


		public function SetUp() {
            clude( 'models/place.php' );
            clude( 'models/types.php' );
            clude( 'models/user.php' );
			clude( 'models/url.php' );
        }
        public function TearDown() {
        }
		public function PreConditions() {
            $this->AssertClassExists( 'Place' );
            $this->AssertMethodExists( 'Place', 'Listing' );
        }
        /**
         * @dataProvider GetData
         */
        public function TestListing() {		
			$places = Place::Listing();
			$this->AssertIsArray( $places, "Place::Listing should return an array" );
			$this->AssertFalse( empty( $places ), "Place::Listing should not return an empty array" );
			foreach ( $places as $place ) {
				$this->Assert( $place[ 'id' ] > 0, "Places ids should be positive numbers" );
			}
        }
		public function GetData() {
			return;	
		}
	}
	
	return New TestPlace();
?>
