<?php

    class TestSchool extends Testcase {
        protected $mAppliesTo = 'libs/school';

        public function TestClassesExist() {
            $this->Assert( class_exists( 'SchoolFinder' ) , 'class SchoolFinder does not exist' );
            $this->Assert( class_exists( 'School' ) , 'class School does not exist' );
        }

        public function TestMethodsExist() {
            $this->Assert( method_exists( 'School' , 'Create' ) , 'method School->Create does not exist' );
            $this->Assert( method_exists( 'School' , 'Approve' ) , 'method School->Approve does not exist' );
            $this->Assert( method_exists( 'School' , 'Rename' ) , 'method School->Rename does not exist' );
            $this->Assert( method_exists( 'School' , 'Replace' ) , 'method School->Replace does not exist' );
            $this->Assert( method_exists( 'School' , 'Delete' ) , 'method School->Delete does not exist' );
        }
    }

    return New TestSchool();

?>
