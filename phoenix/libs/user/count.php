<?php
	class UserCount extends Satori {
		protected $mDbTableAlias = 'usercounts';

		protected function Relations() {
			$this->User = $this->HasOne( 'User', 'Userid' );
		}
	}
?>
