<?php

	global $libs;
	$libs->Load( 'poll/vote' );

	class PollOptionFinder extends Finder {
		protected $mModel = 'PollOption';

		public function FindByPoll( $poll ) {
			$option = New PollOption();
			$option->Pollid = $poll->Id;
			return $this->FindByPrototype( $option );
		}
	}

	class PollOption {
		protected $mDbTableAlias = 'polloptions';

        public function Vote( $user ) {
            $vote = New PollVote();
            $vote->Optionid = $this->Id;
            $vote->Pollid = $this->Pollid;
            $vote->Save();
        }
        public function Delete() {
            $this->DelId = 1;
            $this->Save();

            $this->OnDelete();
        }
        public function OnDelete() {
            foreach ( $this->Votes as $vote ) {
                $vote->Delete();
            }
        }
        public function UndoDelete() {
            $this->DelId = 0;
            $this->Save();

            foreach ( $this->Votes as $vote ) {
                $vote->UndoDelete();
            }
        }
		protected function Relations() {
			$this->Poll = $this->HasOne( 'Poll', 'Pollid' );
            $this->Votes = $this->HasMany( 'PollVoteFinder', 'FindByOption', $this );
		}
	}

?>
