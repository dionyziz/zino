<?php
    class PlaceFinder extends Finder {
        protected $mModel = 'Place';
        
        public function FindAll( $offset = 0, $limit = 10000 ) {
            $prototype = New Place();
            $prototype->Delid = 0;

            return $this->FindByPrototype( $prototype, $offset, $limit, 'Name' );
        }
    }
	
	class Place extends Satori {
        protected $mDbTableAlias = 'places';
		
        // no privcheck after this point
		public function Delete() {
            $this->Delid = 1;
			$this->Save();
            $finder = New UserFinder();
            $finder->ClearPlace( $this->Id );
		}
        public function LoadDefaults() {
            $this->Delid = 0;
            $this->X     = 0;
            $this->Y     = 0;
        }
        public function IsDeleted() {
            return $this->mDelid != 0;
        }
	}
?>
