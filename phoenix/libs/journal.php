<?php

    class JournalFinder extends Finder {
        protected $mModel = 'Journal';
        
        public function FindByUser( $user, $offset = 0, $limit = 20, $order = array( 'Journalid', 'DESC' ) ) {
            $prototype = New Journal();
            $prototype->UserId = $user->Id;
            return $this->FindByPrototype( $prototype, $offset, 20, $order );
        }
    }
    
    class Journal extends Satori {
        protected $mDbTableAlias = 'journals';
        
        public function GetText() {
            return $this->Bulk->Text;
        }
        public function Relations() {
            $this->User = $this->HasOne( 'User', 'userid' );
            $this->Bulk = $this->HasOne( 'Bulk', 'bulkid' );
        }
    }

    // this can't be so small..

?>
