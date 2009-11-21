<?php

    class Song extends Satori {
		protected $mDbTableAlias = 'song';

		public function LoadDefaults() {
            global $user;

            $this->Userid = $user->Id;
			$this->Created = NowDate();
            return;
		}
	}

?>
