<?php

    global $libs;
    $libs->Load( 'comment' );

    class TestComment extends Comment {
        protected $mDbTableAlias = 'testcomments';
    }

    class CommentTest extends Testcase {
        protected $mAppliesTo = 'libs/comment';
        private $mTable;
        private $mUser;

        public function SetUp() {
            global $rabbit_settings;
            global $water;

            $databasealiases = array_keys( $rabbit_settings[ 'databases' ] );
            w_assert( isset( $GLOBALS[ $databasealiases[ 0 ] ] ) );
            $db = $GLOBALS[ $databasealiases[ 0 ] ];

            $existing_tables = $db->Tables();
            $water->Trace( 'existing tables', $existing_tables );
            if ( isset( $existing_tables[ 'testcomments' ] ) ) {
                $table = New DbTable( $db, 'testcomments', 'testcomments' );
                $table->Delete();
            }
            $table = New DbTable( $db, 'comments', 'comments' );
            $table->Copy( 'testcomments', 'testcomments' );

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
        }
        public function TestClassesExist() {
        }
        public function TestMethodsExist() {
        }
        public function TestFunctionsExist() {
        }
        public function TestCreateComment() {
            $comment = New TestComment();
            $comment->Typeid = 0;
            $comment->Itemid = 1;
            $comment->Userid = $this->mUser->Id; 
            $comment->Save();    
        }
        public function TearDown() {
            if ( is_object( $this->mTable ) ) {
                $this->mTable->Delete();
            }
            if ( is_object( $this->mUser ) ) {
                $this->mUser->Delete();
            }
        }
    }

    return New CommentTest();

?>
