<?php

	global $libs;
	$libs->Load( 'bulk' );

    class JournalFinder extends Finder {
        protected $mModel = 'Journal';
        
        public function FindById( $id ) {
            $prototype = New Journal();
            $prototype->Id = $id;
            return $this->FindByPrototype( $prototype );
        }
        public function FindByUser( $user, $offset = 0, $limit = 25 ) {
            $prototype = New Journal();
            $prototype->Userid = $user->Id;

            return $this->FindByPrototype( $prototype, $offset, $limit, array( 'Id', 'DESC' ) );
        }
    }
    
    class Journal extends Satori {
        protected $mDbTableAlias = 'journals';
		private $mNewText = '';
       
	   	public function LoadDefaults() {
			global $user;

			$this->Userid = $user->Id;
            $this->Created = NowDate();
		}
        public function GetText( $length = false ) {
            if ( $length == false ) {
                return $this->Bulk->Text;
            }
            else {
                $text = preg_replace( "#<[^>]*?>#", "", $this->Bulk->Text ); // strip all tags
                return utf8_substr( $text, 0, $length );
            }
        }
		public function SetText( $text ) {
			$this->mNewText = $text;
		}
		public function Save() {
			if ( !empty( $this->mNewText ) ) {
				$bulk = New Bulk();
				$bulk->Text = $this->mNewText;
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
        protected function OnCreate() {
            ++$this->User->Count->Journals;
            $this->User->Count->Save();
        }
        protected function OnDelete() {
            --$this->User->Count->Delete;
            $this->User->Count->Save();
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
