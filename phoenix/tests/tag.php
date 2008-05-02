<?php

    class TestTag extends Testcase {
        protected $mAppliesTo='libs/tag';

        public function SetUp() {
            $finder = UserFinder();
            $users = $finder->FindByName( 'testtag1' );
            $this->RequireSuccess( $this->Assert( empty( $users ) ) );
            $users = $finder->FindByName( 'testtag2' );
            $this->RequireSuccess( $this->Assert( empty( $users ) ) );

            $user = New User();
            $user->Name = 'testtag1';
            $user->Save();

            $user = New User();
            $user->Name = 'testtag2';
            $user->Save();
        }
        public function TestClassesExist() {
            $this->Assert( class_exists( 'Tag' ), 'Tag class does not exist' );
            $this->Assert( class_exists( 'TagFinder' ), 'TagFinder class does not exist' );
        }
        public function TestFunctionsExist() {
            $this->Assert( function_exists( 'Tag_Clear' ), 'Tag_Clear function does not exist' );
        }
        public function TestMethodsExist() {
            $tag = New Tag();

            $this->Assert( method_exists( $tag, 'Save' ), 'Tag::Save method does not exist' );
            $this->Assert( method_exists( $tag, 'Delete' ), 'Tag::Delete method does not exist' );
            $this->Assert( method_exists( $tag, 'Exists' ), 'Tag::Exists method does not exist' );
            $this->Assert( method_exists( $tag, 'MoveAfter' ), 'Tag::MoveAfter method does not exist' );
            $this->Assert( method_exists( $tag, 'MoveBefore' ), 'Tag::MoveBefore method does not exist' );

            $finder = New TagFinder();
            $this->Assert( method_exists( $tag, 'FindByUser' ), 'TagFinder::FindByUser method does not exist' );
            $this->Assert( method_exists( $tag, 'FindByTextAndType' ), 'TagFinder::FindByTextAndType method does not exist' );
        }
        public function TestCreate() {
            $user = New User( 'testtags' );

            $tag = New Tag();
            $tag->Userid = $user->Id;
            $tag->Type = TAG_MOVIE;
            $tag->Text = 'Sin City';
            $this->AssertFalse( $tag->Exists(), 'Tag appears to exist before saving' );
            $tag->Save();
            $this->Assert( $tag->Exists(), 'Tag does not appear to exist after saving' );

            $tag = New Tag();
            $tag->Userid = $user->Id;
            $tag->Type = TAG_BOOK;
            $tag->Text = 'The journal of a Magus';
            $tag->Save();

            $user = New User( 'testtags2' );

            $tag = New Tag();
            $tag->Type = TAG_MOVIE;
            $tag->Userid = $user->Id;
            $tag->Text = 'Sin City';
            $tag->Save();

            $tag = New Tag();
            $tag->Userid = $user->Id;
            $tag->Type = TAG_MOVIE;
            $tag->Text = 'Straight Story'; // NOTICE: Straight Story by David Lynch; not to be confused with the greek comedy.
            $tag->Save();
        }
        public function TestFindByUser() {
            $finder = New TagFinder();
            $tags = $finder->FindByUser( New User( 'testtag1' ) );
            
            $this->Assert( is_array( $tags ), 'Finder::FindByUser did not return an array' );
            $this->AssertEquals( 2, count( $tags ), 'Finder::FindByUser did not return the right number of tags' );
            
            $texts = array( 'Sin City', 'Journal of a Magus' );
            $types = array( TYPE_MOVIE, TYPE_BOOK );
            for ( $i = 0; $i < 2; ++$i ) {
                $tag = $tags[ $i ];
                $this->Assert( $tag instanceof Tag, 'Finder::FindByUser did not return an array of tags' );
                $this->AssertEquals( $texts[ $i ], $tag->Text, 'Tag returned by Finder::FindByUser doesn\'t have the right text, or it is returned in wrong order' );
                $this->AssertEquals( $types[ $i ], $tag->Type, 'Tag returned by Finder::FindByUser doesn\'t have the right type, or it is returned in wrong order' );
            }
        }
        public function TestFindByTextAndType() {
            $finder = New TagFinder();
            $tags = $finder->FindByTextAndType( 'Sin City', TAG_MOVIES );

            $this->Assert( is_array( $tags ), 'Finder::FindByTextAndType did not return an array' );
            $this->AssertEquals( 2, count( $tags ), 'Finder::FindByTextAndType did not return the right number of tags' );
            
            $users = array( 'testtag1', 'testtag2' );
            for ( $i = 0; $i < 2; ++$i ) {
                $tag = $tags[ $i ];
                $this->Assert( $tag instanceof Tag, 'Finder::FindByTextAndType did not return an array of tags' );
                $this->AssertEquals( $users[ $i ], $tag->User->Name, 'Tag returned by Finder::FindByTextAndType doesn\'t have the right user, or it is returned in wrong order' );
            }
        }
        public function TestFindSuggestions() {
            $finder = New TagFinder();
            $texts = $inder->FindSuggestions( 'S', TAG_MOVIES );

            $this->Assert( is_array( $tags ), 'Finder::FindSuggestions did not return an array' );
            
            foreach ( $texts as $text ) {
                $this->Assert( is_string( $text ), 'Finder::FindSuggestions did not return an array of strings' );
                $this->AssertEquals( 'S', $text[ 0 ], 'Finder::FindSuggestions returned a wrong text' );
            }
        }
        public function TestEdit() {
            // no ability to edit tags
        }
        public function TestDelete() {
            // TODO
        }
        public function TestReorder() {
            // TODO
        }
        public function TestClear() {
            $user = New User( 'testtag1' );
            
            Tag_Clear( $user );
            $finder = New TagFinder();
            $tags = $finder->FindByUser( $user );
            $this->Assert( is_array( $tags ), 'TagFinder::FindByUser did not return an array' );
            $this->Assert( empty( $tags ), 'Array returned by TagFinder::FindByUser, after calling Tag_Clear, was not empty' );

            $user2 = New User( 'testtag2' );
            Tag_Clear( $user2->Id ); // this should accept user objects or user ids!
        }
        public function TearDown() { // Crying?
            $user = New User( 'testtag1' );
            $user->Delete();

            $user = New User( 'testtag2' );
            $user->Delete();
        }
    }

    /*
        private function Reorder() {
            $list = $this->mClass . '_List';

            // moving tags
            $test = New User( 'testtags2' );
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
        
        private function Deletion() {
            $list = $this->mClass . '_List';

            $test = New User( 'testtags2' );
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
    */

    return New TestTag();

?>
