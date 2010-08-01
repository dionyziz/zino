<?php

    class TestPoll extends ModelTestcase {
		protected $mUsers;
		protected $mData;


		public function SetUp() {
            clude( 'models/poll.php' );
            clude( 'models/types.php' );
            clude( 'models/user.php' );


            $this->mUsers = $this->GenerateTestUsers( 1 );
			$userid = $this->mUsers[ 0 ][ 'id' ];
			$this->mData = array( 
				array( $userid, "Pws kai gt", array( "Na fygoume", "Na pame", "gt?" ) ), 
				array( $userid, "Dwse mia stigmh", array( "Alla kai gt", "jaf", "aslfh" ) )
			);
        }
        public function TearDown() {
            $this->DeleteTestUsers();
        }
		public function PreConditions() {
            $this->AssertClassExists( 'Poll' );
            $this->AssertMethodExists( 'Poll', 'Create' );
            $this->AssertMethodExists( 'Poll', 'Delete' );
        }
        /**
         * @dataProvider GetData
         */
        public function TestCreate( $userid, $question, $optiontexts ) {	
			$info = Poll::Create( $userid, $question, $optiontexts );
			$this->AssertIsArray( $info );
			$id = $info[ 'id' ];
			$item = Poll::Item( $id );
			$this->AssertIsArray( $item );
			$this->AssertEquals( $item[ "question" ], $question, "Wrong Title" );
			$success = false;
			foreach ( $item[ 'options' ] as $option ) {
				$success = false;
				foreach ( $optiontexts as $text ) {
					if ( $text == $option[ "text" ] ) {
						$success = true;
					}
				}
				if ( $success == false ) {
					break;
				}
			}
			$this->Assert( $success, "Poll options didn't match" );
			$this->AssertEquals( ( int )$item[ "user" ][ "id" ], $userid, "Wrong Creator" );

			return $item;
        }
		/**
         * @producer TestCreate
         */
        public function TestVote( $info ) {
            $id = (int)$info[ 'id' ];
			$userid = ( int )$info[ "userid" ];
			$optionid = ( int )$info[ "options" ][ 0 ][ "id" ];
            PollVote::Create( $id, $optionid, $userid );

            $vote = PollVote::Item( $id, $userid );
			var_dump( $vote );
            $this->Assert( $vote, 'PollVote::Vote wasnt created' );
			return $info;
        }
		/**
         * @producer TestVote
         */
        public function TestDelete( $info ) {
            $id = (int)$info[ 'id' ];
            $success = Poll::Delete( $id );
            $this->Assert( $success, 'Poll::Delete failed' );

            $poll = Poll::Item( $id );
            $this->Called( "Poll::Item" );
            $this->AssertFalse( $poll, 'Poll::Item should return false on deleted item' );

            // test on invalid album
            $success = Poll::Delete( 0 );
            $this->Assert( $success, 'Poll::Delete didnt succeeded on non-existing poll' );
        }

		public function GetData() {
			return $this->mData;	
		}
	}
	
	return New TestPoll();
?>
