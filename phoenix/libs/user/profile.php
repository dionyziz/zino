<?php
    global $libs;
    
    $libs->Load( 'place' );
    $libs->Load( 'university' );
    
    class UserProfile extends Satori {
        protected $mDbTableAlias = 'userprofiles';
        
        public function Relations() {
            $this->User = $this->HasOne( 'User', 'Userid' );
            $this->Location = $this->HasOne( 'Place', 'Placeid' );
            $this->University = $this->HasOne( 'Uni', 'Uniid' );
        }
        public function Delete() {
            throw New UserException( 'User profiles cannot be deleted' );
        }
    }

?>
