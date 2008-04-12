<?php

	class PollVoteFinder extends Finder {
		protected $mModel = 'PollVote';

        public function FindByOption( $option ) {
            $vote = New PollVote();
            $vote->Optionid = $vote->Id;
            
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

	class PollVote extends Satori {
		protected $mDbTableAlias = 'votes';

		public function Save() {
			w_assert( !$this->Exists(), "Poll votes cannot be edited!" );
			
			parent::Save();
		}
		protected function OnCreate() {
			$this->Poll->Numvotes = $this->Poll->Numvotes + 1;
			$this->Poll->Save();

			$this->Option->Numvotes = $this->Option->Numvotes + 1;
			$this->Option->Save();
		}
		protected function OnDelete() {
			$this->Poll->Numvotes = $this->Poll->Numvotes - 1;
			$this->Poll->Save();

			$this->Option->Numvotes = $this->Option->Numvotes - 1;
			$this->Option->Save();
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
