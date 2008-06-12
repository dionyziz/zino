<?php

    class TestAnswer extends Testcase {
        protected $mAppliesTo = 'libs/question/answer';

        public function TestClassesExist() {
            $this->Assert( class_exists( 'Answer' ), 'Class Answer does not exist' );
            $this->Assert( class_exists( 'AnswerFinder' ), 'Class AnswerFinder does not exist' );
        }

		public function TestMethodsExist() {
            $finder = New AnswerFinder();
            $this->Assert( method_exists( $finder, 'Count' ), 'AnswerFinder::Count method does not exist' );
            $this->Assert( method_exists( $finder, 'FindAll' ), 'AnswerFinder::FindAll method does not exist' );
            $this->Assert( method_exists( $finder, 'FindByUser' ), 'AnswerFinder::FindByUser method does not exist' );
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

    return New TestAnswer();

?>