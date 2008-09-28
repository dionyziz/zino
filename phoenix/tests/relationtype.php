<?php

    class TestRelationType extends Testcase {
        protected $mAppliesTo = 'libs/relation/type';
        private $mUser;

        public function SetUp() {
            global $libs;
            $libs->Load( 'relation/relation' );

            $ufinder = New UserFinder();
            $user = $ufinder->FindByName( 'testrelationtypes' );
            if ( is_object( $user ) ) {
                $user->Delete();
            }

            $this->mUser = New User();
            $this->mUser->Name = 'testrelationtypes';
            $this->mUser->Subdomain = 'testrelationtypes';
            $this->mUser->Rights = PERMISSION_RELATIONTYPE_CREATE;
            $this->mUser->Save();

            $finder = New RelationTypeFinder();
            
            $type = $finder->FindByText( 'foo' );
            if ( is_object( $type ) ) {
                $type->Delete();
            }

            $type = $finder->FindByText( 'bar' );
            if ( is_object( $type ) ) {
                $type->Delete();
            }

            $type = $finder->FindByText( 'blah' );
            if ( is_object( $type ) ) {
                $type->Delete();
            }
        }
        public function TestClassesExist() {
            $this->Assert( class_exists( 'RelationType' ), 'RelationType class does not exist' );
            $this->Assert( class_exists( 'RelationTypeFinder' ), 'RelationTypeFinder class does not exist' );
        }
        public function TestFindersExist() {
            $finder = New RelationTypeFinder();
            $this->Assert( method_exists( $finder, 'FindAll' ), 'FindAll finder does not exist' );
            $this->Assert( method_exists( $finder, 'FindByText' ), 'FindByText finder does not exist' );
        }
        public function TestCreate() {
            $type = New RelationType();
            $type->Userid = $this->mUser->Id;
            $type->Text = 'foo';
            $type->Save();

            $t = New RelationType( $type->Id );
            $this->AssertEquals( $this->mUser->Id, $type->Userid, 'Wrong userid' );
            $this->AssertEquals( 'foo', $type->Text, 'Wrong text' );

            $t->Delete();
        }
        public function TestFindAll() {
            $type = New RelationType();
            $type->Userid = $this->mUser->Id;
            $type->Text = 'foo';
            $type->Save();

            $type1 = New RelationType();
            $type1->Userid = $this->mUser->Id;
            $type1->Text = 'bar';
            $type1->Save();
            
            $type2 = New RelationType();
            $type2->Userid = $this->mUser->Id;
            $type2->Text = 'blah';
            $type2->Save();

            $finder = New RelationTypeFinder();
            $types = $finder->FindAll();

            $texts = array( 'foo', 'bar', 'blah' );
            $textsfound = 0;
            $user_timesfound = 0;
            foreach ( $types as $key => $t ) {
                if ( ( $i = array_search( $t->Text, $texts ) ) !== false ) {
                    unset( $texts[ $i ] );
                    ++$textsfound;
                }
                if ( $this->mUser->Id == $t->Userid ) {
                    ++$user_timesfound;
                }
            }

            $this->AssertEquals( 3, $textsfound, 'Did not find all created relations' );
            $this->AssertEquals( 3, $user_timesfound, 'Did not find the user as many times as needed' );

            $type->Delete();
            $type1->Delete();
            $type2->Delete();
        }
        public function TearDown() {
            if ( is_object( $this->mUser ) ) {
                $this->mUser->Delete();
            }
        }
    }

    return New TestRelationType();

?>
