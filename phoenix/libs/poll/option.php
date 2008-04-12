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
        public function Delete() {
            $this->DelId = 1;
            $this->Save();

            $this->OnDelete();
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
