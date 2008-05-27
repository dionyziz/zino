<?php

    define( 'TYPE_PAGE_LIMIT', 4 ); // this is used within comment lib

    global $libs;
    $libs->Load( 'comment' );

    global $water;
    // $water->Disable(); // out of memory D:
    
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
			global $libs;

			$libs->Load( 'journal' );

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
            if ( is_object( $user ) ) {
                $user->Delete();
            }

            $this->mUser = New User();
            $this->mUser->Name = 'testcomments';
            $this->mUser->Subdomain = 'testcomments';
            $this->mUser->Save();

            $this->mJournal = New Journal();
            $this->mJournal->Userid = $this->mUser->Id;
            $this->mJournal->Title = "The old walking song";
            $this->mJournal->Text = "The Road goes ever on and on
Down from the door where it began.
Now far ahead the Road has gone,
And I must follow, if I can,
Pursuing it with eager/weary feet,
Until it joins some larger way
Where many paths and errands meet.
And whither then? I cannot say.
                        
The Road goes ever on and on
Out from the door where it began.
Now far ahead the Road has gone,
Let others follw it who can!
Let them a journey new begin,
But I at last with weary feet
Will turn towards the lighted inn,
My evening-rest and sleep to meet.

Still round the corner there may wait
A new road or a secret gate;
And though I oft have passed them by,
A day will come at last when I
Shall take the hidden paths that run
West of the Moon, East of the Sun.";

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
        public function TestCreateComment() {
            $comment = New TestComment();
            $comment->Typeid = TYPE_JOURNAL;
            $comment->Itemid = $this->mJournal->Id;
            $comment->Userid = $this->mUser->Id;
            $comment->Text = "1ST P0ST!!!";
            $comment->Parentid = 0;
            $comment->Save();

            $c = New TestComment( $comment->Id ); // new instance
            $this->AssertEquals( TYPE_JOURNAL, $c->Typeid, 'Wrong typeid on new instance' );
            $this->AssertEquals( $this->mJournal->Id, $c->Itemid, 'Wrong itemid on new instance' );
            $this->AssertEquals( $this->mJournal->Text, $c->Item->Text, 'Wrong item on new instance' );
            $this->AssertEquals( $this->mUser->Id, $c->Userid, 'Wrong userid on new instance' );
            $this->AssertEquals( $this->mUser->Name, $c->User->Name, 'Wrong user on new instance' );
            $this->AssertEquals( "1ST P0ST!!!", $c->Text, 'Wrong text on new instance' );
            $this->AssertEquals( 0, $c->Parentid, 'Wrong parentid on new instance' );
            $this->AssertEquals( 1, $c->Id, 'Wrong id on first comment of table' );
        }
        private function MakeComment( $user, $text, $parentid ) {
            $comment = New TestComment();
            $comment->Itemid = $this->mJournal->Id;
            $comment->Typeid = TYPE_JOURNAL;
            $comment->Userid = $user->Id;
            $comment->Parentid = $parentid;
            $comment->Text = $text;
            $comment->Save();
        }
        private function MakeUser( $name ) {
            $ufinder = New UserFinder();
            $user = $ufinder->FindByName( $name );
            if ( is_object( $user ) ) {
                $user->Delete();
            }

            $user = New User();
            $user->Name = $name;
            $user->Subdomain = $name;
            $user->Save();

            return $user;
        }
        public function TestFindByPage() {
            $user1 = $this->MakeUser( 'test_green_troll' );
            $user2 = $this->MakeUser( 'test_pwnage' );
            $user3 = $this->MakeUser( 'test_repulis' );
            $user4 = $this->MakeUser( 'test_leimer' );
            $user5 = $this->MakeUser( 'test_fairytaler' );
            
            $this->MakeComment( $user1, "FIRST POST!!!11", 0 ); // 2
            $this->MakeComment( $user2, "LOL PWNED", 2 ); // 3
            $this->MakeComment( $user1, "FAGGOT", 3 ); // 4
            $this->MakeComment( $user3, "dat is TOLKIEN you BITCH", 0 ); // 5
            $this->MakeComment( $user4, "hahahahahahahahahahahah", 5 ); // 6
            $this->MakeComment( $user3, "J.R.R.R.R.Tolkien is a loooser and his ma bitch", 0 ); // 7
            $this->MakeComment( $user4, "For better or for worse and I don't care which??!??!", 7 ); // 8
            $this->MakeComment( $user2, "ROFLMAO testcomments YOU GOT PWNED", 5 ); // 9
            $this->MakeComment( $user1, "LORDI hard rock hallelujah?", 8 ); // 10
            $this->MakeComment( $user3, "I fack his vry whole and i dont care witch!1!!1 x0ax0ax0ax0a", 8 ); // 11
            $this->MakeComment( $user5, "Nice little poem", 0 ); // 12
            $this->MakeComment( $user2, "LOLWOA?", 12 ); // 13

            $finder = New TestCommentFinder();
            $comments = $finder->FindByPage( $this->mJournal, 1 );
            
            $this->Assert( is_array( $comments ), 'CommentFinder::FindByPage did not return an array' );
            $this->AssertEquals( 4, count( $comments ), 'CommentFinder::FindByPage did not return the right number of comments' );
            
            print_r( $comments );

            $user1->Delete();
            $user2->Delete();
            $user3->Delete();
            $user4->Delete();
            $user5->Delete();
        }
        public function TearDown() {
            /* if ( is_object( $this->mTable ) ) {
                $this->mTable->Delete();
            } */
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
