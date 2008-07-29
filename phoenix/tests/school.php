<?php

    class TestSchool extends Testcase {
        protected $mAppliesTo = 'libs/school';
        protected $School;

        public function TestClassesExist() {
            $this->Assert( class_exists( 'SchoolFinder' ), 'class SchoolFinder does not exist' );
            $this->Assert( class_exists( 'School' ), 'class School does not exist' );
        }

        public function TestMethodsExist() {
            $this->Assert( method_exists( 'SchoolFinder', 'Find' ), 'method SchoolFinder->Find does not exist' );
        }

        public function TestCreate() {
            $this->School = New School();
            $this->School->Name = '9th Extraordinarily Inappropriate School';
            $this->School->Typeid = 2;
            $this->School->Placeid = 13;
            $this->School->Save();
            // TODO
        }

        public function TestApprove() {
            $this->School->Approved = 1;
            $this->School->Save();
            // TODO
        }

        public function TestReject() {
            $this->School->Approved = 0;
            $this->School->Save();
            // TODO
        }

        public function TestEdit() {
            $this->School->Name = '9th Infinitely and Extraordinarily Inappropriate School';
            $this->School->Placeid = 4;
            $this->School->Save();
            // TODO
        }

        public function TestDelete() {
            $this->School->Delete();
            // TODO
        }
    }

    return New TestSchool();

?>
