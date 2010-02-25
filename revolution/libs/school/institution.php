<?php

    class InstitutionFinder extends Finder {
        protected $mModel = 'Institution';
    }

    class Institution extends Satori {
        protected $mDbTableAlias = 'institutions';
        
        public function Relations() {
            $this->Avatar = $this->HasOne( 'Image', 'Avatarid' );
        }
    }

?>
