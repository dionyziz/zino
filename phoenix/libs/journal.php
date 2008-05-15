<?php

    class JournalFinder extends Finder {
        protected $mModel = 'Journal';
        
        public function FindById( $id ) {
            $prototype = New Journal();
            $prototype->Id = $id;
            return $this->FindByPrototype( $prototype );
        }
        public function FindByUser( $user ) {
            $prototype = New Journal();
            $prototype->Userid = $user->Id;
            return $this->FindByPrototype( $prototype );
        }
    }
    
    class Journal extends Satori {
        protected $mDbTableAlias = 'journals';
		private $mNewText = '';
        
        public function GetText( $length = false ) {
            if ( $length == false ) {
                return $this->Bulk->Text;
            }
            else {
                $text = ereg_replace( "<[^>]*>", "", $this->Bulk->Text );
                return utf8_substr( $text, $length );
            }
        }
		public function SetText( $text ) {
			$this->mNewText = $text;
		}
		public function Save() {
			if ( !empty( $this->mNewText ) ) {
				$bulk = New Bulk();
				$bulk->Text = $text;
				$bulk->Save();

				$this->Bulkid = $bulk->Id;
			}
			parent::Save();
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
            return $this->Exists() === false;
        }
    }

?>
