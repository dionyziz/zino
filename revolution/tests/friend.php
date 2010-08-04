<?php

    class TestFriend extends ModelTestcase {
		protected $mUsers;
		protected $mData;


		public function SetUp() {
            clude( 'models/friend.php' );
            clude( 'models/types.php' );
            clude( 'models/user.php' );


            $this->mUsers = $this->GenerateTestUsers( 2 );
			$userid1 = $this->mUsers[ 0 ][ 'id' ];
			$userid2 = $this->mUsers[ 1 ][ 'id' ];
			$this->mData = array( 
				array( $userid1, $userid2, FRIENDS_A_HAS_B  )
			);
        }
        public function TearDown() {
            $this->DeleteTestUsers();
        }
		public function PreConditions() {
            $this->AssertClassExists( 'Friend' );
            $this->AssertMethodExists( 'Friend', 'Create' );
        }
        /**
         * @dataProvider GetData
         */
        public function TestCreate( $userid1, $userid2, $typeid ) {	
			$res = Friend::Create( $userid1, $userid2, $typeid );
			$this->Assert( $res, "Create should return true" );
			$res2 = Friend::ItemByUserIds( $userid1, $userid2 );
			$this->Assert( $res2, "Relation id should not be 0" );
			return $res2;
        }
		/**
         * @producer TestCreate
         */
        public function TestDelete( $relid ) {
			$relid = ( int )$relid;
			$success = Friend::Delete( $relid );
			$this->Assert( $success, 'Friend::Delete failed' );

			$friend = Friend::Item( $id );
            $this->Called( "Friend::Item" );
            $this->AssertFalse( $friend, 'Friend::Item should return false on deleted item' );
        }

		public function GetData() {
			return $this->mData;	
		}
	}
	
	return New TestFriend();
?>
