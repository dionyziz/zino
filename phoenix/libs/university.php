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
                $prototype->Placeid = $placeid;
            }
            if ( $typeid !== false ) {
                $prototype->Typeid = $typeid;
            }
            return $this->FindByPrototype( $prototype );
        }
    }

	final class Uni extends Satori {
        protected $mDbTableAlias = 'universities';
        
        protected function Relations() {
            $this->Place = $this->HasOne( 'Place', 'Placeid' );
            $this->User = $this->HasOne( 'User', 'Userid' );
        }
		public function Delete() {
			$this->DelId = 1;
			$this->Save();
		}
		protected function LoadDefaults() {
			$this->Created = NowDate();
		}
	}
?>
