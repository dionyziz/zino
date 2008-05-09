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

	class PollOption extends Satori {
		protected $mDbTableAlias = 'polloptions';

        public function Vote( $user ) {
            if ( $user instanceof User ) {
                $user = $user->Id;
            }

            w_assert( ValidId( $user ), 'Invalid user id on PollOption::Vote!' );

            $vote = New PollVote();
            $vote->Userid = $user;
            $vote->Optionid = $this->Id;
            $vote->Pollid = $this->Pollid;
            $vote->Save();
        }
        public function OnVoteCreate() {
            ++$this->Numvotes;
            $this->Save();
        }
		public function GetPercentage() {
            return $this->Numvotes / $this->Poll->Numvotes;
        }
        public function IsDeleted() {
            return $this->Delid > 0;
        }
        public function Delete() {
            $this->Delid = 1;
            $this->Save();

            $this->OnUpdate();
        }
        public function UndoDelete() {
            $this->DelId = 0;
            $this->Save();
        }
		protected function Relations() {
			$this->Poll = $this->HasOne( 'Poll', 'Pollid' );
            $this->Votes = $this->HasMany( 'PollVoteFinder', 'FindByOption', $this );
		}
	}

?>
