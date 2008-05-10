<?php

    global $libs;
    $libs->Load( 'comment' );

    class TestComment extends Comment {
        protected $mDbTableAlias = 'testcomments';
    }

    class CommentTest extends Testcase {
        protected $mAppliesTo = 'libs/comment';

        public function SetUp() {
            $databasealiases = array_keys( $rabbit_settings[ 'databases' ] );
            w_assert( isset( $GLOBALS[ $databasealiases[ 0 ] ] ) );
            $db = $GLOBALS[ $databasealiases[ 0 ] ];

            $table = New DbTable( $db, 'comments', 'comments' );
            $table->Copy( 'testcomments' );

            /*
            $databasealiases = array_keys( $rabbit_settings[ 'databases' ] );
            w_assert( isset( $GLOBALS[ $databasealiases[ 0 ] ] ) );
            $this->mDb = $GLOBALS[ $databasealiases[ 0 ] ];

            $table = New DbTable();
            $table->Name = 'testcomments';
            $table->Alias = 'testcomments';
            $table->Database = $this->mDb;

            $field = New DbField();
            $field->Name = 'comment_id';
            $field->Type = DB_TYPE_INT;
            $field->IsAutoIncrement = true;

            $field2 = New DbField();
            $field2->Name = 'comment_userid';
            $field2->Type = DB_TYPE_INT;

            $field3 = New DbField();
            $field3->Name = 'comment_created';
            $field3->Type = 
            
            $field->Name = 'test_id';
            $field->Type = DB_TYPE_INT;
            $field->IsAutoIncrement = true;
            
            $field2 = New DBField();
            $field2->Name = 'test_char';
            $field2->Type = DB_TYPE_CHAR;
            $field2->Length = 4;
            */
        }
        public function ClassesExist() {
        }
        public function MethodsExist() {
        }
        public function FunctionsExist() {
        }
        public function CreateComment() {
        }
        public function TearDown() {
        }
    }

    return New CommentTest();

?>
