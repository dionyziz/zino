<?php
    define( 'HAPPENING_PARTICIPATION_NO', 0 );
    define( 'HAPPENING_PARTICIPATION_MAYBE', 1 );
    define( 'HAPPENING_PARTICIPATION_YES', 2 );
    
    class Happening extends Satori {
        protected $mDbTableAlias = 'happenings';
        
        protected function Relations() {
            $this->Participants = $this->HasMany( 'HappeningParticipantFinder', 'FindByHappening', $this );
        }
    }
    class HappeningParticipantFinder extends Finder {
        protected $mModel = 'HappeningParticipant';
        
        public function FindByHappening( Happening $happening ) {
            $prototype = New HappeningParticipant();
            $prototype->Happeningid = $happening->Id;
            
            return $this->FindByPrototype( $prototype, 0, 1000 );
        }
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
