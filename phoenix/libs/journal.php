<?php

    class JournalFinder extends Finder {
        protected $mModel = 'Journal';
        
        public function FindByUser( $user ) {
            $prototype = New Journal();
            $prototype->Userid = $user->Id;
            return $this->FindByPrototype( $prototype );
        }
    }
    
    class Journal extends Satori {
        protected $mDbTableAlias = 'journals';
        
        public function GetText() {
            return $this->Bulk->Text;
        }
        protected function Relations() {
            $this->User = $this->HasOne( 'User', 'Userid' );
            $this->Bulk = $this->HasOne( 'Bulk', 'Bulkid' );
        }
    }

    // this can't be so small..

?>
