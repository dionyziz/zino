<?php

    class TestSchool extends Testcase {
        protected $mAppliesTo = 'libs/school';
        protected $mFinder;
        protected $mSchool;

        public function TestClassesExist() {
            $this->Assert( class_exists( 'SchoolException' ), 'class SchoolException does not exist' );
            $this->Assert( class_exists( 'SchoolFinder' ), 'class SchoolFinder does not exist' );
            $this->Assert( class_exists( 'School' ), 'class School does not exist' );
        }

        public function TestMethodsExist() {
            $this->Assert( method_exists( 'SchoolFinder', 'Find' ), 'method SchoolFinder->Find does not exist' );
            $this->Assert( method_exists( 'SchoolFinder', 'Count' ), 'method SchoolFinder->Count does not exist' );
        }

        public function TestFind() {
            $this->mFinder = New SchoolFinder();
            $schools = $this->mFinder->Find();
            $this->Assert( is_array( $schools ) );
            foreach ( $schools as $school ) {
                $this->Assert( $school instanceof School );
            }
        }

        public function TestCount() {
            $count = $this->mFinder->Count();
            $this->Assert( is_int( $count ) );
            $this->Assert( $count >= 0 );
        }

        public function TestCreate() {
            $this->mSchool = New School();
            $this->mSchool->Name = '9th Extraordinarily Inappropriate School';
            $this->mSchool->Typeid = 2;
            $this->mSchool->Placeid = 13;
            $this->mSchool->Save();
            $id = $this->mSchool->Id;
            $count = $this->mFinder->Find( $schoolid = $id );
            $this->AssertEquals( 1, $count );
        }

        public function TestApprove() {
            $this->AssertEquals( 0, $this->mSchool->Approved ); 
            $this->mSchool->Approved = 1;
            $this->mSchool->Save();
            $this->AssertEquals( 1, $this->mSchool->Approved );
            $this->mSchool->Approved = 0;
            $this->mSchool->Save();
            $this->AssertEquals( 0, $this->mSchool->Approved );
        }

        public function TestEdit() {
            $this->mSchool->Name = '9th Infinitely and Extraordinarily Inappropriate School';
            $this->mSchool->Placeid = 4;
            $this->mSchool->Save();
            // TODO
        }

        public function TestDelete() {
            $this->mSchool->Delete();
            // TODO
        }
    }

    return New TestSchool();

?>
