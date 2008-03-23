<?php

    class UserPreferences extends Satori {
        protected $mDbTableAlias = 'userpreferences';
        
        public function Relations() {
            $this->User = $this->HasOne( 'User', 'userid' );
        }
        public function Delete() {
            throw New UserException( 'User preferences cannot be deleted' );
        }
    }

?>
