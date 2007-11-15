<?php
    global $libs;
    $libs->Load( 'interesttag' );
    
    final class TestInterestTag extends Testcase {
        public function TestClassesExist() {
            $this->Assert( class_exists( 'InterestTag' ), 'InterestTag class does not exist' );
        }
        
        public function TestFunctionsExist() {
            $this->Assert( function_exists( 'InterestTag_List' ), 'InterestTag_List function does not exist' );
            $this->Assert( function_exists( 'InterestTag_Clear' ), 'InterestTag_Clear function does not exist' );
        }
        
        public function TestMethodsExist() {
            $tag = New InterestTag();
            $this->Assert( method_exists( $tag, 'Save' ), 'InterestTag::Save method does not exist' );
            $this->Assert( method_exists( $tag, 'Delete' ), 'InterestTag::Delete method does not exist' );
            $this->Assert( method_exists( $tag, 'MoveAfter' ), 'InterestTag::MoveAfter method does not exist' );
            $this->Assert( method_exists( $tag, 'MoveBefore' ), 'InterestTag::MoveBefore method does not exist' );
            $this->Assert( method_exists( $tag, 'Exists' ), 'InterestTag::Exists method does not exist' );
        }
        
        public function TestClear() {
            $test = New User( 'test' );
            InterestTag_Clear( $test );
            $list = InterestTag_List( $test );
            $this->Assert( is_array( $list ), 'InterestTag_List did not return an array' );
            $this->Assert( empty( $list ), 'InterestTag_List did not return an empty array after Clear()' );
        }
        
        public function TestCreation() {
            $test = New User( 'test' );
            // creating a new tag
            $tag = New InterestTag();
            $this->AssertFalse( $tag->Exists(), 'Empty new InterestTag appears to Exist' );
            $tag->User = $test;
            $tag->Text = 'Sin City';
            $tag->Save();
            $this->AssertTrue( $tag->Exists(), 'Freshly saved InterestTag appears not to Exist' );
            $this->AssertEquals( 'Sin City', $tag->Text, 'Freshly saved InterestTag does not contain the text it was assigned' );
            $this->AssertEquals( $test, $tag->User, 'Freshly saved InterestTag does not contain the user it was assigned' );
            $tag = New InterestTag();
            $tag->User = $test;
            $tag->Text = 'Parkour';
            $tag->Save();
        }
        
        public function TestQueryNonExisting() {
            $test = New User( 'test' );
            $tag = New InterestTag( 'some non-existing tag', $test );
            $this->AssertFalse( $tag->Exists(), 'Querying a non-existing tag yields to an existing tag' );
        }
        
        public function TestQueryExisting() {
            $test = New User( 'test' );
            $tag1 = New InterestTag( 'Sin City', $test );
            $tag2 = New InterestTag( 'Parkour', $test );
            $this->AssertTrue( $tag1->Exists(), 'I just created tag Sin City but it doesn\'t seem to exist' );
            $this->AssertTrue( $tag2->Exists(), 'I just created tag Parkour but it doesn\'t seem to exist' );
            $this->AssertEquals( 'Sin City', $tag1->Text, 'Sin City tag doesn\'t have text "Sin City"' );
            $this->AssertEquals( 'Parkour', $tag2->Text, 'Parkour tag doesn\'t have text "Parkour"' );
            $this->AssertEquals( $test, $tag1->User, 'I ain\'t the owner of Sin City, while I just created it' );
            $this->AssertEquals( $test, $tag2->User, 'I ain\'t the owner of Parkour, while I just created it' );
        }
        
        public function TestEdit() {
            // no ability to edit tags!
        }
        
        public function TestValidText() {
        	$this->AssertTrue( InterestTag_Valid( "Dog gy" ), 'InterestTag_Valid did not recognise an invalid tag' );
        	$this->AssertTrue( InterestTag_Valid( "Pup,py" ), 'InterestTag_Valid did not recognise an invalid tag' );
        	$this->AssertTrue( InterestTag_Valid( "" ), 'InterestTag_Valid did not recognise an invalid tag' );
        	$this->AssertTrue( !InterestTag_Valid( " lol " ), 'InterestTag_Valid recognised a valid tag as invalid' );
        	$this->AssertTrue( InterestTag_Valid( "    " ), 'InterestTag_Valid did not recognise an invalid tag' );
        }
        
        public function TestListUsertags() {
            $test = New User( 'test' );
            // listing the tags of a user
            $tags = InterestTag_List( $test );
            $this->Assert( is_array( $tags ), 'InterestTag_List did not return an array after creating a few tags' );
            $this->AssertEquals( 2, count( $tags ), 'InterestTag_List did not return two tags, while I created two' );
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
        
        public function TestReorder() {
            // moving tags
            $test = New User( 'test' );
            $tag1 = New InterestTag( 'Sin City', $test );
            $tag2 = New InterestTag( 'Parkour', $test );
            $tag1->MoveAfter( $tag2 );
            $tags = InterestTag_List( $test );
            $this->Assert( is_array( $tags ), 'InterestTag_List did not return an array after I tried to MoveAfter a tag' );
            $this->AssertEquals( 2, count( $tags ), 'InterestTag_List returned an incorrect number of items after I tried to MoveAfter a tag' );
            $i = 0;
            foreach ( $tags as $tag ) {
                $this->Assert( $tag instanceof InterestTag, 'InterestTag_List should return an array of InterestTag instances when retrieving a list of tags for a particular user' );
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
            $tags = InterestTag_List( $test );
            $this->Assert( is_array( $tags ), 'InterestTag_List did not return an array after I tried to MoveBefore a tag' );
            $this->AssertEquals( 2, count( $tags ), 'InterestTag_List returned an incorrect number of items after I tried to MoveBefore a tag' );
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
        
        public function TestListTexttags() {
            // listing the tags with a particular text
            $tags = InterestTag_List( 'Sin City' );
            $test = New User( 'test' );
            $found = false;
            foreach ( $tags as $tag ) { // they should be ordered by order (users who have this tag first show up first), secondarily ordered by reverse creation date (newest users who added it show up first)
                $this->Assert( $tag instanceof InterestTag, 'InterestTag_List should return an array of InterestTag instances when retrieving a list of tags with a particular text' );
                $user = $tag->User;
                if ( $test->Username() == $user->Username() ) {
                    $found = true;
                }
            }
            $this->Assert( $found, 'I have a tag for Sin City, yet I\'m not in the list of users who have that tag' );
        }

        public function TestDeletion() {
            $test = New User( 'test' );
            // deleting an existing tag
            $tag = New InterestTag( 'Sin City', $test );
            $this->AssertTrue( $tag->Exists(), 'Sin City should exist before I delete it' );
            $tag->Delete();
            $this->AssertFalse( $tag->Exists(), 'Sin City should not exist after I delete it' );
            $tags = InterestTag_List( $test );
            $this->Assert( is_array( $tags ), 'InterestTag_List did not return an array after deleting a tag' );
            $this->AssertEquals( 1, count( $tags ), 'I had two tags, I deleted one, yet I don\'t have one tag now!' );
            $tag = New InterestTag( 'Parkour', $test );
            $this->AssertTrue( $tag->Exists(), 'Parkour should exist before I delete it' );
            $tag->Delete();
            $this->AssertFalse( $tag->Exists(), 'Parkour should not exist after I delete it' );
            $tags = InterestTag_List( $test );
            $this->Assert( is_array( $tags ), 'InterestTag_List does not return an array after I delete all my tags' );
            $this->AssertEquals( 0, count( $tags ), 'InterestTag_List returned a non-empty array even though I don\'t have any tags left' );
        }
    }
    
    return New TestInterestTag();
?>
