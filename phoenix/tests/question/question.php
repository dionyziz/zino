<?php

    class TestQuestion extends Testcase {
        protected $mAppliesTo = 'libs/question/question';

        public function TestClassesExist() {
            $this->Assert( class_exists( 'Question' ), 'Class Question does not exist' );
            $this->Assert( class_exists( 'QuestionFinder' ), 'Class QuestionFinder does not exist' );
        }

		public function TestMethodsExist() {
            $finder = New QuestionFinder();
            $this->Assert( method_exists( $finder, 'Count' ), 'QuestionFinder::Count method does not exist' );
            $this->Assert( method_exists( $finder, 'FindAll' ), 'QuestionFinder::FindAll method does not exist' );
            $this->Assert( method_exists( $finder, 'FindRandomByUser' ), 'QuestionFinder::FindRandomByUser method does not exist' );
            $this->Assert( method_exists( $finder, 'FindNewQuestion' ), 'QuestionFinder::FindNewQuestion method does not exist' );
        }

        public function TestCreate() {
			// TODO
		}


        public function SetUp() {
			// TODO
        }
        public function TearDown() {
			// TODO
        }
    }

    return New TestQuestion();

?>