<?php

    define( 'COMMENT_PAGE_LIMIT', 5 ); // this is used within comment lib

    global $libs;
    $libs->Load( 'comment' );
    
    class TestComment extends Comment {
        protected $mDbTableAlias = 'testcomments';
    }

    class TestCommentFinder extends CommentFinder {
        protected $mModel = 'TestComment';
    }
    class CommentTest extends Testcase {
        protected $mAppliesTo = 'libs/comment';
        private $mTable;
        private $mUser;
        private $mJournal;

        public function SetUp() {
            global $rabbit_settings;
            global $water;

            $databasealiases = array_keys( $rabbit_settings[ 'databases' ] );
            w_assert( isset( $GLOBALS[ $databasealiases[ 0 ] ] ) );
            $db = $GLOBALS[ $databasealiases[ 0 ] ];

            $table = New DbTable( $db, 'comments', 'comments' );
            try {
                $table->Copy( 'testcomments', 'testcomments' );
            } catch ( Exception $e ) { // ooops already found testcomments
                $oldtable = New DbTable( $db, 'testcomments', 'testcomments' );
                $oldtable->Delete();

                $table->Copy( 'testcomments', 'testcomments' );
            }

            $this->mTable = New DbTable( $db, 'testcomments', 'testcomments' );

            $ufinder = New UserFinder();
            $user = $ufinder->FindByName( 'testcomments' );
            if ( $user !== false ) {
                $user->Delete();
            }

            $this->mUser = New User();
            $this->mUser->Name = 'testcomments';
            $this->mUser->Subdomain = 'testcomments';
            $this->mUser->Save();

            $this->mJournal = New Journal();
            $this->mJournal->Userid = $this->mUser->Id;
            $this->mJournal->Title = "test";
            $this->mJournal->Text = "foo bar";
            $this->mJournal->Save();
        }
        public function TestClassesExist() {
            $this->Assert( class_exists( 'Comment' ), 'Comment class does not exist' );
            $this->Assert( class_exists( 'CommentFinder' ), 'CommentFinder class does not exist' );
        }
        public function TestMethodsExist() {
            $comment = New Comment();
            $this->Assert( method_exists( $comment, 'Save' ), 'Comment::Save method does not exist' );

            $finder = New CommentFinder();
            $this->Assert( method_exists( $finder, 'FindNear' ), 'CommentFinder::FindNear method does not exist' );
            $this->Assert( method_exists( $finder, 'FindByPage' ), 'CommentFinder::FindByPage method does not exist' );
        }
        public function TestFunctionsExist() {
            $this->Assert( function_exists( 'Comments_TypeFromEntity' ), 'Comments_TypeFromEntity class does not exist' );
        }
        public function TestCreateComment() {
            $comment = New TestComment();
            $comment->Typeid = COMMENT_JOURNAL;
            $comment->Itemid = $this->mJournal->Id;
            $comment->Userid = $this->mUser->Id;
            $comment->Text = "haloooo";
            $comment->Save();

            $c = New TestComment( $comment->Id ); // new instance
            $this->AssertEquals( COMMENT_JOURNAL, $c->Typeid, 'Wrong typeid on new instance' );
            $this->AssertEquals( $this->mJournal->Id, $c->Itemid, 'Wrong itemid on new instance' );
            $this->AssertEquals( $this->mJournal->Text, $c->Item->Text, 'Wrong item on new instance' );
            $this->AssertEquals( $this->mUser->Id, $c->Userid, 'Wrong userid on new instance' );
            $this->AssertEquals( $this->mUser->Name, $c->User->Name, 'Wrong user on new instance' );
            $this->AssertEquals( "haloooo", $c->Text, 'Wrong text on new instance' );
        }
        public function TearDown() {
            if ( is_object( $this->mTable ) ) {
                $this->mTable->Delete();
            }
            if ( is_object( $this->mUser ) ) {
                $this->mUser->Delete();
            }
            if ( is_object( $this->mJournal ) ) {
                $this->mJournal->Delete();
            }
        }
    }

    return New CommentTest();

?>
