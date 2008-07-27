<?php

    class SchoolFinder extends Finder {
        protected $mModel = 'School';

        public function FindUsers() {
            // TODO
        }

        // TODO
    }

    class School extends Satori {
        protected $mDbTableAlias = 'schools';

        protected function Relations() {
            $this->Users = $this->HasMany( 'SchoolFinder', 'FindUsers', $this );
        }
    }

?>
