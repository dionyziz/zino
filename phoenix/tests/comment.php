<?php

    class TestComment extends Testcase {
        protected $mAppliesTo = 'libs/comment';
        private $mUser;
        private $mJournal;
        private $mUser2;

        public function SetUp() {
            global $libs;

            $libs->Load( 'comment' );
            $libs->Load( 'journal' );

            $ufinder = New UserFinder();
            $user = $ufinder->FindByName( 'testcomments' );

            if ( is_object( $user ) ) {
                $user->Delete();
            }

            $user = New User();
            $user->Name = 'testcomments';
            $user->Subdomain = 'testcomments';
            $user->Profile->Email = 'bitbucket@kamibu.com';
            $user->Save();

            $this->mUser = $user;

            $journal = New Journal();
            $journal->Title = 'Comments testcase';
            $journal->Text = 'Testing comments....';
            $journal->Userid = $this->mUser->Id;
            $journal->Save();

            $this->mJournal = $journal;

            $user = $ufinder->FindByName( 'testcomments2' );
            if ( is_object( $user ) ) {
                $user->Delete();
            }

            $this->mUser2 = New User();
            $this->mUser2->Name = 'testcomments2';
            $this->mUser2->Subdomain = 'testcomments2';
            $this->mUser2->Profile->Email = 'bitbucket@kamibu.com';
            $this->mUser2->Save();
        }
        public function TestCreation() {
            $comment = New Comment();
            $comment->Userid = $this->mUser->Id;
            $comment->Typeid = TYPE_JOURNAL;
            $comment->Itemid = $this->mJournal->Id;
            $comment->Text = '<h2>Lorem</h2> ipsum dolor sit amet';
            $this->AssertEquals( '<h2>Lorem</h2> ipsum dolor sit amet', $comment->Text, 'Wrong comment text' );
            $comment->Parentid = 0;
            $comment->Save();

            $this->Assert( $comment->Exists(), 'Comment does not seem to exist' );

            $comment2 = New Comment( $comment->Id );
            $this->AssertEquals( '<h2>Lorem</h2> ipsum dolor sit amet', $comment2->Text, 'Wrong comment text on new instance' );
            $this->AssertEquals( 'Lorem', $comment2->GetText( 5 ), 'Wrong comment text substring on new instance' );
            $comment2->Delete();
        }
        public function TestEdit() {
            $comment = New Comment();
            $comment->Userid = $this->mUser->Id;
            $comment->Typeid = TYPE_JOURNAL;
            $comment->Itemid = $this->mJournal->Id;
            $comment->Text = "foo bar blah";
            $comment->Parentid = 0;
            $comment->Save();

            $c = New Comment( $comment->Id );
            $this->Assert( $c->Exists(), 'Saved comment does not exist' );
            $c->Text = "foo bar";
            $c->Parentid = 2;
            $c->Save();

            $comment = New Comment( $c->Id );
            $this->Assert( $comment->Exists(), 'Edited comment does not exist' );
            $this->AssertEquals( 'foo bar', $comment->Text, 'Wrong text' );
            $this->AssertEquals( 2, $comment->Parentid, 'Wrong parentid' );

            $comment->Delete();
        }
        public function TestFindLatest() {
            $comment = New Comment();
            $comment->Userid = $this->mUser->Id;
            $comment->Typeid = TYPE_JOURNAL;
            $comment->Itemid = $this->mJournal->Id;
            $comment->Text = 'first';
            $comment->Parentid = 0;
            $comment->Save();

            $comment2 = New Comment();
            $comment2->Userid = $this->mUser->Id;
            $comment2->Typeid = TYPE_JOURNAL;
            $comment2->Itemid = $this->mJournal->Id;
            $comment2->Text = 'lol';
            $comment2->Parentid = 0;
            $comment2->Save();

            $comment3 = New Comment();
            $comment3->Userid = $this->mUser2->Id;
            $comment3->Typeid = TYPE_JOURNAL;
            $comment3->Itemid = $this->mJournal->Id;
            $comment3->Parentid = $comment2->Id;
            $comment3->Text = 'xaxaxa';
            $comment3->Save();

            $finder = New CommentFinder();
            $latest = $finder->FindLatest( 0, 3 );

            $userids = array( $this->mUser2->Id, $this->mUser->Id, $this->mUser->Id );
            $typeid = TYPE_JOURNAL;
            $itemid = $this->mJournal->Id;
            $texts = array( 'xaxaxa', 'lol', 'first' );

            $this->AssertEquals( 3, count( $latest ), 'wrong number of latest comments' );
            foreach ( $latest as $i => $comment ) {
                $this->AssertEquals( $userids[ $i ], $comment->Userid, "Wrong userid No $i" );
                $this->AssertEquals( $typeid, $comment->Typeid, "Wrong typeid No $i" );
                $this->AssertEquals( $itemid, $comment->Itemid, "Wrong itemid No $i" );
                $this->AssertEquals( $texts[ $i ], $comment->Text, "Wrong text No $i" );
            }

            $comment->Delete();
            $comment2->Delete();
            $comment3->Delete();
        }
    }

    return New TestComment();

?>
