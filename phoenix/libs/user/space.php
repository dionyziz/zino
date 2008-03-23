<?php

    class UserSpace extends Satori {
        protected $mDbTableAlias = 'userspaces';
        
        public function Relations() {
            $this->User = $this->HasOne( 'User', 'userid' );
        }
        public function Delete() {
            throw New UserException( 'Userspaces cannot be deleted' );
        }
    }

?>
