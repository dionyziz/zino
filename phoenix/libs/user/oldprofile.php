<?php

    class OldUserProfile extends Satori {
        protected $mDbTableAlias = 'olduserprofiles';
        
        protected function Relations() {
            $this->Location = $this->HasOne( 'Place', 'Placeid' );
            $this->Uni = $this->HasOne( 'Uni', 'Uniid' );
            $this->School = $this->HasOne( 'School', 'Schoolid' );
            $this->Mood = $this->HasOne( 'Mood', 'Moodid' );
        }
        protected function LoadDefaults() {
            $this->Education = '-';
            $this->Sexualorientation = '-';
            $this->Religion = '-';
            $this->Politics = '-';    
            $this->Eyecolor = '-';
            $this->Haircolor = '-';
            $this->Smoker = '-';
            $this->Drinker = '-';
            $this->Height = -1;
            $this->Weight = -1;
        }
    }

?>
