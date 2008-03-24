<?php

    class UserSpace extends Satori {
        protected $mDbTableAlias = 'userspaces';
        
        public function GetText() {
            return $this->Bulk->Text;
        }
        public function Relations() {
            $this->User = $this->HasOne( 'User', 'userid' );
            $this->Bulk = $this->HasOne( 'Bulk', 'bulkid' );
        }
        public function Delete() {
            throw New UserException( 'Userspaces cannot be deleted' );
        }
    }

?>
