<?php

    class TestComment extends ModelTestcase {
		protected $mUsers;
		protected $mData;


		public function SetUp() {
            clude( 'models/comment.php' );
            clude( 'models/types.php' );
            clude( 'models/user.php' );


            $this->mUsers = $this->GenerateTestUsers( 1 );
			$userid = $this->mUsers[ 0 ][ 'id' ];
			$this->mData = array( 
				array( $userid, "Eftase hwra?", TYPE_PHOTO, 1245, 0 ),
				array( $userid, "Eftase hwra?", TYPE_JOURNAL, 1245, 0 ),
				array( $userid, "Eftase hwra?", TYPE_POLL, 1245, 0 ),
				array( $userid, "Eftase hwra?", TYPE_USERPROFILE, 1245, 0 )				
			);
        }
        /*public function TearDown() {
            $this->DeleteTestUsers();
        }*/
		public function PreConditions() {
            $this->AssertClassExists( 'Comment' );
            $this->AssertMethodExists( 'Comment', 'Create' );
        }
        /**
         * @dataProvider GetData
         */
        public function TestCreate( $userid, $text, $typeid, $itemid, $parentid ) {	
			$info = Comment::Create( $userid, $text, $typeid, $itemid, $parentid );
			$id = $info[ 'id' ];
			$text2 = $info[ 'text' ];
			$this->AssertIsArray( $info );
			$item = Comment::Item( $id );
			$this->AssertIsArray( $item );
			$this->AssertEquals( ( int )$item[ "id" ], $id, "wrong comment id" );
			$this->AssertEquals( $item[ "text" ], $text2, "wrong comment text" );
			$this->AssertEquals( ( int )$item[ "typeid" ], $typeid, "wrong comment type" );
			
			return $item;
        }
		public function GetData() {
			return $this->mData;	
		}
	}
	
	return New TestComment();
?>
