<?php
	final class TestStore extends Testcase {
		protected $mAppliesTo = 'libs/store';
		
		private $mUser;
		private $mAlbum;
		
		private $mStoretype;
		private $mStoreitem;
		private $mStoreproperty;
		private $mStorepurchase;
		private $mStorepurchaseproperty;
		
		public function SetUp(){
			global $libs;
			$libs->Load( 'album' );
			$libs->Load( 'journal' );
			
			$finder = new UserFinder();
			$user = $finder->FindByName( 'testStore2' );
			if( is_object( $user ) ){
				$user->Delete();
			}
            $this->mUser = New User();
            $this->mUser->Name = 'testStore2';
            $this->mUser->Subdomain = 'teststore2';
            $this->mUser->Save();
			
		}
		
		public function TestClassesExist(){
			$this->Assert( class_exists( 'Storetype' ), 'Class Storetype does not exist' );
			$this->Assert( class_exists( 'Storeitem' ), 'Class Storeitem does not exist' );
			$this->Assert( class_exists( 'Storeproperty' ), 'Class Storeproperties does not exist' );
			$this->Assert( class_exists( 'Storepurchase' ), 'Class Storepurchase does not exist' );
			$this->Assert( class_exists( 'Storepurchaseproperty' ), 'Class Storepurchaseproperties does not exist' );
			
			$this->Assert( class_exists( 'StoretypeFinder' ), 'Class StoretypeFinder does not exist' );
			$this->Assert( class_exists( 'StoreitemFinder' ), 'Class StoreitemFinder does not exist' );
			$this->Assert( class_exists( 'StorepropertyFinder' ), 'Class StorepropertyFinder does not exist' );
			$this->Assert( class_exists( 'StorepurchaseFinder' ), 'Class StorepurchaseFinder does not exist' );
			$this->Assert( class_exists( 'StorepurchasepropertyFinder' ), 'Class StorepurchasepropertyFinder does not exist' );
			
		}
		
		public function TestMethodExist(){
			$purchase = New Storepurchase();
			$this->Assert( method_exists( $purchase, 'OnBeforeCreate' ), 'Storepurchase::OnBeforeCreate method does not exist' );
			
			
			$TypeFinder = New StoretypeFinder();
			$this->Assert( method_exists( $TypeFinder, 'FindbByName' ), 'StoretypeFinder::FindbByName method does not exist' );
			$this->Assert( method_exists( $TypeFinder, 'FindAll' ), 'StoretypeFinder::FindAll method does not exist' );
			
			$ItemFinder = New StoreitemFinder();
			$this->Assert( method_exists( $ItemFinder, 'FindAll' ), 'StoreItemFinder::FindById method does not exist' );
			
			$PropertyFinder = New StorepropertyFinder();
			$this->Assert( method_exists( $PropertyFinder, 'FindAll' ), 'StorepropertyFinder::FindAll method does not exist' );
			
			$PurchaseFinder = New StorepurchaseFinder();
			$this->Assert( method_exists( $PurchaseFinder, 'FindByItemid' ), 'StorepurchaseFinder::FindByItemid method does not exist' );
			$this->Assert( method_exists( $PurchaseFinder, 'CountByItemid' ), 'StorepurchaseFinder::CountByItemid method does not exist' );
			
			$PurchasepropertyFinder = New StorepurchasepropertyFinder();
			$this->Assert( method_exists( $PurchasepropertyFinder, 'FindAll' ), 'StorepurchasepropertyFinder::FindAll method does not exist' );
		}
		
		
		public function TestCreateType(){
			$this->mStoretype = New Storetype();
			$this->mStoretype->Name = "T-shirt";
			$this->mStoretype->Save();
			$finder = StoretypeFinder();
			$newType = $finder->FindByName( "T-shirt" );
			$this->AssertEquals( $this->mStoretype, $newType, 'Types are not equal after creation' );
		}
		public function TestCreateItem(){
			$this->mStoreitem = New Storeitem();
			$this->mStoreitem->Name = "Dragon T-shirt";
			$this->mStoreitem->Price = '20.00E';
			$this->mStoreitem->Description = 'A great T-shirt with a dragon on it';
			$this->mStoreitem->Typeid = $this->mStoreType->Id;
			//$this->mStoreitem->Albumid = ;
			$this->mStoreitem->Total = 50;
			$this->mStoreitem->Save();
			
			$this->Assert( is_int( $this->mStoreitem->Id ), 'Item Id sould be an integer after saving' );
			$this->AssertEquals( 'Dragon T-shirt', $this->mStoreitem->Name, 'Item name changed after saving item' );
			$this->AssertEquals( '20.00E', $this->mStoreitem->Price, 'Item price changed after saving item' );
			$this->AssertEquals( 50, $this->mStoreitem->Total, 'Item piece count changed after saving item' );
			$this->AssertEquals( 'A great T-shirt with a dragon on it', $this->mStoreitem->Description, 'Item Description changed after saving item' );
		}
		
/*		public function TestFindOne(){
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
		}*/
	}
	
	return New TestStore;
?>
