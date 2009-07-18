<?php
	final class TestStore extends Testcase {
		protected $mAppliesTo = 'libs/store';
		
		private $mUser;
		private $mAlbum;
		
		private $mStoretype;
		private $mStoreitem;
		private $mStoreproperty1;
		private $mStoreproperty2;
		private $mStorepurchase;
		private $mStorepurchaseproperty1;
		private $mStorepurchaseproperty2;
		
		public function SetUp(){
			global $libs;
			$libs->Load( 'album' );
			$libs->Load( 'user/user' );
			$libs->Load( 'user/profile' );
			$libs->Load( 'user/settings' );
			$libs->Load( 'journal/journal' );
			
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
			$TypeFinder = New StoretypeFinder();
			$this->Assert( method_exists( $TypeFinder, 'FindByName' ), 'StoretypeFinder::FindByName method does not exist' );
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
			$finder = New StoretypeFinder();
			$newType = $finder->FindByName( "T-shirt" );
			
			$this->Assert( is_int( $this->mStoretype->Id ), 'Type Id sould be an integer after saving' );
			$this->AssertEquals( 'T-shirt', $this->mStoretype->Name, 'Type name changed after saving item' );
			$this->AssertEquals( NowDate(), $this->mStoretype->Created, 'There was a problem while returning Created date' );
		}
		public function TestCreateItem(){
			$name = "Dragon T-shirt";
			$this->mStoreitem = New Storeitem();
			$this->mStoreitem->Name = $name;
			$this->mStoreitem->Price = '20.00E';
			$this->mStoreitem->Description = 'A great T-shirt with a dragon on it';
			$this->mStoreitem->Typeid = $this->mStoretype->Id;
			$this->mStoreitem->Total = 50;
			$this->mStoreitem->Save();
			
			$this->mAlbum = New Album();
			$this->mAlbum->Ownertype = TYPE_STOREITEM;
			$this->mAlbum->Ownerid = $this->mStoreitem->Id;
			$this->mAlbum->Save();
			
			$this->mStoreitem->Albumid = $this->mAlbum->Id;
			$this->mStoreitem->Save();
			
			$itemFinder = new StoreitemFinder();
			$items = $itemFinder->FindByName( $name );
			$item = $items[ 0 ];
			
			$this->Assert( is_int( $this->mStoreitem->Id ), 'Item Id sould be an integer after saving' );
			$this->AssertEquals( $name, $item->Name, 'Item name changed after saving item' );
			$this->AssertEquals( '20.00E', $item->Price, 'Item price changed after saving item' );
			$this->AssertEquals( 'A great T-shirt with a dragon on it', $item->Description, 'Item Description changed after saving item' );
			$this->AssertEquals( $this->mStoretype->Id, $item->Typeid, 'Type id changed after saving item' );
			$this->AssertEquals( $this->mAlbum->Id, $item->Albumid, 'Album id changed after saving item' );
			$this->AssertEquals( NowDate(), $item->Created, 'There was a problem while returning Created date' );
			$this->AssertEquals( 50, $item->Total, 'Item piece count changed after saving item' );
		}
		public function TestCreateProperties(){
			$this->mStoreproperty1 = New Storeproperty();
			$this->mStoreproperty1->Itemid = $this->mStoreitem->Id;
			$this->mStoreproperty1->Type = "Size";
			$this->mStoreproperty1->Value = "S";
			$this->mStoreproperty1->Save();
			$this->mStoreproperty2 = New Storeproperty();
			$this->mStoreproperty2->Itemid = $this->mStoreitem->Id;
			$this->mStoreproperty2->Type = "Size";
			$this->mStoreproperty2->Value = "L";
			$this->mStoreproperty2->Save();
			
			$property1 = New Storeproperty( $this->mStoreproperty1->Id );
			$property2 = New Storeproperty( $this->mStoreproperty2->Id );
			$this->AssertEquals( 'Size', $property1->Type, 'Property1 Type changed after saving' );
			$this->AssertEquals( 'S', $property1->Value, 'Property1 Type changed after saving' );
			$this->AssertEquals( 'Size', $property2->Type, 'Property2 Type changed after saving' );
			$this->AssertEquals( 'L', $property2->Value, 'Property2 Type changed after saving' );
		}
		
		public function TestDeletion(){
			$this->AssertTrue( $this->mUser->Exists(), 'Created user does not seem to exist before deleting' );
			$this->mUser->Delete();
			$this->AssertFalse( $this->mUser->Exists(), 'User deleted but he still seems to exist' );
			
			$this->AssertTrue( $this->mAlbum->Exists(), 'Created Album does not seem to exist before deleting' );
			$this->mAlbum->Delete();
			$this->AssertFalse( $this->mAlbum->Exists(), 'Album deleted but he still seems to exist' );
			
			$this->AssertTrue( $this->mStoretype->Exists(), 'Created Storetype does not seem to exist before deleting' );
			$this->mStoretype->Delete();
			$this->AssertFalse( $this->mStoretype->Exists(), 'Storetype deleted but he still seems to exist' );
			
			$this->AssertTrue( $this->mStoreitem->Exists(), 'Created Storeitem does not seem to exist before deleting' );
			$this->mStoreitem->Delete();
			$this->AssertFalse( $this->mStoreitem->Exists(), 'Storeitem deleted but he still seems to exist' );
			
			$this->AssertTrue( $this->mStoreproperty1->Exists(), 'Created Storeproperty1 does not seem to exist before deleting' );
			$this->mStoreproperty1->Delete();
			$this->AssertFalse( $this->mStoreproperty1->Exists(), 'Storeproperty1 deleted but he still seems to exist' );
			
			$this->AssertTrue( $this->mStoreproperty2->Exists(), 'Created Storeproperty2 does not seem to exist before deleting' );
			$this->mStoreproperty2->Delete();
			$this->AssertFalse( $this->mStoreproperty2->Exists(), 'Storeproperty2 deleted but he still seems to exist' );
			
			//$this->mStorepurchase->Delete();
			//$this->mStorepurchaseproperty->Delete();
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
