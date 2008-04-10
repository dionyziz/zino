<?php
    class PlaceFinder extends Finder {
        protected $mModel = 'Place';
        
        public function FindAll( $offset = 0, $limit = 10000 ) {
            return $this->FindByPrototype( New Place(), $offset, $limit, 'Name' );
        }
    }
	
	class Place extends Satori {
        protected $mDbTableAlias = 'places';
		
        // no privcheck after this point
		public function Delete() {
            $this->DelId = 1;
			$change = $this->Save();
            
			if ( $change->Impact() ) {
				// Prepared query
                $finder = New UserFinder();
                $finder->ClearPlace( $this->Id );
			}
		}
        public function LoadDefaults() {
            $this->Delid = 0;
            $this->X     = 0;
            $this->Y     = 0;
        }
	}
?>
