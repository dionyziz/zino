<?php
    class PlaceFinder extends Finder {
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
			global $user;
            
            $this->UpdateUserId = $user->Id();
            $this->UpdateDate   = NowDate();
            $this->UpdateIp     = UserIp();
            $this->DelId        = 0;
            $this->mX           = 0;
            $this->mY           = 0;
        }
	}
?>
