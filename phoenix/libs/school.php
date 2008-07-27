<?php

    class SchoolFinder extends Finder {
        protected $mModel = 'School';

        public function FindUsers( $schoolid, $offset = 0, $limit = 10000 ) {
            $prototype = New User();
            $prototype->Schoolid = $schoolid;
            return FindByPrototype( $prototype, $offset, $limit, 'Name' );
        }

    }

    class School extends Satori {
        protected $mDbTableAlias = 'schools';

        protected function Relations() {
            $this->Users = $this->HasMany( 'SchoolFinder', 'FindUsers', $this );
        }
    }

?>
