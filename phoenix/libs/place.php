<?php
    class PlaceFinder extends Finder {
        protected $mModel = 'Place';
        
        public function FindAll( $offset = 0, $limit = 10000 ) {
            $prototype = New Place();
            $prototype->Delid = 0;

            $places = $this->FindByPrototype( $prototype, $offset, $limit );

            $out = array();
            foreach ( $places as $i => $place ) {
                $out[ iconv( $place->Name, 'UTF-8', "ISO-8859-7" ) ] = $place;
            }
            ksort( $places );

            return $places;
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
	}
?>
