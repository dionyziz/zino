<?php
    class Happening extends Satori {
        protected $mDbTableAlias = 'happenings';
    }
    class HappeningParticipant extends Satori {
        protected $mDbTableAlias = 'happeningparticipants';
        
        protected function Relations() {
            $this->User = $this->HasOne( 'User', 'Userid' );
            $this->Location = $this->HasOne( 'Place', 'Placeid' );
        }
        protected function LoadDefaults() {
            global $user;
            
            $this->Created = NowDate();
            if ( $user->Exists() ) {
                $this->Userid = $user->Id;
            }
            $this->Certainty = 0;
        }
    }
?>
