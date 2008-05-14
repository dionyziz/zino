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
        
        public function GetText( $length = false ) {
            if ( $length == false ) {
                return $this->Bulk->Text;
            }
            else {
                $text = ereg_replace( "<[^>]*>", "", $this->Bulk->Text );
                return utf8_substr( $text, $length );
            }
        }
        public function OnCommentCreate() {
            ++$this->Numcomments;
            $this->Save();
        }
        public function OnCommentDelete() {
            --$this->Numcomments;
            $this->Save();
        }
        protected function Relations() {
            $this->User = $this->HasOne( 'User', 'Userid' );
            $this->Bulk = $this->HasOne( 'Bulk', 'Bulkid' );
        }
        public function IsDeleted() {
            return $this->Delid > 0;
        }
    }

?>
