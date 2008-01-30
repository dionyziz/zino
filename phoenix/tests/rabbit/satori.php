<?php
    /*
    //
    //   Simple stand-alone object
    //
    
    class Place extends Satori {
        protected $mDbTable = 'places';
    }
    
    class Bulk extends Satori {
        protected $mDbTable = 'bulk';
    }
    
    //
    //   One-to-one relationship
    //
    
    class Space extends Satori {
        protected $mDbTable = 'spaces';
        protected $mDb = 'db';
        
        protected function Relations() {
            $this->mBulk = $this->HasOne( 'Bulk', 'space_bulkid', array( 'bulk', 'bulk_id' ) );
        }
    }
    
    //
    //   One-to-many relationship
    //
    
    class Photo extends Satori {
        protected $mDbTable = 'photos';
        
        protected function Relations() {
            $this->mAlbum = $this->BelongsTo( 'Album', 'photo_albumid', array( 'albums', 'album_id' ) );
        }
    }
    
    class Album extends Satori {
        protected $mDbTable = 'albums';
        
        protected function Relations() {
            $this->mPhotos = $this->HasMany( 'Photo', 'album_id', array( 'photos', 'photo_albumid' ) );
            $this->mUser = $this->BelongsTo( 'User', 'album_userid', array( 'users', 'user_id' ) );
        }
    }
    
    //
    //   Many-to-many relationship
    //
    
    class User extends Satori {
        protected $mDbTable = 'users';
        
        protected function Relations() {
            $this->mJournalAuthors = $this->HasMany( 'JournalAuthor', 'user_id', array( 'journalauthors', 'journalauthor_userid' ) );
            $this->mSettings = $this->HasOne( 'UserSetting', 'user_id', array( 'usersettings', 'setting_userid' ) );
            $this->mProfiles = $this->HasOne( 'UserProfile', 'user_id', array( 'userprofiles', 'profile_userid' ) );
            $this->mPlace = $this->HasOne( 'Place', 'user_placeid', array( 'places', 'place_id' ) );
            $this->mAlbums = $this->HasMany( 'Album', 'user_id', array( 'albums', 'album_userid' ) );
        }
    }
    
    class JournalAuthor extends Satori {
        protected $mDbTable = 'journalauthors';
        
        protected function Relations() {
            $this->mJournal = $this->BelongsTo( 'Journal', 'journalauthor_journalid', array( 'journals', 'journal_id' ) );
            $this->mAuthor = $this->BelongsTo( 'User', 'journalauthor_userid', array( 'users', 'user_id' ) );
        }
    }
        
    class UserSetting extends Satori {
        protected $mDbTable = 'usersettings';
    }
    
    class Journal extends Satori {
        protected $mDbTable = 'journals';
        
        public function GetText() {
            return $this->Bulk->Text;
        }
        public function Relations() {
            $this->mJournalAuthors = $this->HasMany( 'JournalAuthor', 'journal_journalid', array( 'journals_authors', 'journalauthor_journalid' ) );
            $this->mBulk = $this->HasOne( 'Bulk', 'journal_bulkid', array( 'bulk', 'bulk_id' ) );
        }
    }
    
    $theuser = New User( 5 );
    foreach ( $theuser->Journals as $journal ) {
        ?><h1><?php
        echo htmlspecialchars( $journal->Title );
        ?></h1><?php
        echo $journal->Text;
    }
    
    echo htmlspecialchars( $theuser->Profiles->FavouriteMovies->GetByIndex( 2 ) );
    */
    class TestRabbitOverloadable extends Overloadable {
        private $mFoo;
        
        public function TestRabbitOverloadable() {
            $this->mFoo = false;
        }
        public function SetFoo( $value ) {
            $this->mFoo = $value;
        }
        public function GetBar() {
            return $this->mFoo;
        }
    }
    
    class TestRabbitSatoriExtension extends Satori {
        protected $mDbTableAlias = 'rabbit_satori_test';
        
        public function LoadDefaults() {
            $this->Char = 'abcd';
        }
    }
        
    class TestRabbitSatori extends Testcase {
        protected $mAppliesTo = 'libs/rabbit/satori';
        private $mDb;
        private $mDbTable;
        private $mObj;
        
        public function SetUp() {
            global $rabbit_settings;
            
            w_assert( is_array( $rabbit_settings[ 'databases' ] ) );
            w_assert( count( $rabbit_settings[ 'databases' ] ) );
            $databasealiases = array_keys( $rabbit_settings[ 'databases' ] );
            w_assert( isset( $GLOBALS[ $databasealiases[ 0 ] ] ) );
            $this->mDb = $GLOBALS[ $databasealiases[ 0 ] ];
            w_assert( $this->mDb instanceof Database );
            
            // make sure we don't overwrite something
            w_assert( $this->mDb->TableByAlias( 'rabbit_satori_test' ) === false );
            
            $this->mDbTable = New DBTable();
            $this->mDbTable->Name = 'rabbit_satori_test';
            $this->mDbTable->Alias = 'rabbit_satori_test';
            $this->mDbTable->Database = $this->mDb;
            
            $field = New DBField();
            $field->Name = 'test_id';
            $field->Type = DB_TYPE_INT;
            $field->IsAutoIncrement = true;
            
            $field2 = New DBField();
            $field2->Name = 'test_char';
            $field2->Type = DB_TYPE_CHAR;
            $field2->Length = 4;
            
            $field3 = New DBField();
            $field3->Name = 'test_int';
            $field3->Type = DB_TYPE_INT;
            
            $this->mDbTable->CreateField( $field, $field2, $field3 );
            
            $primary = New DBIndex();
            $primary->Type = DB_KEY_PRIMARY;
            $primary->AddField( $field );
            
            $this->mDbTable->CreateIndex( $primary );
            
            $this->mDbTable->Save();
            
            $this->mDb->AttachTable( 'rabbit_satori_test', 'rabbit_satori_test' );
        }
        public function TestClassesExist() {
            $this->Assert( class_exists( 'Overloadable' ), 'Class Overloadable is undefined' );
            $this->Assert( class_exists( 'Satori' ), 'Class Satori is undefined' );
        }
        public function TestOverloadable() {
            $this->Assert( class_exists( 'TestRabbitOverloadable' ) );
            $test = New TestRabbitOverloadable();
            $this->Assert( $test instanceof TestRabbitOverloadable );
            $this->AssertEquals( false, $test->Bar, 'Initial value of Foo is not false as expected' );
            $test->Foo = 5;
            $this->AssertEquals( 5, $test->Bar, 'Value of Foo should have been changed to 5' );
            $test->Foo = false;
            $this->AssertEquals( false, $test->Bar, 'Value of Foo should have been changed back to false' );
            $test->Foo = true;
            $this->AssertEquals( true, $test->Bar, 'Value of Foo should have been changed to true' );
            $test->Foo = 'Somestring';
            $this->AssertEquals( 'Somestring', $test->Bar, 'Unable to change value of Foo to an arbitrary string' );
            $test->Foo = array( 2, 3, 5, 7, 11 );
            $this->AssertEquals( array( 2, 3, 5, 7, 11 ), $test->Bar, 'Unable to change value of Foo to a non-scalar value' );
            $test->Foo = $this;
            $this->AssertEquals( $this, $test->Bar, 'Unable to change value of Foo to an object' );
        }
        public function TestCreation() {
            $this->Assert( class_exists( 'TestRabbitSatoriExtension' ) );
            $this->mObj = New TestRabbitSatoriExtension();
            $this->AssertFalse( $this->mObj->Exists(), 'New Satori-derived object should not exist prior to saving' );
            $this->mObj->Save();
            $this->AssertTrue( $this->mObj->Exists(), 'New Satori-derived object should exist after saving' );
        }
        public function TestDefaults() {
            $this->AssertEquals( 'abcd', $this->mObj->Char, 'Default values did not load using LoadDefaults()' );
            $this->AssertFalse( false, $this->mObj->Int, 'Default values not set using LoadDefaults() should default to false' );
        }
        public function TestAssignment() {
            $this->mObj->Char = 'cool';
            $this->AssertEquals( 'cool', $this->mObj->Char, 'Could not assign string Satori attribute' );
            $this->mObj->Int = 5;
            $this->AssertEquals( 5, $this->mObj->Int, 'Could not assign integer Satori attribute' );
        }
        public function TestDeletion() {
            $this->mObj->Delete();
            $this->AssertFalse( $this->mObj->Exists(), 'Satori-derived object should not exist after deletion' );
        }
        public function TearDown() {
            $this->mDb->DetachTable( 'rabbit_satori_test' );
            $this->mDbTable->Delete();
        }
    }
    
    return New TestRabbitSatori();
?>
