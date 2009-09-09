<?php
	
	class StoretypeFinder extends Finder{
		protected $mModel = 'Storetype';
		public function FindByName( $name ){
			$prototype = New Storetype();
			$prototype->Name = $name;
			return $this->FindByPrototype( $prototype );
		}
		public function FindAll( $offset = 0, $limit = 25 ){
			return parent::FindAll( $offset, $limit, array( 'Id', 'DESC' ) );
		}
	}
	
	class StoreitemFinder extends Finder{
		protected $mModel = 'Storeitem';
		public function FindAll( $offset = 0, $limit = 25 ){
			return parent::FindAll( $offset, $limit, array( 'Id', 'DESC' ) );
		}
		public function FindByName( $name ){
			$prototype = New Storeitem();
			$prototype->Name = $name;
			return $this->FindByPrototype( $prototype );
		}
	}
	
	class StorepropertyFinder extends Finder{
		protected $mModel = 'Storeproperty';
		public function FindAll( $offset = 0, $limit = 25 ){
			return parent::FindAll( $offset, $limit, array( 'Id', 'DESC' ) );
		}
		public function FindByItemId( $id ){
			$prototype = New Storeproperty();
			$prototype->Itemid = $id;
			return $this->FindByPrototype( $prototype );
		}
	}
	
	class StorepurchaseFinder extends Finder{
		protected $mModel = 'Storepurchase';
        
		public function FindByItemid( $id, $offset = 0, $limit = 200 ){
			$prototype = New Storepurchase();
			$prototype->Itemid = $id;
			$purchases = $this->FindByPrototype( $prototype, $offset, $limit );
			$userids = array();
			foreach ( $purchases as $purchase ){
				$userids[] = $purchase->Userid;
			}
			$finder = New UserFinder();
			$users = $finder->FindByIds( $userids );
			
			$userbyid = array();
			foreach ( $users as $user ){
				$userbyid[ $user->Id ] = $user;
			}
			foreach ( $purchases as $i => $purchase ){
				$purchase->CopyRelationFrom( 'User', $userbyid[ $purchase->Userid ] );
			}
			return $purchases;
		}
		public function CountByItemid( $id ){
			$prototype = New Storepurchase();
			$prototype->Itemid = $id;
			return count( $this->FindByPrototype( $prototype, 0, 200 ) );
		}
		public function FindByUserid( $id, $offset = 0, $limit = 200 ){
			$prototype = New Storepurchase();
			$prototype->Userid = $id;
			return $this->FindByPrototype( $prototype, $offset, $limit );
		}
	}
	
	class StorepurchasepropertyFinder extends Finder{
		protected $mModel = 'Storepurchaseproperty';
        
        public function FindByPurchaseId( $purchaseid ) {
            $prototype = New StorePurchaeProperty();
            $prototype->Purchaseid = $purchaseid;
            return $this->FindByPrototype( $prototype );
        }
        protected function Relations() {
            $this->Purchase = $this->HasOne( 'StorePurchase', 'Purchaseid' );
            $this->Property = $this->HasOne( 'StoreProperty', 'Propertyid' );
        }
		public function FindAll( $offset = 9, $limit = 25 ){
			return parent::FindAll( $offset, $limit, array( 'Id', 'DESC' ) );
		}
	}
	
	
	class Storetype extends Satori{
		protected $mDbTableAlias = 'storetypes';
		public function LoadDefaults() {
			$this->Created = NowDate();
		}
	}
	
	class Storeitem extends Satori{
		protected $mDbTableAlias = 'storeitems';
		public function Remaining(){
			$finder = New StorepurchaseFinder();
			$sold = $finder->CountByItemid( $this->Id );
			return $this->Total - $sold;
		}
		public function LoadDefaults() {
			$this->Created = NowDate();
		}
	}
	
	class Storeproperty extends Satori{
		protected $mDbTableAlias = 'storeproperties';
	}
	
	class Storepurchase extends Satori{
		protected $mDbTableAlias = 'storepurchases';
        protected function Relations() {
            $this->User = $this->HasOne( 'User', 'Userid' );
        }
		protected function OnBeforeCreate(){
			$purchaseFinder = New StorepurchaseFinder();
			$purchases = $purchaseFinder->CountByItemid( $this->Itemid );
			$item = New Storeitem( $this->Itemid );
			if( $purchases[ 0 ] >= $item->Total ){
				return false;
			}
		}
		public function LoadDefaults() {
			$this->Created = NowDate();
		}
	}
	
	class Storepurchaseproperty extends Satori{
		protected $mDbTableAlias = 'storepurchaseproperties';
	}
	
?>
