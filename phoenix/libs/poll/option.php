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

		protected function Relations() {
			$this->Poll = $this->HasOne( 'Poll', 'Pollid' );
		}
	}

?>
