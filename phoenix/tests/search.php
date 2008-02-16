<?php

    class TestModel extends Satori {
        protected $mDbTableAlias = 'search_test';

        public function LoadDefaults() {
            $this->Num = 10;
            $this->Text = "foobar";
        }
    }

    class TestController extends Search {
        protected $mModel = 'TestModel';

        public function GetMax( $n = 5 ) {
            $this->SortBy = 'Num';
            $this->Order = 'DESC';
            $this->Limit = $n;

            return $this->Get();
        }
        public function SearchText( $text ) {
            $this->Text = $text;

            return $this->Get();
        }
    }

    class TestSearch extends Testcase {
        protected $mAppliesTo = 'libs/search';
        private $mDbTable;
        private $mDb;
        private $mObjectsCount;
        
        public function SetUp() { 
            global $rabbit_settings;
            
            w_assert( is_array( $rabbit_settings[ 'databases' ] ) );
            w_assert( count( $rabbit_settings[ 'databases' ] ) );
            $databasealiases = array_keys( $rabbit_settings[ 'databases' ] );
            w_assert( isset( $GLOBALS[ $databasealiases[ 0 ] ] ) );
            $this->mDb = $GLOBALS[ $databasealiases[ 0 ] ];
            w_assert( $this->mDb instanceof Database );
            
            // make sure we don't overwrite something
            w_assert( $this->mDb->TableByAlias( 'search_test' ) === false );

            $this->mDbTable = New DBTable();
            $this->mDbTable->Name = 'search_test';
            $this->mDbTable->Alias = 'search_test';
            $this->mDbTable->Database = $this->mDb;
            
            $field = New DBField();
            $field->Name = 'test_id';
            $field->Type = DB_TYPE_INT;
            $field->IsAutoIncrement = true;
            
            $field1 = New DBField();
            $field1->Name = 'test_text';
            $field1->Type = DB_TYPE_CHAR;
            $field1->Length = 16;
            
            $field2 = New DBField();
            $field2->Name = 'test_num';
            $field2->Type = DB_TYPE_INT;
            
            $this->mDbTable->CreateField( $field, $field1, $field2 );
            
            $primary = New DBIndex();
            $primary->Type = DB_KEY_PRIMARY;
            $primary->AddField( $field );
            
            $this->mDbTable->CreateIndex( $primary );
            
            $this->mDbTable->Save();
            
            $this->mDb->AttachTable( 'search_test', 'search_test' );

            $this->mObjectsCount = 0;

            $obj = new TestModel();
            // num 10
            // text foobar
            $obj->Save();
            ++$this->mObjectsCount;

            $obj = new TestModel();
            $obj->Text = 'blah';
            $obj->Num = 14;
            $obj->Save();
            ++$this->mObjectsCount;

            $obj = new TestModel();
            $obj->Text = '';
            $obj->Num = 0;
            $obj->Save();
            ++$this->mObjectsCount;

            $obj = new TestModel();
            $obj->Text = 'lorem';
            $obj->Num = -15;
            $obj->Save();
            ++$this->mObjectsCount;

            $obj = new TestModel();
            $obj->Text = 'ipsum';
            $obj->Num = -50;
            $obj->Save();
            ++$this->mObjectsCount;

            $obj = new TestModel();
            $obj->Num = 4;
            $obj->Save();
            ++$this->mObjectsCount;
        }
        public function TestClassesExist() {
            $this->Assert( class_exists( 'Search' ), 'Search class does not exist' );
        }
        public function TestFetchAll() {
            $search = New TestController();
            $objects = $search->Get();

            $this->Assert( is_array( $objects ), 'Get did not return an array' );
            $this->Assert( count( $objects ) == $this->mObjectsCount, 'Get did not return the right number of objects' );
        }
        public function TearDown() {
            $this->mDbTable->Delete();
            $this->mDb->DetachTable( 'rabbit_satori_test' );
        }
    }

?>
