<?php
   /* class BanFinder extends Finder {
		protected $mModel = 'Ban';
		
		public function FindByIp( $ip ) {
			$prototype = New Ban();
			$prototype->Ip = $ip;
			
			return $this->FindByPrototype( $prototype );
		}
	}
	
	class Ban extends Satori {
		protected $mDbTableAlias = 'ipban';
		
		public function __get( $key ) {
			if ( $key == 'Expired' ) {
				return strtotime( $this->Expiredate ) < time();
			}

			return parent::__get( $key );
		}
	}*/
?>
