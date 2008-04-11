<?php

	global $libs;
	$libs->Load( 'poll/option' );

	class PollFinder extends Finder {
		protected $mModel = 'Poll';

		public function FindByUser( $user ) {
			$poll = New Poll();
			$poll->Userid = $user->Id;
			return $this->FindByPrototype( $poll );
		}
	}

	class Poll extends Satori {
		protected $mDbTableAlias = 'polls';

        public function CreateOption( $text ) {
            $option = New PollOption();
            $option->Text = "text";
            $option->Pollid = $this->Id;
            $option->Save();
            
            return $option;
        }
        public function Delete() {
            $this->Delid = 1;
            $this->Save();

            $this->OnDelete();
        }
        public function UndoDelete() {
            $this->Delid = 0;
            $this->Save();

            foreach ( $this->Options as $option ) {
                $option->UndoDelete();
            }
        }
        public function OnDelete() {
            foreach ( $this->Options as $option ) {
                $option->Delete();
            }
        }
        protected function LoadDefaults() {
            $this->Created = NowDate();
        }
		protected function Relations() {
			$this->User = $this->HasOne( 'User', 'Userid' );
			$this->Options = $this->HasMany( 'PollOptionFinder', 'FindByPoll', $this );
		}	
	}

?>
