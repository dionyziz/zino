<?php

	class PollOptionFinder extends Finder {
		protected $mModel = 'Poll';

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
