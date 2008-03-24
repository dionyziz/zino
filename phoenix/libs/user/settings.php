<?php

    class UserSettings extends Satori {
        protected $mDbTableAlias = 'usersettings';
        
        public function Relations() {
            $this->User = $this->HasOne( 'User', 'UserId' );
        }
        public function Delete() {
            throw New UserException( 'User preferences cannot be deleted' );
        }
    }

?>
