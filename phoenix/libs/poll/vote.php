<?php
    class PollVoteFinder extends Finder {
        protected $mModel = 'PollVote';

        public function FindByOption( $option ) {
            $vote = New PollVote();
            $vote->Optionid = $option->Id;
            
            return $this->FindByPrototype( $vote );
        }
        public function FindByPoll( $poll ) {
            $vote = New PollVote();
            $vote->Pollid = $poll->Id;
            
            return $this->FindByPrototype( $vote );
        }
        public function FindByPollAndUser( $poll, $user ) {
            $vote = New PollVote();
            $vote->Pollid = $poll->Id;
            $vote->Userid = $user->Id;

            return $this->FindByPrototype( $vote );
        }
    }

    class PollException extends Exception {
    }

    class PollVote extends Satori {
        protected $mDbTableAlias = 'votes';

        public function OnBeforeUpdate() {
            throw New PollException( "Poll votes cannot be edited!" );
        }
        protected function OnCreate() {
            $this->Poll->OnVoteCreate();
            $this->Option->OnVoteCreate();
        }
        protected function OnDelete() {
            $this->Poll->Numvotes = $this->Poll->Numvotes - 1;
            $this->Poll->Save();
            $this->Poll->OnVoteDelete();
            $this->Option->OnVoteDelete();
        }
        protected function Relations() {
            $this->User = $this->HasOne( 'User', 'Userid' );
            $this->Option = $this->HasOne( 'PollOption', 'Optionid' );
            $this->Poll = $this->HasOne( 'Poll', 'Pollid' );    
        }
        protected function LoadDefaults() {
            $this->Created = NowDate();
        }
    }

?>
