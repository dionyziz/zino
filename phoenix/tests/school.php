<?php

    class TestSchool extends Testcase {
        protected $mAppliesTo = 'libs/school';

        public function TestClassesExist() {
            $this->Assert( class_exists( 'SchoolFinder' ), 'class SchoolFinder does not exist' );
            $this->Assert( class_exists( 'School' ), 'class School does not exist' );
        }

        public function TestMethodsExist() {
            $this->Assert( method_exists( 'SchoolFinder', 'FindUsers' ), 'method SchoolFinder->FindUsers does not exist' );
            $this->Assert( method_exists( 'SchoolFinder', 'FindByUser' ), 'method SchoolFinder->FindByUser does not exist' );
        }
    }

    return New TestSchool();

?>
