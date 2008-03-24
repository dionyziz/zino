<?php

    class JournalFinder extends Finder {
        protected $mModel = 'Journal';
        
        public function FindByUser( $user, $offset = 0, $order = array( 'Created', 'DESC' ) ) {
            $prototype = New Journal();
            $prototype->UserId = $user->Id;
            return $this->FindByPrototype( $prototype, $offset, $order );
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
