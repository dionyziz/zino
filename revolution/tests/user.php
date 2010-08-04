<?php

    class TestUser extends ModelTestcase {
		protected $mData;


		public function SetUp() {
            clude( 'models/journal.php' );
            clude( 'models/types.php' );
            clude( 'models/user.php' );


			$this->mData = array( 
				array( "mitsaras223", "apap@rigo.com", "hellsbell" ),
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
        public function TestCreate( $name, $email, $password ) {	
			$info = User::Create( $name, $email, $password );
			$this->Assert( $info, "User could not be created" );
			$userid = $info;
			$user = User::Item( $userid );
			$this->AssertIsArray( $user, "User::Item should return an array." );
			$this->AssertEquals( $user[ 'name' ], $name, "User::Create.Wrong Name." );

			return $user;
        }
		/**
         * @producer TestCreate
         */
        public function TestDelete( $user ) {
            $userid = $user[ 'id' ];
			User::Delete( $userid );
			$user = User::Item( $userid );
			$this->AssertFalse( $user, "User::Delete.User should have been deleted." );
        }

		public function GetData() {
			return $this->mData;	
		}
	}
	
	return New TestUser();
?>
