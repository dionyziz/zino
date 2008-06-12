<?php

    class TestAnswer extends Testcase {
        protected $mAppliesTo = 'libs/question/answer';

        public function TestClassesExist() {
            $this->Assert( class_exists( 'Answer' ), 'Class Answer does not exist' );
            $this->Assert( class_exists( 'AnswerFinder' ), 'Class AnswerFinder does not exist' );
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