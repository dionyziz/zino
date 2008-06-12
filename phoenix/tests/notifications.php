<?php

    class TestNotifications extends Testcase {
        protected $mAppliesTo = 'libs/notify';
        private $mUser;
        private $mUser2;

        public function SetUp() {
            global $libs;
            $libs->Load( 'notify' );
            $libs->Load( 'comment' );

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
        }
        public function TestCommentFiring() {
            $comment = New Comment();
            $comment->Userid = $this->mUser->Id;
            $comment->Typeid = TYPE_USERPROFILE;
            $comment->Itemid = $this->mUser2->Id;
            $comment->Text = "foo bar blah";
            $comment->Parentid = 0;
            $comment->Save();

            $comment1 = New Comment();
            $comment1->Userid = $this->mUser->Id;
            $comment1->Typeid = TYPE_USERPROFILE;
            $comment1->Itemid = $this->mUser2->Id;
            $comment1->Text = "notification";
            $comment1->Save();

            /* this should not show up on the finder.. */
            $comment2 = New Comment();
            $comment2->Userid = $this->mUser2->Id;
            $comment2->Typeid = TYPE_USERPROFILE;
            $comment2->Itemid = $this->mUser2->Id;
            $comment2->Text = "hahaha";
            $comment2->Save();

            $finder = New NotificationFinder();
            $notifs = $finder->FindByUser( $this->mUser2 );

            $this->AssertEquals( 2, count( $notifs ), 'Wrong number of notifications' );
            $texts = array( "notification", "foo bar blah" );
            foreach ( $notifs as $key => $notif ) {
                $this->AssertEquals( $this->mUser->Id, $notif->Fromuserid, 'Wrong notif fromuserid' );
                $this->AssertEquals( $this->mUser2->Id, $notif->Touserid, 'Wrong notif touserid' );
                $this->AssertEquals( $texts[ $key ], $notif->Item->Text, 'Wrong notif item text' );
                $this->AssertEquals( $this->mUser2->Id, $notif->Item->Itemid, 'Wrong notif item itemid' );
            }
        }
        public function TestFindByUser() {
        }
        public function TestFindByUserAndComment() {
        }
        public function TearDown() {
            if ( is_object( $this->mUser ) ) {
                $this->mUser->Delete();
            }
            if ( is_object( $this->mUser2 ) ) {
                $this->mUser2->Delete();
            }
        }
    }

    return New TestNotifications();

?>
