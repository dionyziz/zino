<?php

    class TestQuestion extends Testcase {
        protected $mAppliesTo = 'libs/question/question';
        private $mUser;
        private $mUser2;
        
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
            $question = New Question();
            $question->Userid = $this->mUser->Id;
            $question->Text = "How?";
            $question->Delid = 0;
            $question->Save();
            
            $question1 = New Question( $question->Id );
            
            $this->Assert( $question1->Exists(), 'Question must exist after save' );
            $this->AssertFalse( $question1->IsDeleted(), 'Question must not be deleted if delid equals 0' );    
            $this->AssertEquals( $this->mUser->Id, $question1->Userid, 'Question userid did not retain its attribute values after saving' );
            $this->AssertEquals( 'How?', $question1->Text, 'Question text did not retain its attribute values after saving' );
            $this->AssertEquals( 0, $question1->Delid, 'Question delid did not retain its attribute values after saving' );
            
            $question->Delete();
		}
        
        
        public function TestEdit() {
            // Temp 
            $question = New Question();
            $question->Userid = $this->mUser->Id;
            $question->Text = "How?";
            $question->Delid = 0;
            $question->Save();
            
            // Database object
            $question1 = New Question( $question->Id );
            $question1->Userid = $this->mUser2->Id;
            $question1->Text = "Why?";
            $question1->Delid = 1;
            $question1->Save();
            
            // New database object
            $question = New Question( $question1->Id );
            
            $this->Assert( $question1->Exists(), 'Question saved does not exist' );
            $this->AssertEquals( $this->mUser2->Id, $question->Userid, 'Wrong userid on edited question' );
            $this->AssertEquals( 'Why?', $question->Text, 'Wrong text on edited question' );
            $this->AssertEquals( 1, $question1->Delid, 'Wrong delid on edited question' );

            $question->Delete();
        }
        
        public function TestDelete() {
            // Temp 
            $question = New Question();
            $question->Userid = $this->mUser->Id;
            $question->Text = "How?";
            $question->Delid = 0;
            $question->Save();
            
            $question->Delete();
            $this->Assert( $question->IsDeleted(), 'Question must be deleted if delid equals 1' );    
            $this->AssertEquals( 1, $question->Delid, 'Question delid did not retain its attribute values after saving' );
        }
        
        public function TestFindAll() {
            $q1 = New Question();
            $q1->Userid = $this->mUser->Id;
            $q1->Text = 'When?';
            $q1->Save();

            $q2 = New Question();
            $q2->Userid = $this->mUser2->Id;
            $q2->Text = 'How?';
            $q2->Save();

            $finder = New QuestionFinder();
            $questions = $finder->FindAll();
            $this->AssertEquals( 2, count( $questions ), 'Returned questions from QuestionFinder::FindAll not match' );

            $num_texts = 0;
            foreach( $questions as $key => $q ) {
                if ( array_search( $q->Text, array( 'When?', 'How?' ) ) == true ) {
                    ++$num_texts;
                }
            }
            $this->AssertEquals( 2, $num_texts, 'Questions returned texts from finder not match' );

            $q1->Delete();
            $q2->Delete();
        }
        
        public function TestFindNewQuestion() {
            
        }


        public function SetUp() {
            global $libs;
            $libs->Load( 'user' );
            
            $ufinder = New UserFinder();
            $user = $ufinder->FindByName( 'testquestions' );
            if ( is_object( $user ) ) {
                $user->Delete();
            }

            $this->mUser = New User();
            $this->mUser->Name = 'testquestions';
            $this->mUser->Subdomain = 'testquestions';
            $this->mUser->Save();            
            
            $ufinder = New UserFinder();
            $user = $ufinder->FindByName( 'testquestions2' );
            if ( is_object( $user ) ) {
                $user->Delete();
            }
            
            $this->mUser2 = New User();
            $this->mUser2->Name = 'testquestions2';
            $this->mUser2->Subdomain = 'testquestions2';
            $this->mUser2->Save();            
            
            
        }
        
        public function TearDown() {
            if ( is_object( $this->mUser ) ) {
                $this->mUser->Delete();
            }
        }
    }

    return New TestQuestion();
?>