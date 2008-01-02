<?php
    class TestTag extends Testcase {
        private $mClass;

        private function ClassesExist() {
            $this->Assert( class_exists( $this->mClass ), $this->mClass . ' class does not exist' );
        }
        
        private function FunctionsExist() {
            $this->Assert( function_exists( $this->mClass . '_List' ), $this->mClass . '_List function does not exist' );
            $this->Assert( function_exists( $this->mClass . '_Clear' ), $this->mClass . '_Clear function does not exist' );
        }
        
        private function MethodsExist() {
            $tag = New $this->mClass(); // MAGIC!
            $this->Assert( method_exists( $tag, 'Save' ), $this->mClass . '::Save method does not exist' );
            $this->Assert( method_exists( $tag, 'Delete' ), $this->mClass . '::Delete method does not exist' );
            $this->Assert( method_exists( $tag, 'MoveAfter' ), $this->mClass . '::MoveAfter method does not exist' );
            $this->Assert( method_exists( $tag, 'MoveBefore' ), $this->mClass . '::MoveBefore method does not exist' );
            $this->Assert( method_exists( $tag, 'Exists' ), $this->mClass . '::Exists method does not exist' );
        }
        
        private function Clear() {
            $clear = $this->mClass . '_Clear';
            $list = $this->mClass . '_List';

            $test = New User( 'test' );
            $clear( $test ); // MAGIC!
            $list = $list( $test ); // MAGIC!
            $this->Assert( is_array( $list ), $list . ' did not return an array' );
            $this->Assert( empty( $list ), $list . ' did not return an empty array after Clear()' );
        }
        
        private function Creation() {
            $test = New User( 'test' );
            // creating a new tag
            $tag = New $this->mClass(); // MAGIC!
            $this->AssertFalse( $tag->Exists(), 'Empty new ' . $this->mClass . ' appears to Exist' );
            $tag->User = $test;
            $tag->Text = 'Sin City';
            $tag->Save();
            $this->AssertTrue( $tag->Exists(), 'Freshly saved InterestTag appears not to Exist' );
            $this->AssertEquals( 'Sin City', $tag->Text, 'Freshly saved InterestTag does not contain the text it was assigned' );
            $this->AssertEquals( $test, $tag->User, 'Freshly saved InterestTag does not contain the user it was assigned' );
            $tag = New $this->mClass();
            $tag->User = $test;
            $tag->Text = 'Parkour';
            $tag->Save();
        }
        
        private function QueryNonExisting() {
            $test = New User( 'test' );
            $tag = New $this->mClass( 'some non-existing tag', $test );
            $this->AssertFalse( $tag->Exists(), 'Querying a non-existing tag yields to an existing tag' );
        }
        
        private function QueryExisting() {
            $test = New User( 'test' );
            $tag1 = New $this->mClass( 'Sin City', $test );
            $tag2 = New $this->mClass( 'Parkour', $test );
            $this->AssertTrue( $tag1->Exists(), 'I just created tag Sin City but it doesn\'t seem to exist' );
            $this->AssertTrue( $tag2->Exists(), 'I just created tag Parkour but it doesn\'t seem to exist' );
            $this->AssertEquals( 'Sin City', $tag1->Text, 'Sin City tag doesn\'t have text "Sin City"' );
            $this->AssertEquals( 'Parkour', $tag2->Text, 'Parkour tag doesn\'t have text "Parkour"' );
            $this->AssertEquals( $test, $tag1->User, 'I ain\'t the owner of Sin City, while I just created it' );
            $this->AssertEquals( $test, $tag2->User, 'I ain\'t the owner of Parkour, while I just created it' );
        }
        
        private function Edit() {
            // no ability to edit tags!
        }
        
        private function ValidText() {
            $valid = $this->mClass . '_Valid';

        	$this->AssertFalse( $valid( "Dog gy" ), $valid . ' did not recognise an invalid tag' );
        	$this->AssertFalse( $valid( "Pup,py" ), $valid . ' did not recognise an invalid tag' );
        	$this->AssertFalse( $valid( "" ), $valid . ' did not recognise an invalid tag' );
        	$this->AssertFalse( $valid( " lol " ), $valid . ' recognised a valid tag as invalid' );
        	$this->AssertFalse( $valid( "    " ), $valid . ' did not recognise an invalid tag' );
        }
        
        private function ListUsertags() {
            $list = $this->mClass . '_List';

            $test = New User( 'test' );
            // listing the tags of a user
            $tags = $list( $test );
            $this->Assert( is_array( $tags ), $list . ' did not return an array after creating a few tags' );
            $this->AssertEquals( 2, count( $tags ), $list . ' did not return two tags, while I created two' );
            $i = 0;
            foreach ( $tags as $tag ) { // they should be returned in order
                // retrieving information about a particular tag
                $text = $tag->Text;
                switch ( $i ) {
                    case 0:
                        $this->AssertEquals( 'Sin City', $text, 'The first tag I created was Sin City' );
                        break;
                    case 1:
                        $this->AssertEquals( 'Parkour', $text, 'The second tag I created was Parkour' );
                        break;
                }
                ++$i;
            }
        }
        
        private function Reorder() {
            $list = $this->mClass . '_List';

            // moving tags
            $test = New User( 'test' );
            $tag1 = New $this->mClass( 'Sin City', $test );
            $tag2 = New $this->mClass( 'Parkour', $test );
            $tag1->MoveAfter( $tag2 );
            $tags = $list( $test );
            $this->Assert( is_array( $tags ), 'InterestTag_List did not return an array after I tried to MoveAfter a tag' );
            $this->AssertEquals( 2, count( $tags ), 'InterestTag_List returned an incorrect number of items after I tried to MoveAfter a tag' );
            $i = 0;
            foreach ( $tags as $tag ) {
                $this->Assert( $tag instanceof $this->mClass, 'InterestTag_List should return an array of InterestTag instances when retrieving a list of tags for a particular user' );
                $text = $tag->Text;
                switch ( $i ) {
                    case 0:
                        $this->AssertEquals( 'Parkour', $text, 'Parkour should have been moved to position #1' );
                        break;
                    case 1:
                        $this->AssertEquals( 'Sin City', $text, 'Sin City should have been moved to position #2' );
                        break;
                }
                ++$i;
            }
            
            $tag1->MoveBefore( $tag2 );
            $tags = $list( $test );
            $this->Assert( is_array( $tags ), $list . ' did not return an array after I tried to MoveBefore a tag' );
            $this->AssertEquals( 2, count( $tags ), $list . ' returned an incorrect number of items after I tried to MoveBefore a tag' );
            $i = 0;
            foreach ( $tags as $tag ) {
                $text = $tag->Text;
                switch ( $i ) {
                    case 0:
                        $this->AssertEquals( 'Sin City', $text, 'Sin City should have been moved back to position #1' );
                        break;
                    case 1:
                        $this->AssertEquals( 'Parkour', $text, 'Parkour should have been moved back to position #2' );
                        break;
                }
                ++$i;
            }
        }
        
        private function ListTexttags() {
            $list = $this->mClass . '_List';

            // listing the tags with a particular text
            $tags = $list( 'Sin City' );
            $test = New User( 'test' );
            $found = false;
            foreach ( $tags as $tag ) { // they should be ordered by order (users who have this tag first show up first), secondarily ordered by reverse creation date (newest users who added it show up first)
                $this->Assert( $tag instanceof $this->mClass, 'InterestTag_List should return an array of InterestTag instances when retrieving a list of tags with a particular text' );
                $user = $tag->User;
                if ( $test->Username() == $user->Username() ) {
                    $found = true;
                }
            }
            $this->Assert( $found, 'I have a tag for Sin City, yet I\'m not in the list of users who have that tag' );
        }
        private function Deletion() {
            $list = $this->mClass . '_List';

            $test = New User( 'test' );
            // deleting an existing tag
            $tag = New $this->mClass( 'Sin City', $test );
            $this->AssertTrue( $tag->Exists(), 'Sin City should exist before I delete it' );
            $tag->Delete();
            $this->AssertFalse( $tag->Exists(), 'Sin City should not exist after I delete it' );
            $tags = $list( $test );
            $this->Assert( is_array( $tags ), 'InterestTag_List did not return an array after deleting a tag' );
            $this->AssertEquals( 1, count( $tags ), 'I had two tags, I deleted one, yet I don\'t have one tag now!' );
            $tag = New $this->mClass( 'Parkour', $test );
            $this->AssertTrue( $tag->Exists(), 'Parkour should exist before I delete it' );
            $tag->Delete();
            $this->AssertFalse( $tag->Exists(), 'Parkour should not exist after I delete it' );
            $tags = $list( $test );
            $this->Assert( is_array( $tags ), 'InterestTag_List does not return an array after I delete all my tags' );
            $this->AssertEquals( 0, count( $tags ), 'InterestTag_List returned a non-empty array even though I don\'t have any tags left' );
        }
        private function ClassTest( $class ) {
            $this->mClass = $class;

            $this->ClassesExist();
            $this->FunctionsExist();
            $this->MethodsExist();
            $this->Clear();
            $this->Creation();
            $this->QueryNonExisting();
            $this->QueryExisting();
            $this->Edit();
            $this->ValidText();
            $this->ListUsertags();
            $this->Reorder();
            $this->ListTexttags();
            $this->Deletion();
        }
        protected function TestInitialize() {
            global $libs;

            $libs->Load( 'tag' );
            $libs->Load( 'artisttag' );
            $libs->Load( 'booktag' );
            $libs->Load( 'interesttag' );
            $libs->Load( 'movietag' );
            $libs->Load( 'songtag' );
            $libs->Load( 'tvshowtag' );
            $libs->Load( 'videogametag' );
        }
        protected function TestArtistTags() {
            $this->ClassTest( 'ArtistTag' );
        }
        protected function TestBookTags() {
            $this->ClassTest( 'BookTag' );
        }
        protected function TestInterestTags() {
            $this->ClassTest( 'InterestTag' );
        }
        protected function TestMovieTags() {
            $this->ClassTest( 'MovieTag' );
        }
        protected function TestSongTags() {
            $this->ClassTest( 'SongTag' );
        }
        protected function TestTvShowTags() {
            $this->ClassTest( 'TvShowTag' );
        }
        protected function TestVideoGameTags() {
            $this->ClassTest( 'VideoGameTag' );
        }
    }

    return New TestTag();

?>
