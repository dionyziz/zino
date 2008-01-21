<?php
    final class TestFavourites extends Testcase {
        protected $mAppliesTo = 'libs/favourites';
        
        public function SetUp() {
            global $libs;
            $libs->Load( 'favourite' );

            $libs->Load( 'journal' );
            $libs->Load( 'poll' );
            $libs->Load( 'image/image' );
        }
        public function TestClassesExist() {
            $this->Assert( class_exists( 'Favourite' ), 'Favourite class does not exist' );
            $this->Assert( class_exists( 'Poll' ), 'Poll class does not exist' );
            $this->Assert( class_exists( 'Journal' ), 'Journal class does not exist' );
            $this->Assert( class_exists( 'Image' ), 'Image class does not exist' );
        }
        public function TestFunctionsExist() {
            $this->Assert( function_exists( 'Favourite_List' ), 'Favourite_List function does not exist' );
            $this->Assert( function_exists( 'Favourite_Check' ), 'Favourite_Check function does not exist' );
            $this->Assert( function_exists( 'Favourite_Clear' ), 'Favourite_Clear function does not exist' );
        }
        public function TestMethodsExist() {
            $favourite = new Favourite;

            $this->Assert( method_exists( $favourite, 'Save' ), 'Favourite::Save method does not exist' );
            $this->Assert( method_exists( $favourite, 'Delete' ), 'Favourite::Delete method does not exist' );
            $this->Assert( method_exists( $favourite, 'Exists' ), 'Favourite::Exists method does not exist' );
        }
        /*
        public function TestContantsSet() {
            $this->Assert( FAVOURITES_POLLS > 0, 'FAVOURITES_POLLS constant is not set' );
            $this->Assert( FAVOURITES_JOURNALS, 'FAVOURITES_JOURNALS constant is not set' );
            $this->Assert( FAVOURITES_PHOTOS ), 'FAVOURITES_PHOTOS constant is not set' );
            $this->Assert( isset( FAVOURITES_ALL ), 'FAVOURITES_ALL constant is not set' );
        }
        */
        public function TestClearEmpty() {
            $test = new User( 'test' );

            Favourite_Clear( $test );
            $list = Favourite_List( $test, FAVOURITE_ALL );

            $this->Assert( is_array( $list ), 'Favourite_List did not return an array' );
            $this->Assert( empty( $list ), 'Favourite_List did not return an empty array after calling Favourite_Clear' );
        }
        public function TestCheckNonExisting() {
            $test = new User( 'test' );
            $poll = new Poll( 1 );

            $this->AssertFalse( Favourites_Check( $test, $poll ), 'Favourites_Check returned true on a non-existing favourite' );
        }
        public function TestCreation() {
            $test = new User( 'test' );
    
            $poll = new Poll( 1 );
                
            $favourite = new Favourite;
            $favourite->User = $test;
            $favourite->Item = $poll;
            $this->AssertFalse( $favourite->Exists(), 'Favourite exists before Save() method called' );
            $favourite->Save();

            $this->AssertTrue( $favourite->Exists(), 'Favourite::Exists returned false after creation' );
            $this->Assert( $favourite->User == $test, 'Favourite::User different after calling Favourite::Save() method' );
            $this->Assert( $favourite->Item == $poll, 'Favourite::Item different after calling Favourite::Save() method' );

            $poll = new Poll( 2 );

            $favourite = new Favourite;
            $favourite->User = $test;
            $favourite->Item = $poll;
            $favourite->Save();

            $journal = new Journal( 1 );
            
            $favourite = new Favourite;
            $favourite->User = $test;
            $favourite->Item = $journal;
            $favourite->Save();

            $journal = new Journal( 2 );

            $favourite = new Favourite;
            $favourite->Item = $journal;
            $favourite->User = $test;
            $favourite->Save();

            $journal = new Journal( 3 );

            $favourite = new Favourite;
            $favourite->Item = $journal;
            $favourite->User = $test;
            $favourite->Save();

            $photo = new Image( 1 );
            $favourite->Item = $photo;
            $favourite->User = $test;
            $favourite->Save();
        }
        public function TestCheckCreated() {
            $test = new User( 'test' );

            $poll = new Poll( 2 );
            $journal = new Journal( 2 );
            $photo = new Image( 1 );
            $poll2 = new Poll( 10 );
            
            $this->AssertTrue( Favourites_Check( $test, $poll ), 'Favourites_Check returned false on a poll marked as favourite' );
            $this->AssertTrue( Favourites_Check( $test, $journal ), 'Favourites_Check returned false on a journal marked as favourite' );
            $this->AssertTrue( Favourites_Check( $test, $photo ), 'Favourites_Check returned false on a photo marked as favourite' );
            $this->AssertFalse( Favourites_Check( $test, $poll2 ), 'Favourites_Check returned true on a poll that is not marked as favourite' );
        }
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
        public function TestListJournals() {
            $test = new User( 'test' );
            
            $journals = Favourites_List( $test, FAVOURITES_JOURNALS );

            $this->Assert( is_array( $journals ), 'Listing favourite journals did not return an array' );
            $this->Assert( count( $journals ) == 2, 'Listing favourite journals did not return the right number of items' );

            $found = array( 1 => false, 3 => false );
            foreach ( $journals as $journal ) {
                $this->Assert( $journal instanceof Journal, 'Listing favourite journals returned an item that is not instance of Journal class' );
                $this->AssertTrue( $journal->Exists(), 'Favourites_List (journals) returned a non-existing journal' );
                $this->Assert( array_key_exists( $journal->Id, $found ), 'Favourites_List (journals) returned a journal that is not marked as favourite' );
                $found[ $journal->Id ] = true;
            }

            foreach ( $found as $id => $isfound ) {
                $this->AssertTrue( $isfound, 'I marked a journal as favourite but it is not in the list of my favourite journals' );
            }
        }
        public function TestListPolls() {
            $test = new User( 'test' );
            
            $polls = Favourites_List( $test, FAVOURITES_POLLS );

            $this->Assert( is_array( $polls ), 'Listing favourite polls did not return an array' );
            $this->Assert( count( $polls ) == 2, 'Listing favourite polls did not return the right number of items' );

            $found = array( 1 => false, 2 => false );
            foreach ( $polls as $poll ) {
                $this->Assert( $poll instanceof Poll, 'Listing favourite polls returned an item that is not instance of Poll class' );
                $this->AssertTrue( $poll->Exists(), 'Favourites_List (polls) returned a non-existing poll' );
                $this->Assert( array_key_exists( $poll->Id, $found ), 'Favourites_List (polls) returned a poll that is not marked as favourite' );
                $found[ $poll->Id ] = true;
            }

            foreach ( $found as $id => $isfound ) {
                $this->AssertTrue( $isfound, 'I marked a poll as favourite but it is not in the list of my favourite polls' );
            }
        }
        public function TestListAll() {
            $test = new User( 'test' );

            $items = Favourites_List( $test, FAVOURITES_ALL );
            $photo = new Image( 1 );

            $this->Assert( is_array( $items ), 'Favourites_List did not return an array' );
            $this->Assert( count( $items ) == 5, 'Favourites_List (all) did not return the right number of items' );

            $found = false;
            foreach ( $items as $item ) {
                $this->Assert( $item instanceof Poll || $item instanceof Image || $item instanceof Journal, 'Item returned from Favourites_List (all) is not instance of Poll, Image or Journal class' ); 
                $this->AssertTrue( $item->Exists(), 'Item returned from Favourites_List (all) does not exist'  );
                if ( $item == $photo ) {
                    $found = true;
                }
            }

            $this->AssertTrue( $found, 'Image marked as favourite is not in the list of my favourite items' );
        }
        public function TestListMultiple() {
            $test = new User( 'test' );

            $items = Favourites_List( $test, FAVOURITES_JOURNALS | FAVOURITES_PHOTOS );
            $journal = new Journal( 1 );

            $this->Assert( is_array( $items ), 'Listing multiple types of favourites does not return an array' );
            $this->Assert( count( $items ) == 3, 'Listing multiple types of favourites does not return the right number of items' );

            $found = false;
            foreach ( $items as $item ) {
                $this->Assert( $item instanceof Image || $item instanceof Journal, 'Favourites_List (multiple) returned an instance of other class than requested' );
                $this->AssertTrue( $item->Exists(), 'Favourites_List (multiple) returned an item that does not exist' );
                if ( $item == $journal ) {
                    $found = true;
                }
            }

            $this->AssertTrue( $found, 'A journal was marked as favourite but it is not listed in my favourites list' );
        }
        public function TestListItemUsers() {
            $poll = new Poll( 1 );
            $test = new User( "test" );

            $users = Favourites_List( $poll );

            $this->Assert( is_array( $users ), 'Favourites_List did not return an array' );
            $found = false;
            foreach ( $users as $user ) {
                if ( $user->Username() == $test->Username() ) {
                    $found = true;
                } 
            }

            $this->AssertTrue( $found, 'I marked a poll as a favourite but I am not in the list of users who added the poll to their favourites' );
        }
        public function TestClear() {
            $test = new User( 'test' );

            Favourite_Clear( $test );
            $list = Favourite_List( $test, FAVOURITE_ALL );

            $this->Assert( is_array( $list ), 'Favourite_List did not return an array' );
            $this->Assert( empty( $list ), 'Favourite_List did not return an empty array after calling Favourite_Clear' );
        }
    }

    return new TestFavourites();

?>
