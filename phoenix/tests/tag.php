<?php

	class TestTag extends Testcase {
		protected $mAppliesTo='libs/tag';
		private $mUser1;
		private $mUser2;
		private $mBookTag;
		private $mMovieTag1;
		private $mMovieTag2;
		private $mMovieTag3;
		private $mBookTag2;
		private $mBookTag3;
		private $mBookTag4;

		public function SetUp() {
			global $libs;
			$libs->Load( 'tag' );
			
			$finder = New UserFinder();
			$users = $finder->FindByName( 'testtag1' );
			if ( is_object( $users ) ) {
				$users->Delete();
			}
			$users = $finder->FindByName( 'testtag2' );
			if ( is_object( $users ) ) {
				$users->Delete();
			}

			$user = New User();
			$user->Name = 'testtag1';
			$user->Subdomain = 'testtag1';
			$user->Save();

			$this->mUser1 = $user;

			$user = New User();
			$user->Name = 'testtag2';
			$user->Subdomain = 'testtag2';
			$user->Save();

			$this->mUser2 = $user;
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
			$this->Assert( method_exists( $finder, 'FindByUser' ), 'TagFinder::FindByUser method does not exist' );
			$this->Assert( method_exists( $finder, 'FindByTextAndType' ), 'TagFinder::FindByTextAndType method does not exist' );
		}
		public function TestCreate() {
			$user = $this->mUser1;

			$tag = New Tag();
			$tag->Userid = $user->Id;
			$tag->Typeid = TAG_MOVIE;
			$tag->Text = 'Sin City';
			$this->AssertFalse( $tag->Exists(), 'Tag appears to exist before saving' );
			$tag->Save();
			$this->Assert( $tag->Exists(), 'Tag does not appear to exist after saving' );

			$tag = New Tag();
			$tag->Userid = $user->Id;
			$tag->Typeid = TAG_BOOK;
			$tag->Text = 'The journal of a Magus';
			$tag->Save();

			$this->mBookTag = $tag;
			
			$tag = New Tag();
			$tag->Userid = $user->Id;
			$tag->Typeid = TAG_BOOK;
			$tag->Text = 'The Trial';
			$tag->Nextid = $this->mBookTag->Id;
			$tag->Save();
			$this->mBookTag4 = $tag;

			$user = $this->mUser2;

			$tag1 = New Tag();
			$tag1->Typeid = TAG_MOVIE;
			$tag1->Userid = $user->Id;
			$tag1->Text = 'Sin City';
			$tag1->Save();

			$this->mMovieTag1 = $tag1;

			$tag2 = New Tag();
			$tag2->Userid = $user->Id;
			$tag2->Typeid = TAG_MOVIE;
			$tag2->Text = 'Straight Story'; // NOTICE: Straight Story by David Lynch; not to be confused with the greek comedy. <--Does this comment make the code more readable? :P
			$tag2->Nextid = $tag1->Id;
			$tag2->Save();
			
			$this->mMovieTag2 = $tag2;

			$this->mMovieTag3 = New Tag();
			$this->mMovieTag3->Userid = $user->Id;
			$this->mMovieTag3->Typeid = TAG_MOVIE;
			$this->mMovieTag3->Text = 'Fooland';
			$this->mMovieTag3->Nextid = $tag2->Id;
			$this->mMovieTag3->Save();
			
			$this->mBookTag2 = New Tag();
			$this->mBookTag2->Userid = $user->Id;
			$this->mBookTag2->Typeid = TAG_BOOK;
			$this->mBookTag2->Text = "Kama Sutra";
			$this->mBookTag2->Save();
			
			$this->mBookTag3 = New Tag();
			$this->mBookTag3->Userid = $user->Id;
			$this->mBookTag3->Typeid = TAG_BOOK;
			$this->mBookTag3->Text = "Sutra Kama";
			$this->mBookTag3->Nextid = $this->mBookTag2->Id;
			$this->mBookTag3->Save();

			
		}
		public function TestFindByUser() {
			$finder = New TagFinder();
			$tags = $finder->FindByUser( $this->mUser2 );
			
			$this->Assert( is_array( $tags ), 'Finder::FindByUser did not return an array' );
			$this->AssertEquals( 5, count( $tags ), 'Finder::FindByUser did not return the right number of tags' );
			
			// Two feasible solutions
			$texts1 = array( 'Fooland', 'Straight Story', 'Sin City', 'Sutra Kama', 'Kama Sutra' );
			$types1 = array( TAG_MOVIE, TAG_MOVIE, TAG_MOVIE, TAG_BOOK, TAG_BOOK );
			
			$texts2 = array( 'Sutra Kama', 'Kama Sutra', 'Fooland', 'Straight Story', 'Sin City' );
			$types2 = array( TAG_MOVIE, TAG_BOOK, TAG_BOOK, TAG_MOVIE, TAG_MOVIE );
			
			$texts = ( $texts1[0] == $tags[0]->Text )?$texts1:$texts2;
			$types = ( $types1[0] == $tags[0]->Typeid )?$types1:$types2;
			for ( $i = 0; $i < 5; ++$i ) {
				$tag = $tags[ $i ];
				$this->Assert( $tag instanceof Tag, 'Finder::FindByUser did not return an array of tags' );
				$this->AssertEquals( $texts[$i], $tag->Text, 'Tag returned by Finder::FindByUser doesn\'t have the right text or is not in the right order' );
				$this->AssertEquals( $types[$i], $tag->Typeid, 'Tag returned by Finder::FindByUser doesn\'t have the right type or is not in the right order' );
			}
		}
		public function TestFindByTextAndType() {
			$finder = New TagFinder();
			$tags = $finder->FindByTextAndType( 'Sin City', TAG_MOVIE );

			$this->Assert( is_array( $tags ), 'Finder::FindByTextAndType did not return an array' );
			$this->AssertEquals( 2, count( $tags ), 'Finder::FindByTextAndType did not return the right number of tags' );
			
			$users1 = array( 'testtag1', 'testtag2' );
			$users2 = array( 'testtag2', 'testtag1' );
			
			$users = ( $users1[0] == $tags[0]->User->Name )?$users1:$users2;
			for ( $i = 0; $i < 2; ++$i ) {
				$tag = $tags[ $i ];
				$this->Assert( $tag instanceof Tag, 'Finder::FindByTextAndType did not return an array of tags' );
				$this->AssertEquals( $users[ $i ], $tag->User->Name, 'Tag returned by Finder::FindByTextAndType doesn\'t have the right user, or it is returned in wrong order' );
			}
		}
		public function TestFindSuggestions() {
			$finder = New TagFinder();
			$texts = $finder->FindSuggestions( 'S', TAG_MOVIE );

			$this->Assert( is_array( $texts ), 'Finder::FindSuggestions did not return an array' );
			
			foreach ( $texts as $text ) {
				$this->Assert( is_string( $text ), 'Finder::FindSuggestions did not return an array of strings' );
				$this->AssertEquals( 'S', $text[ 0 ], 'Finder::FindSuggestions returned a wrong text' );
			}
		}
		public function TestEdit() {
			// no ability to edit tags
		}
		public function TestReorder() {
			$finder = New TagFinder();

			$this->mBookTag->MoveBefore( $this->mBookTag4 );

			$tags = $finder->FindByUser( $this->mUser1 );
			if ( $tags[0]->Text == "Sin City" ) {
				array_shift( $tags );
			}
			else {
				array_pop( $tags );
			}

			$texts = array( 'The journal of a Magus', 'The Trial' );
			$types = array( TAG_BOOK, TAG_BOOK );
			
			$this->AssertEquals( 2, count( $tags ), 'Finder::FindByUser did not return the right number of tags' );
			for ( $i = 0; $i < 1; ++$i ) {
				$tag = $tags[ $i ];
				$this->Assert( $tag instanceof Tag, 'Finder::FindByUser did not return an array of tags bt an array of' . get_class( $tag ) );
				$this->AssertEquals( $texts[ $i ], $tag->Text, 'Tag returned by Finder::FindByUser doesn\'t have the right text, or it is returned in wrong order' );
				$this->AssertEquals( $types[ $i ], $tag->Typeid, 'Tag returned by Finder::FindByUser doesn\'t have the right type, or it is returned in wrong order' );
			}

			$this->mBookTag->MoveAfter( $this->mBookTag4 );

			$tags = $finder->FindByUser( $this->mUser1 );
			
			if ( $tags[0]->Text == "Sin City" ) {
				array_shift( $tags );
			}
			else {
				array_pop( $tags );
			}

			$texts = array( 'The Trial', 'The journal of a Magus' );
			$types = array( TAG_BOOK, TAG_BOOK );
			for ( $i = 0; $i < 1; ++$i ) {
				$tag = $tags[ $i ];
				$this->Assert( $tag instanceof Tag, 'Finder::FindByUser did not return an array of tags' );
				$this->AssertEquals( $texts[ $i ], $tag->Text, 'Tag returned by Finder::FindByUser doesn\'t have the right text, or it is returned in wrong order' );
				$this->AssertEquals( $types[ $i ], $tag->Typeid, 'Tag returned by Finder::FindByUser doesn\'t have the right type, or it is returned in wrong order' );
			}

		}
		public function TestDelete() {
			$this->Assert( $this->mBookTag->Exists(), 'Tag does not appear to exist before deleting' );
			$this->mBookTag->Delete();
			$this->AssertFalse( $this->mBookTag->Exists(), 'Tag appears to exist after deleting' );

			$finder = New TagFinder();
			$tags = $finder->FindByUser( $this->mUser1 );

			$this->Assert( is_array( $tags ), 'Finder::FindByUser did not return an array' );
			$this->AssertEquals( 2, count( $tags ), 'Finder::FindByUser did not return the right number of tags' );
			
			$texts1 = array( 'Sin City', 'The Trial' );
			$types1 = array( TAG_MOVIE, TAG_BOOK );
			
			$texts2 = array( 'The Trial', 'Sin City' );
			$types2 = array( TAG_BOOK, TAG_MOVIE );
			
			$texts = ( $texts1[0] == $tags[0]->Text )?$texts1:$texts2;
			$types = ( $types1[0] == $tags[0]->Typeid )?$types1:$types2;
			for ( $i = 0; $i < 1; ++$i ) {
				$tag = $tags[ $i ];
				$this->Assert( $tag instanceof Tag, 'Finder::FindByUser did not return an array of tags' );
				$this->AssertEquals( $texts[ $i ], $tag->Text, 'Tag returned by Finder::FindByUser doesn\'t have the right text, or it is returned in wrong order' );
				$this->AssertEquals( $types[ $i ], $tag->Typeid, 'Tag returned by Finder::FindByUser doesn\'t have the right type, or it is returned in wrong order' );
			}
		}
		public function TestClear() {
			Tag_Clear( $this->mUser1 );

			$finder = New TagFinder();

			$tags = $finder->FindByUser( $this->mUser1 );
			$this->Assert( is_array( $tags ), 'TagFinder::FindByUser did not return an array' );
			$this->Assert( empty( $tags ), 'Array returned by TagFinder::FindByUser, after calling Tag_Clear, was not empty' );

			Tag_Clear( $this->mUser2 ); // this should accept user objects or user ids!
		}
		public function TearDown() {
			if ( is_object( $this->mUser1 ) && $this->mUser1->Exists() ) {
				$this->mUser1->Delete();
			}
			if ( is_object( $this->mUser2 ) && $this->mUser2->Exists() ) {
				$this->mUser2->Delete();
			}
			if ( is_object( $this->mBookTag ) && $this->mBookTag->Exists() ) {
				$this->mBookTag->Delete();
			}
			if ( is_object( $this->mMovieTag1 ) && $this->mMovieTag1->Exists() ) {
				$this->mMovieTag1->Delete();
			}
			if ( is_object( $this->mMovieTag2 ) && $this->mMovieTag2->Exists() ) {
				$this->mMovieTag2->Delete();
			}
			if ( is_object( $this->mMovieTag3 ) && $this->mMovieTag3->Exists() ) {
				$this->mMovieTag3->Delete();
			}
			if ( is_object( $this->mBookTag2 ) && $this->mBookTag2->Exists() ) {
				$this->mBookTag2->Delete();
			}
			if ( is_object( $this->mBookTag3 ) && $this->mBookTag3->Exists() ) {
				$this->mBookTag3->Delete();
			}
		}
	}

	return New TestTag();

?>
