<?php

	class TestPoll extends Testcase {
		protected $mAppliesTo = 'libs/poll/poll';
        private $mPollId;

        public function TestClassesExist() {
            $this->Assert( class_exists( 'Poll' ), 'Poll class does not exist' );
            $this->Assert( class_exists( 'PollFinder' ), 'PollFinder class does not exist' );

            $this->Assert( class_exists( 'PollOption' ), 'PollOption class does not exist' );
            $this->Assert( class_exists( 'PollOptionFinder' ), 'PollOptionFinder class does not exist' );

            $this->Assert( class_exists( 'PollVote' ), 'PollVote class does not exist' );
            $this->Assert( class_exists( 'PollVoteFinder' ), 'PollVoteFinder class does not exist' );
        }
        public function TestMethodsExist() {
            $pollfinder = New PollFinder();
            $this->Assert( method_exists( $pollfinder, 'FindByUser' ), 'PollFinder::FindByUser method does not exist' );

            $optionfinder = New PollOptionFinder();
            $this->Assert( method_exists( $optionfinder, 'FindByPoll' ), 'PollOptionFinder::FindByPoll method does not exist' );

            $votefinder = New PollVoteFinder();
            $this->Assert( method_exists( $votefinder, 'FindByPollAndUser' ), 'PollVote::FindByPollAndUser method does not exist' );
        }
        public function TestCreatePolls() {
            $poll = New Poll();
            $poll->Userid = 2;
            $poll->Question = "Who is your favourite Beatle?";

            $this->AssertFalse( $poll->Exists(), 'Poll appears to exist before saving' );
            $poll->Save();
            $this->Assert( $poll->Exists(), 'Poll does not appear to exist after saving' );

            $this->Assert( ValidId( $poll->Id ), 'Poll id is not valid after saving' );

            $poll = New Poll( $poll->Id );
            $this->Assert( $poll2->Exists(), 'Poll does not appear to exist after creating new instance' );

            $this->AssertEqual( $poll->Userid, $poll2->Userid, 'Poll Userid changed on new instance' );
            $this->AssertEqual( $poll->Question, $poll2->Question, 'Poll Question changed on new instance' );
            $this->AssertEqual( $poll->Created, $poll2->Created, 'Poll Created changed on new instance' );
            
            $this->AssertEqual( 0, $poll->Numcomments, 'Numcomments should be 0 on a new poll' );
            $this->AssertEqual( 0, $poll->Numvotes, 'Numvotes should be 0 on a new poll' );
            
            $this->mPollId = $poll->Id;
        }
        public function TestCreateOptions() {
            $poll = New Poll( $this->mPollId );

            $option = New Option();
            $option->Text = "John Lennon";
            $option->Pollid = $poll->Id;
            $this->AssertFalse( $option->Exists(), 'Option appears to exist before saving' );
            $option->Save();
            $this->Assert( $option->Exists(), 'Option does not appear to exist after saving' );

            $this->Assert( ValidId( $option->Id ), 'Option Id not valid after saving' );

            $option2 = New Option( $option2->Id );
            $this->Assert( $option2->Exists(), 'Option does not appear to exist after creating a new instance' );

            $this->Assert( $option->Text, $option2->Text, 'Option text changed on new instance' );
            $this->Assert( $option->Pollid, $option2->Pollid, 'Option pollid changed on new instance' );
            $this->Assert( 0, $option2->Numvotes, 'Option numvotes should be 0 on a new option' );

            $option = $poll->CreateOption( 'Paul Mc Cartney' );
            $this->Assert( $option->Exists(), 'Option returned by Poll::CreateOption does not seem to exist' );
            $this->Assert( 'Paul Mc Cartney', $option->Text, 'Option returned by Poll::CreateOption does not have the text specified' );
            $this->Assert( $this->mPollId, $option->Pollid, 'Option returned by Poll::CreateOption does not have the right poll id' );
            
            $poll->CreateOption( 'Ringo Starr' );
            $poll->CreateOption( 'George Harrison' );
            $poll->CreateOption( 'I have never heard of the Beatles' );

            $options = $poll->Options;
            $this->Assert( 5, count( $options ), 'Wrong number of options in Options property' );
        }
        public function TestVote() {
        }
        public function TestFindVotes() {
        }
        public function TestFindOptions() {
        }
        public function TestFindPolls() {
        }
        public function TestDeleteOption() {
        }
        public function TestUndoDeleteOption() {
        }
        public function TestDeletePoll() {
        }
        public function TestUndoDeletePoll() {
        }
	}

	return New TestPoll();

?>
