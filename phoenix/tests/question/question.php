<?php

    class TestQuestion extends Testcase {
        protected $mAppliesTo = 'libs/question/question';

        public function TestClassesExist() {
            $this->Assert( class_exists( 'Question' ), 'Class Question does not exist' );
            $this->Assert( class_exists( 'QuestionFinder' ), 'Class QuestionFinder does not exist' );
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