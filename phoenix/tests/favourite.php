<?php

       class TestFavourites extends Testcase {
        protected $mAppliesTo = 'libs/favourite';
        private $mUser;
        private $mUser2;
        private $mJournal;
        private $mJournal2;

        public function SetUp() {
            global $libs;
            $libs->Load( 'favourite' );
            $libs->Load( 'journal' );

            $ufinder = New UserFinder();
            $user = $ufinder->FindByName( 'testfavourite' );
            if ( is_object( $user ) ) {
                $user->Delete();
            }

            $this->mUser = New User();
            $this->mUser->Name = 'testfavourite';
            $this->mUser->Subdomain = 'testfavourite';
            $this->mUser->Save();

            $this->mUser2 = New User();
            $this->mUser2->Name = 'testfavourite2';
            $this->mUser2->Subdomain = 'testfavourite2';
            $this->mUser2->Save();

            $this->mJournal = New Journal();
            $this->mJournal->Userid = $this->mUser->Id;
            $this->mJournal->Title = 'Testing favourites';
            $this->mJournal->Text = 'You should not be reading this :P';
            $this->mJournal->Save();

            $this->mJournal2 = New Journal();
            $this->mJournal2->Userid = $this->mUser2->Id;
            $this->mJournal2->Title = 'Testing favourites 2';
            $this->mJournal2->Text = 'Foo bar blah';
            $this->mJournal2->Save();
        }
        public function TestCheckNonExisting() {
            $finder = New FavouriteFinder();
            $this->AssertFalse( is_object( $finder->FindByUserAndEntity( $this->mUser, $this->mJournal ) ), 'FavouriteFinder::FindByUserAndEntity returned object on non-existing favourite' );
        }
        public function TestCreation() {
            $favourite = New Favourite();
            $favourite->Userid = $this->mUser->Id;
            $favourite->Itemid = $this->mJournal->Id;
            $favourite->Typeid = TYPE_JOURNAL;
            $favourite->Save();

            $f = New Favourite( $favourite->Id );
            $this->AssertEquals( $this->mUser->Id, $f->Userid, 'Wrong userid' );
            $this->AssertEquals( $this->mJournal->Id, $f->Itemid, 'Wrong itemid' );
            $this->AssertEquals( TYPE_JOURNAL, $f->Typeid, 'Wrong typeid' );
            $this->AssertEquals( $this->mJournal->Title, $f->Item->Title, 'Wrong item title' );
        }
        public function TestCheckCreated() {
            $finder = New FavouriteFinder();

            $favourite = $finder->FindByUserAndEntity( $this->mUser, $this->mJournal );
            $this->Assert( is_object( $favourite ), 'FavouriteFinder::FindByUserAndEntity did not return an object on existing favourite' );
            $this->AssertEquals( $this->mJournal->Id, $favourite->Itemid, 'Wrong itemid' );
            $this->AssertEquals( $this->mUser->Id, $favourite->Userid, 'Wrong userid' );
            
            $this->AssertFalse( is_object( $finder->FindByUserAndEntity( $this->mUser2, $this->mJournal ) ), 'FavouriteFinder::FindByUserAndEntity returned object on non-existing favourite' );
        }
        public function TestFindByEntity() {
            $favourite = New Favourite();
            $favourite->Userid = $this->mUser2->Id;
            $favourite->Itemid = $this->mJournal->Id;
            $favourite->Typeid = TYPE_JOURNAL;
            $favourite->Save();

            $finder = New FavouriteFinder();
            $favourites = $finder->FindByEntity( $this->mJournal );
            
            $this->AssertEquals( 2, count( $favourites ),'Wrong number of favourites' );
            $this->AssertEquals( $this->mUser->Id, $favourites[ 0 ]->Userid, 'Wrong first userid' );
            $this->AssertEquals( $this->mUser2->Id, $favourites[ 1 ]->Userid, 'Wrong second userid' );
        }
        public function TestFindByUserAndType() {
            $favourite = New Favourite();
            $favourite->Userid = $this->mUser->Id;
            $favourite->Itemid = $this->mJournal2->Id;
            $favourite->Typeid = TYPE_JOURNAL;
            $favourite->Save();

            $finder = New FavouriteFinder();
            $favourites = $finder->FindByUserAndType( $this->mUser, TYPE_JOURNAL );

            $this->AssertEquals( 2, count( $favourites ), 'Wrong number of favourites' );
            $this->AssertEquals( $this->mJournal->Title, $favourites[ 0 ]->Item->Title, 'Wrong first item title' );
            $this->AssertEquals( $this->mJournal2->Title, $favourites[ 1 ]->Item->Title, 'Wrong second item title' );        
        }
        /*
        public function TestDeletion() {
            $test = new User( 'test' );
            $journal = new Journal( 2 );
                
            $favourite = new Favourite;
            $favourite->Item = $journal;
            $favourite->User = $test;

            $this->AssertTrue( $favourite->Exists(), 'Favourite did not exist before deleting' );
            $favourite->Delete();
            $this->AssertFalse( $favourite->Exists(), 'Favourite exists after deletion' );
        }
        public function TestCheckDeleted() {
            $test = new User( 'test' );
            $journal = new Journal( 2 );

            $journal2 = new Journal( 3 );

            $this->AssertFalse( Favourite_Check( $test, $journal ), 'Favourite deleted but Favourite_Check still returned true' );
            $this->AssertTrue( Favourite_Check( $test, $journal2 ), 'Favourite_Check returned wrong value after deleting another favourite' );
        }
        */
        public function TearDown() {
            if ( is_object( $this->mUser ) ) {
                $this->mUser->Delete();
            }
            if ( is_object( $this->mUser2 ) ) {
                $this->mUser2->Delete();
            }
            if ( is_object( $this->mJournal ) ) {
                $this->mJournal->Delete();
            }
            if ( is_object( $this->mJournal2 ) ) {
                $this->mJournal2->Delete();
            }
        }
    }

    return new TestFavourites();

?>
