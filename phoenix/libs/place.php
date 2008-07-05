<?php
    class PlaceFinder extends Finder {
        protected $mModel = 'Place';
        
        public function FindAll( $offset = 0, $limit = 10000 ) {
            $prototype = New Place();
            $prototype->Delid = 0;

            return $this->FindByPrototype( $prototype, $offset, $limit, array( 'Name', 'ASC' ) );
        }
    }
	
	class Place extends Satori {
        protected $mDbTableAlias = 'places';
		
        // no privcheck after this point
		public function OnBeforeDelete() {
            $this->Delid = 1;
			$this->Save();
            $finder = New UserFinder();
            $finder->ClearPlace( $this->Id );

            return false;
		}
        public function LoadDefaults() {
            $this->Delid = 0;
            $this->X     = 0;
            $this->Y     = 0;
        }
        public function IsDeleted() {
            return $this->Delid != 0;
        }
        public function GetNameaccusative() {
            if ( empty( $this->mCurrentValues[ 'Nameaccusative' ] ) ) {
                return $this->Name;
            }
            return $this->mCurrentValues[ 'Nameaccusative' ];
        }
	}
?>
