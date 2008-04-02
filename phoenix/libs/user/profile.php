<?php
    global $libs;
    
    $libs->Load( 'place' );
    
    class UserProfile extends Satori {
        protected $mDbTableAlias = 'userprofiles';
        
        public function Relations() {
            $this->User = $this->HasOne( 'User', 'Userid' );
            $this->Location = $this->HasOne( 'Place', 'Placeid' );
        }
        public function Delete() {
            throw New UserException( 'User profiles cannot be deleted' );
        }
    }

?>
