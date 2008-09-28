<?php

	class TestNotification extends Testcase {
		protected $mAppliesTo = 'libs/notify';
		private $mUser;
		private $mUser2;
		private $mJournal;

		private function CreateRelationType( $text ) {
			$typefinder = New RelationTypeFinder();
			$type = $typefinder->FindByText( $text );
			if ( !is_object( $type ) ) {
				$type = New RelationType();
				$type->Text = $text;
				$type->Save();
			}

			return $type;
		}
		private function DeleteRelationType( $text ) {
			$typefinder = New RelationTypeFinder();
			$type = $typefinder->FindByText( $text );
			if ( is_object( $type ) ) {
				$type->Delete();
			}
		}
		public function SetUp() {
			global $libs;
			$libs->Load( 'notify' );
			$libs->Load( 'comment' );
			$libs->Load( 'relation/relation' );

			/* standar stuff */
			$ufinder = New UserFinder();
			$user = $ufinder->FindByName( 'testnotify' );
			if ( is_object( $user ) ) {
				$user->Delete();
			}
			$user = $ufinder->FindByName( 'testnotify2' );
			if ( is_object( $user ) ) {
				$user->Delete();
			}

			$this->mUser = New User();
			$this->mUser->Name = 'testnotify';
			$this->mUser->Subdomain = 'testnotify';
			$this->mUser->Save();

			$this->mUser2 = New User();
			$this->mUser2->Name = 'testnotify2';
			$this->mUser2->Subdomain = 'testnotify2';
			$this->mUser2->Save();

			$this->mJournal = New Journal();
			$this->mJournal->Userid = $this->mUser2->Id;
			$this->mJournal->Title = 'Test notifications!';
			$this->mJournal->Text = 'foo bar';
			$this->mJournal->Save();
		}
		public function TestCommentFiring() {
			$comment = New Comment();
			$comment->Userid = $this->mUser->Id;
			$comment->Typeid = TYPE_JOURNAL;
			$comment->Itemid = $this->mJournal->Id;
			$comment->Text = "foo bar blah";
			$comment->Parentid = 0;
			$comment->Save();

			$comment1 = New Comment();
			$comment1->Userid = $this->mUser->Id;
			$comment1->Typeid = TYPE_JOURNAL;
			$comment1->Itemid = $this->mJournal->Id;
			$comment1->Text = "notification";
			$comment1->Parentid = 0;
			$comment1->Save();

			/* this should not show up on the finder.. */
			$comment2 = New Comment();
			$comment2->Userid = $this->mUser2->Id;
			$comment2->Typeid = TYPE_JOURNAL;
			$comment2->Itemid = $this->mJournal->Id;
			$comment2->Text = "hahaha";
			$comment2->Parentid = 0;
			$comment2->Save();

			$comment3 = New Comment();
			$comment3->Userid = $this->mUser2->Id;
			$comment3->Typeid = TYPE_JOURNAL;
			$comment3->Itemid = $this->mJournal->Id;
			$comment3->Text = "lol";
			$comment3->Parentid = $comment->Id;
			$comment3->Save();

			$finder = New NotificationFinder();
			$notifs = $finder->FindByUser( $this->mUser2 );

			$this->AssertEquals( 2, count( $notifs ), 'Wrong number of notifications' );
			$texts = array( "notification", "foo bar blah" );
			foreach ( $notifs as $key => $notif ) {
				$this->AssertEquals( $this->mUser->Id, $notif->Fromuserid, 'Wrong notif fromuserid' );
				$this->AssertEquals( $this->mUser2->Id, $notif->Touserid, 'Wrong notif touserid' );
				$this->AssertEquals( $texts[ $key ], $notif->Item->Text, 'Wrong notif item text' );
				$this->AssertEquals( $this->mJournal->Id, $notif->Item->Itemid, 'Wrong notif item itemid' );
			}

			$notifs = $finder->FindByUser( $this->mUser );
			$this->AssertEquals( 1, count( $notifs ), 'Wrong number of notifications for user1' );

			$notif = $notifs[ 0 ];
			$this->AssertEquals( $this->mUser2->Id, $notif->Fromuserid, 'Wrong notif fromuserid' );
			$this->AssertEquals( $this->mUser->Id, $notif->Touserid, 'Wrong notif touserid' );
			$this->AssertEquals( $comment3->Id, $notif->Item->Id, 'Wrong notif item id' );
			$this->AssertEquals( 'lol', $notif->Item->Text, 'Wrong notif item text' );
			$this->AssertEquals( $this->mJournal->Id, $notif->Item->Itemid, 'Wrong notif item itemid' );

			$comment->Delete();
			$comment1->Delete();
			$comment2->Delete();
			$comment3->Delete();

			$notifs = $finder->FindByUser( $this->mUser2 );
			$this->AssertEquals( 0, count( $notifs ), 'Wrong number of notifications after deleting comments' );
		}
		public function TestRelationFiring() {
			$lover = $this->CreateRelationType( 'lover' );

			$relation = New FriendRelation();
			$relation->Userid = $this->mUser->Id;
			$relation->Friendid = $this->mUser2->Id;
			$relation->Typeid = $lover->Id;
			$relation->Save();

			$finder = New NotificationFinder();
			$notifs = $finder->FindByUser( $this->mUser2 );
			$this->AssertEquals( 1, count( $notifs ), 'Wrong number of notifications' );

			$notif = $notifs[ 0 ];
			$this->AssertEquals( $this->mUser->Id, $notif->Fromuserid, 'Wrong notif fromuserid' );
			$this->AssertEquals( $this->mUser2->Id, $notif->Touserid, 'Wrong notif touserid' );
			$this->AssertEquals( $lover->Id, $notif->Item->Typeid, 'Wrong notif item typeid' );

			$relation->Delete();

			$notifs = $finder->FindByUser( $this->mUser2 );
			$this->AssertEquals( 0, count( $notifs ), 'Wrong number of notifications after deleting relation' );

			$this->DeleteRelationType( 'lover' );
		}
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
		}
	}

	return New TestNotification();

?>
