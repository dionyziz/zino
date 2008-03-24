<?php

    class UserProfile extends Satori {
        protected $mDbTableAlias = 'userprofiles';
        
        public function Relations() {
            $this->User = $this->HasOne( 'User', 'UserId' );
        }
        public function Delete() {
            throw New UserException( 'User profiles cannot be deleted' );
        }
    }

?>
