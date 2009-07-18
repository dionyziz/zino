<?php
	
	class StoretypeFinder extends Finder{
		public function FindbByName( $name ){
			$prototype = New Storetype();
			$prototype->Name = $name;
			return $this->FindByPrototype( $prototype );
		}
		public function FindAll( $offset = 0, $limit = 25 ){
			return parent::FindAll( $offset, $limit, array( 'Id', 'DESC' ) );
		}
	}
	
	class StoreitemFinder extends Finder{
		public function FindAll( $offset = 0, $limit = 25 ){
			return parent::FindAll( $offset, $limit, array( 'Id', 'DESC' ) );
		}
	}
	
	class StorepurchaseFinder extends Finder{
		public function FindByItemid( $id, $offset = 0, $limit = 50 ){
			$prototype = New Storepurchase();
			$prototype->Typeid = $id;
			return $this->FindByPrototype(  $prototype );
		}
	}
	
	class Storetype extends Satori{
		protected $mDbTableAlias = 'storetypes';
	}
	
	class Storeitem extends Satori{
		protected $mDbTableAlias = 'storeitems';
	}
	
	class Storeproperties extends Satori{
		protected $mDbTableAlias = 'storeproperties';
	}
	
	class Storepurchase extends Satori{
		protected $mDbTableAlias = 'storepurchases';
		protected function OnBeforeCreate(){
			
		}
	}
	
	class Storepurchaseproperties extends Satori{
		protected $mDbTableAlias = 'storepurchaseproperties';
	}
	
?>
