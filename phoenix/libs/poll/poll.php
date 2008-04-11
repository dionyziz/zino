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

		protected function Relations() {
			$this->User = $this->HasOne( 'User', 'Userid' );
			$this->Options = $this->HasMany( 'PollOptionFinder', 'FindByPoll', $this );
		}	
	}

?>
