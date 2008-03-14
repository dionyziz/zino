<?php
	/*
		Developer: Izual
		Backend for the universities task
	*/

    class UniFinder extends Finder {
        protected $mModel = 'Uni';
        
        public function Find( $placeid = 0, $typeid = false ) {
            $prototype = New Uni();
            if ( $placeid != 0 ) {
                $prototype->PlaceId = $placeid;
            }
            if ( $typeid !== false ) {
                $prototype->TypeId = $typeid;
            }
            return $this->FindByPrototype( $prototype );
        }
    }

	final class Uni extends Satori {
        protected $mDbTableAlias = 'universities';
        
        protected function Relations() {
            $this->Place = $this->HasOne( 'Place', 'placeid' );
        }
		public function Delete() {
			$this->DelId = 1;
			$this->Save();
		}
		protected function LoadDefaults() {
			$this->Date = NowDate();
		}
	}
?>
