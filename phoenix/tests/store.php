<?php
	final class TestStore extends Testcase {
		protected $mAppliesTo = 'libs/store';
		private $mUser;
		private $mStoreItem;
		private $mStorePurchase;
		
		public function SetUp(){
			global $libs;
			$libs->Load( 'store' );
			
			$ufinder = New UserFinder();
			$user = $ufinder->FindByName( "teststore" );
			if ( isObject( $user ) ){
				$user->Delete();
			}
			$user = New User();
			$user->Name = 'teststore';
			$user->Subdomain = 'teststore';
			$user->Profile->Email = 'teststore@kamibu.com';
			$user->Save();
			$this->mUser = $user;
		}
		
		public function TestClassesExist(){
			$this->Assert( class_exists( 'StoreItem' ), 'Class StoreItem does not exist' );
			$this->Assert( class_exists( 'StorePurchase' ), 'Class StorePurchase does not exist' );
			$this->Assert( class_exists( 'StoreItemFinder' ), 'Class StoreItemFinder does not exist' );
		}
		
		public function TestMethodExist(){
			$item = New StoreItem();
			$this->Assert( method_exists( $item, 'Save' ), 'StoreItem::Save method does not exist' );
			$this->Assert( method_exists( $item, 'Delete' ), 'StoreItem::Delete method does not exist' );
			
			$finder = New StoreItemFinder();
			$this->Assert( method_exists( $finder, 'FindById' ), 'StoreItemFinder::FindById method does not exist' );
			$this->Assert( method_exists( $finder, 'FindByName'), 'StoreItemFinder::FindByName method does not exist' );
		}
		
		public function TestCreateItem(){
			$this->mStoreItem = New StoreItem();
			$name = "T-shirt";
			$price = "20.00E";
			$pieces = 32;
			$this->mStoreItem->Name = $name;
			$this->mStoreItem->Price = $price;
			$this->mStoreItem->Pieces = $pieces;
			$this->mStoreItem->Save;
			$this->Assert( is_int( $this->mStoreItem->Id ), 'Item Id sould be an integer after saving' );
			$this->AssertEquals( $name, $this->mStoreItem->Name, 'Item name changed after saving item' );
			$this->AssertEquals( $price, $this->mStoreItem->Price, 'Item price changed after saving item' );
			$this->AssertEquals( $Pieces, $this->mStoreItem->Pieces, 'Item piece count changed after saving item' );
			$this->Assert( $this->mStoreItem->Sold = 0, 'Sold items count was not set while saving item' );
		}
		
		public function TestFindOne(){
			$finder = New StoreItemFinder();
			$item = $finder->FindById( $this->mStoreItem->Id );
			$this->AssertEquals( "T-shirt", $item->Name, 'There was a problem while finding the item by id' );
			
			$item = $finder->FindByName( $this->mStoreItem->Name );
			$this->AssertEquals( "20.00E", $item->Price, 'There was a problem while Finding the item by name' );
		}
		
		public function TestDelete(){
			$this->mStoreItem->Delete();
			$finder = New StoreItemFinder();
			$item = $finder->FindByName( "T-shirt" );
			$this->AssertFalse( $item, 'Item seems to exist after deletion' );
		}
		
		public function TestPurchase(){
			$finder = New StoreItemFinder();
			$item = $finder->FindByName( "T-shirt" );
			$purchase = New StorePurchase();
			$purchase->StoreItemId = $item->Id;
			$purchase->UserId = $this->mUser->Id;
			$purchase->Save();
			$this->Assert( is_int( $purchase->Id ), 'Purchase Id sould be an integer after saving' );
			$this->AssertEquals( $this->mUser->Id, $purchase->UserId, 'User id changed after saving purchase' );
			$this->AssertEquals( $this->mStoreItem->Id, $purchase->StoreItemId, 'Item id changed after saving purchase' );
		}
	}
	
	return New TestStore;
?>
