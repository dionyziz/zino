<?php
	final class TestStore extends Testcase {
		protected $mAppliesTo = 'libs/store';
		
		private $mUser;
		private $mUser2;
		
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
			$libs->Load( 'favourite' );
			
			$finder = new UserFinder();
			$user = $finder->FindByName( 'testStore1' );
			if( is_object( $user ) ){
				$user->Delete();
			}
 			$user = $finder->FindByName( 'testStore2' );
			if( is_object( $user ) ){
				$user->Delete();
			}
            $this->mUser = New User();
            $this->mUser->Name = 'testStore1';
            $this->mUser->Subdomain = 'teststore1';
            $this->mUser->Save();
			
            $this->mUser2 = New User();
            $this->mUser2->Name = 'testStore2';
            $this->mUser2->Subdomain = 'teststore2';
            $this->mUser2->Save();
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
			$finder = New StoretypeFinder();
			$type = $finder->FindByName( "T-shirt" );
			if( is_object( $type ) ){
				$type->Delete();
			}
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
			$finder = new StoreitemFinder();
			$items = $finder->FindByName( $name );
			$item = $items[ 0 ];
			if( is_object( $item ) ){
				$item->Delete();
			}
			$this->mStoreitem = New Storeitem();
			$this->mStoreitem->Name = $name;
			$this->mStoreitem->Price = '20.00E';
			$this->mStoreitem->Description = 'A great T-shirt with a dragon on it';
			$this->mStoreitem->Typeid = $this->mStoretype->Id;
			$this->mStoreitem->Total = 50;
			$this->mStoreitem->Save();
			
			
		/*
			$this->mAlbum = New Album();
			$this->mAlbum->Ownertype = TYPE_STOREITEM;
			$this->mAlbum->Ownerid = $this->mStoreitem->Id;
			$this->mAlbum->Save();
			
			$this->mStoreitem->Albumid = $this->mAlbum->Id;
			$this->mStoreitem->Save();
			*/
			$itemFinder = new StoreitemFinder();
			$items = $itemFinder->FindByName( $name );
			$item = $items[ 0 ];
			
			$this->Assert( is_int( $this->mStoreitem->Id ), 'Item Id sould be an integer after saving' );
			$this->AssertEquals( $name, $item->Name, 'Item name changed after saving item' );
			$this->AssertEquals( '20.00E', $item->Price, 'Item price changed after saving item' );
			$this->AssertEquals( 'A great T-shirt with a dragon on it', $item->Description, 'Item Description changed after saving item' );
			$this->AssertEquals( $this->mStoretype->Id, $item->Typeid, 'Type id changed after saving item' );
		//	$this->AssertEquals( $this->mAlbum->Id, $item->Albumid, 'Album id changed after saving item' );
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
		public function TestCreatePurchase(){
			$this->mStorepurchase = New Storepurchase();
			$this->mStorepurchase->Itemid = $this->mStoreitem->Id;
			$this->mStorepurchase->Userid = $this->mUser->Id;
			$this->mStorepurchase->Save();
			
			$purchaseFinder = New StorepurchaseFinder();
			$purchases = $purchaseFinder->FindByItemid( $this->mStoreitem->Id );
			$purchase = $purchases[ 0 ];
			
			$this->Assert( is_int( $purchase->Id ), 'purchase Id sould be an integer after saving' );
			$this->AssertEquals( $this->mStoreitem->Id, $purchase->Itemid, 'Item id changed after saving item' );
			$this->AssertEquals( $this->mUser->Id, $purchase->Userid, 'User id changed after saving item' );
			
			$this->mStorepurchaseproperty1 = New Storepurchaseproperty();
			$this->mStorepurchaseproperty1->Propertyid = $this->mStoreproperty1->Id;
			$this->mStorepurchaseproperty1->Purchaseid = $this->mStorepurchase->Id;
			$this->mStorepurchaseproperty1->Save();
			
			$this->mStorepurchaseproperty2 = New Storepurchaseproperty();
			$this->mStorepurchaseproperty2->Propertyid = $this->mStoreproperty2->Id;
			$this->mStorepurchaseproperty2->Purchaseid = $this->mStorepurchase->Id;
			$this->mStorepurchaseproperty2->Save();
			
			$this->Assert( is_int( $this->mStorepurchaseproperty1->Id ), 'purchaseproperty1 id sould be an integer after saving' );
			$this->Assert( is_int( $this->mStorepurchaseproperty2->Id ), 'purchaseproperty2 id sould be an integer after saving' );
		}
		
		public function TestRelations(){
			$this->AssertEquals( $this->mUser->Id, $this->mStorepurchase->User->id, 'Userid changed after relation with purchase' );
		}
		
		public function TestFavourites(){
			$finder = new FavouriteFinder();
			$favourites = $finder->FindByEntity( $this->mStoreitem );
			$this->AssertEquals( count( $favourites ), 0, 'there are favourite entries after creation of the item' );
			$favourite1 = new Favourite();
			$favourite1->Itemid = $this->mStoreitem->Id;
			$favourite1->Userid = $this->mUser->Id;
			$favourite1->Typeid = 8;
			$favourite1->Save();
			$favourites = $finder->FindByEntity( $this->mStoreitem );
			$this->AssertEquals( count( $favourites ), 1, 'the number of favourites is wrong, after creating one favourite for Storeitem' );
			$this->AssertEquals( $faviurutes[ 0 ]->Userid, $this->mUser, 'Userid changed after saving' );
			
			$favourite2 = new Favourite();
			$favourite2->Itemid = $this->mStoreitem->Id;
			$favourite2->Userid = $this->mUser2->Id;
			$favourite2->Typeid = 8;
			$favourite2->Save();
			$favourites = $finder->FindByEntity( $this->mStoreitem );
			$this->AssertEquals( count( $favourites ), 2, 'the number of favourites is wrong, after creating second favourite for Storeitem' );
			$ret1 = ( $favourites[ 0 ]->Userid == $this->mUser->Id && $favourites[ 1 ]->Userid == $this->mUser2->Id );
			$ret2 = ( $favourites[ 1 ]->Userid == $this->mUser->Id && $favourites[ 0 ]->Userid == $this->mUser2->Id );
			$this->Assert( $ret1 || $ret2, 'Favourites changed after saving the second' );
			$favourite1->Delete();
			$favourite2->Delete();
			$favourites = $finder->FindByEntity( $this->mStoreitem );
			$this->AssertEquals( count( $favourites ), 0, 'Favourites still exist, after deletion' );
		}
		
		public function TestDeletion(){
			$this->AssertTrue( $this->mUser->Exists(), 'Created user does not seem to exist before deleting' );
			$this->mUser->Delete();
			$this->AssertFalse( $this->mUser->Exists(), 'User deleted but he still seems to exist' );
			
		/*	$this->AssertFalse( $this->mAlbum->IsDeleted(), 'Created Album does not seem to exist before deleting' );
			$this->mAlbum->Delete();
			$this->AssertTrue( $this->mAlbum->IsDeleted(), 'Album deleted but he still seems to exist' );
			*/
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
			
			$this->AssertTrue( $this->mStorepurchase->Exists(), 'Created purchase does not seem to exist before deleting' );
			$this->mStorepurchase->Delete();
			$this->AssertFalse( $this->mStorepurchase->Exists(), 'purchase deleted but he still seems to exist' );
					
			$this->AssertTrue( $this->mStorepurchaseproperty1->Exists(), 'Created purchaseproperty1 does not seem to exist before deleting' );
			$this->mStorepurchaseproperty1->Delete();
			$this->AssertFalse( $this->mStorepurchaseproperty1->Exists(), 'purchaseproperty1 deleted but he still seems to exist' );
					
			$this->AssertTrue( $this->mStorepurchaseproperty2->Exists(), 'Created purchaseproperty2 does not seem to exist before deleting' );
			$this->mStorepurchaseproperty2->Delete();
			$this->AssertFalse( $this->mStorepurchaseproperty2->Exists(), 'purchaseproperty2 deleted but he still seems to exist' );
		
		
		}
		

	}
	
	return New TestStore;
?>
