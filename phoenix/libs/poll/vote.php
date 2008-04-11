<?php

	class PollVoteFinder extends Finder {
		protected $mModel = 'PollVote';

        public function FindByOption( $option ) {
            $vote = New PollVote();
            $vote->OptionId = $vote->Id;
            
            return $this->FindByPrototype( $vote );
        }
		public function FindByPollAndUser( $poll, $user ) {
			$vote = New PollVote();
			$vote->PollId = $poll->Id;
			$vote->UserId = $user->Id;

			return $this->FindByPrototype( $vote );
		}	
	}

	class PollVote extends Satori {
		protected $mDbTableAlias = 'votes';

		public function Save() {
			w_assert( !$this->Exists(), "Poll votes cannot be edited!" );
			
			parent::Save();
		}
        public function Delete() {
            $this->Delid = 1;
            $this->Save();
        }
        public function UndoDelete() {
            $this->Delid = 0;
            $this->Save();

            $this->OnCreate();
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
        protected function LeadDefaults() {
            $this->Created = NowDate();
        }
	}

?>
