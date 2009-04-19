<?php

    class TestPoll extends Testcase {
        protected $mAppliesTo = 'libs/poll/poll';
        private $mPoll;
        private $mOption;
        private $mUser;

        public function SetUp() {
            global $libs;

            $libs->Load( 'poll/poll' );

            $this->mUser = New User();
            $this->mUser->Name = 'testpolls';
            $this->mUser->Subdomain = 'testpolls';
            $this->mUser->Password = 'foobar';
            $this->mUser->Email = 'bitbucket@kamibu.com';
            $this->mUser->Save();
        }
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
            $this->Assert( method_exists( $votefinder, 'FindByOption' ), 'PollVoteFinder::FindByOption method does not exist' );
            $this->Assert( method_exists( $votefinder, 'FindByPoll' ), 'PollVoteFinder::FindByPoll method does not exist' );
            $this->Assert( method_exists( $votefinder, 'FindByPollAndUser' ), 'PollVoteFinder::FindByPollAndUser method does not exist' );
        }
        public function TestCreatePolls() {
            $poll = New Poll();
            $poll->Userid = $this->mUser->Id;
            $poll->Question = "Who is your favourite Beatle?";

            $this->AssertFalse( $poll->Exists(), 'Poll appears to exist before saving' );
            $poll->Save();
            $this->Assert( $poll->Exists(), 'Poll does not appear to exist after saving' );

            $this->Assert( ValidId( $poll->Id ), 'Poll id is not valid after saving' );

            $poll2 = New Poll( $poll->Id );
            $this->Assert( $poll2->Exists(), 'Poll does not appear to exist after creating new instance' );

            $this->AssertEquals( $poll->Userid, $poll2->Userid, 'Poll Userid changed on new instance' );
            $this->AssertEquals( $poll->Question, $poll2->Question, 'Poll Question changed on new instance' );
            $this->AssertEquals( $poll->Created, $poll2->Created, 'Poll Created changed on new instance' );
            
            $this->AssertEquals( 0, $poll->Numcomments, 'Numcomments should be 0 on a new poll' );
            $this->AssertEquals( 0, $poll->Numvotes, 'Numvotes should be 0 on a new poll' );
            
            $this->mPoll = $poll;
        }
        public function TestCreateOptions() {
            $poll = $this->mPoll;

            $option = New PollOption();
            $option->Text = "John Lennon";
            $option->Pollid = $poll->Id;
            $this->AssertFalse( $option->Exists(), 'Option appears to exist before saving' );
            $option->Save();
            $this->Assert( $option->Exists(), 'Option does not appear to exist after saving' );

            $this->Assert( ValidId( $option->Id ), 'Option Id not valid after saving' );
            
            $this->mOption = $option;

            $option2 = New PollOption( $option->Id );
            $this->Assert( $option2->Exists(), 'Option does not appear to exist after creating a new instance' );

            $this->AssertEquals( $option->Text, $option2->Text, 'Option text changed on new instance' );
            $this->AssertEquals( $option->Pollid, $option2->Pollid, 'Option pollid changed on new instance' );
            $this->AssertEquals( 0, $option2->Numvotes, 'Option numvotes should be 0 on a new option' );

            $option = $poll->CreateOption( 'Paul Mc Cartney' );
            $this->Assert( $option->Exists(), 'Option returned by Poll::CreateOption does not seem to exist' );
            $this->AssertEquals( 'Paul Mc Cartney', $option->Text, 'Option returned by Poll::CreateOption does not have the text specified' );
            $this->AssertEquals( $this->mPoll->Id, $option->Pollid, 'Option returned by Poll::CreateOption does not have the right poll id' );
            
            $poll->CreateOption( 'Ringo Starr' );
            $poll->CreateOption( 'George Harrison' );
            $poll->CreateOption( 'I have never heard of the Beatles' );

            $poll = New Poll( $this->mPoll->Id );
            $this->AssertEquals( 5, count( $poll->Options ), 'Wrong number of options in Options property of a new instance' );
        }
        public function TestVote() {
            $vote = New PollVote();
            $vote->Userid = $this->mUser->Id;
            $vote->Optionid = $this->mOption->Id;
            $vote->Pollid = $this->mPoll->Id;
            $this->AssertFalse( $vote->Exists(), 'Vote appears to exist before saving' );
            $vote->Save();

            $this->Assert( $vote->Exists(), 'Vote does not appear to exist after saving' );

            $vote2 = New PollVote( $this->mOption->Id, $this->mUser->Id );
            $this->Assert( $vote2->Exists(), 'Vote does not appear to exist after creating a new instance' );
            $this->AssertEquals( $vote->Created, $vote2->Created, 'Vote created changed on new instance' );
            $this->AssertEquals( $vote->Userid, $vote2->Userid, 'Vote userid changed on new instance' );
            $this->AssertEquals( $vote->Optionid, $vote2->Optionid, 'Vote optionid changed on new instance' );
            $this->AssertEquals( $vote->Pollid, $vote2->Pollid, 'Vote pollid changed on new instance' );

            $option = $this->mOption;
            $option->Vote( 2 );
            $option->Vote( 4 );

            $option = New PollOption( $this->mOption->Id + 1 );
            $option->Vote( 1 );
        }
        public function TestFindPollVotes() {
            $finder = New PollVoteFinder();
            $votes = $finder->FindByPoll( $this->mPoll );

            $this->Assert( is_array( $votes ), 'PollVoteFinder::FindByPoll did not return an array' );
            $this->AssertEquals( 4, count( $votes ), 'Number of votes returned by FindByPoll is wrong' );
        }
        public function TestFindUserVote() {
            $finder = New PollVoteFinder();
            $votes = $finder->FindByPollAndUser( $this->mPoll, $this->mUser );

            $this->Assert( is_array( $votes ), 'PollVoteFinder::FindByPollAndUser did not return an array' );
            $this->AssertEquals( 1, count( $votes ), 'PollVoteFinder::FindByPollAndUser returned wrong number of votes' );
            
            $vote = $votes[ 0 ];
            $this->AssertEquals( $this->mUser->Id, $vote->Userid, 'Wrong Vote::Userid on vote returned by FindByPollAndUser' );
            $this->AssertEquals( $this->mOption->Id, $vote->Optionid, 'Wrong Vote::Optionid on vote returned by FindByPollAndUser' );
            $this->AssertEquals( $this->mPoll->Id, $vote->Pollid, 'Wrong Vote::Pollid on vote returned by FindByPollAndUser' );
        }
        public function TestFindPollOptions() {
            $finder = New PollOptionFinder();
            $options = $finder->FindByPoll( $this->mPoll );

            $this->Assert( is_array( $options ), 'PollOptionFinder::FindByPoll did not return an array' );
            $this->AssertEquals( 5, count( $options ), 'PollOptionFinder::FindByPoll returned wrong number of options' ); 

            $texts = array( 'John Lennon', 'Paul Mc Cartney', 'Ringo Starr', 'George Harrison', 'I have never heard of the Beatles' );
            for ( $i = 0; $i < count( $options ); ++$i ) {
                $option = $options[ $i ];
                $this->Assert( $option instanceof PollOption, 'FindByPoll did not return an array of options' );
                $this->AssertEquals( $this->mPoll->Id, $option->Pollid, 'FindByPoll did not return the right options' );
                $this->AssertEquals( $texts[ $i ], $option->Text, 'FindByPoll did not return the right options, or returned them in a wrong order' );
            }
        }
        public function TestFindUserPolls() {
            $poll = New Poll();
            $poll->Userid = $this->mUser->Id;
            $poll->Question = "What's your favourite season?";
            $poll->Save();
            $pollid = $poll->Id;

            $option = New PollOption();
            $option->Text = "None";
            $option->Pollid = $poll->Id;
            $option->Save();

            $finder = New PollFinder();
            $polls = $finder->FindByUser( $this->mUser );

            $this->Assert( is_array( $polls ), 'PollFinder::FindByUser did not return an array' );
            $this->Assert( 2, count( $polls ), 'PollFinder::FindByUser did not return the right number of polls' );

            $texts = array( "What's your favourite season?", "Who is your favourite Beatle?" );
            $optionscounts = array( 1, 5 );
    
            for ( $i = 0; $i < count( $polls ); ++$i ) {
                $poll = $polls[ $i ];
                $this->AssertEquals( $this->mUser->Id, $poll->Userid, 'FindByUser did not return the right polls' );
                $this->AssertEquals( $texts[ $i ], $poll->Question, 'FindByUser did not return the right polls, or returned them in wrong order' );
                $this->AssertEquals( $optionscounts[ $i ], count( $poll->Options ), 'FindByUser did not return the right polls, or returned them in wrong order' );
            }

            $poll = New Poll( $pollid );
            $poll->Delete();
        }
        public function TestDeleteOption() {
            $option = $this->mOption;
            $this->AssertFalse( $option->IsDeleted(), 'Option seems to be deleted before calling PollOption::Delete' );
            $option->Delete();
            $this->Assert( $option->IsDeleted(), 'Option seems not deleted after calling PollOption::Delete' );

            $option = New PollOption( $this->mOption->Id );
            $this->Assert( $option->IsDeleted(), 'Deleted option doesn\'t seem to be deleted on new instance' );
        }
        public function TestDeletePoll() {
            $poll = $this->mPoll;
            $poll->Delete();
            
            $this->Assert( $poll->IsDeleted(), 'Poll::IsDeleted() did not return true on a deleted poll' );
            $this->AssertEquals( 1, $poll->Delid, 'Poll::Delid should be 1 on deleted polls' );

            $poll = New Poll( $this->mPoll->Id );
            $this->Assert( $poll->IsDeleted(), 'Poll::IsDeleted did not return true on new instance of deleted poll' );
            $this->AssertEquals( 1, $poll->Delid, 'Poll::Delid was not 1 on new instance of deleted poll' );
        }
        public function TearDown() {
            $this->mUser->Delete();
        }
    }

    return New TestPoll();

?>
