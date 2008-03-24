<?php

    class UserSettings extends Satori {
        protected $mDbTableAlias = 'usersettings';
        
        public function Relations() {
            $this->User = $this->HasOne( 'User', 'Userid' );
        }
        public function Delete() {
            throw New UserException( 'User preferences cannot be deleted' );
        }
    }

?>
