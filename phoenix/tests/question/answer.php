<?php

	class TestAnswer extends Testcase {
		protected $mAppliesTo = 'libs/question/answer';
		private $mUser;
		private $mQuestion;

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
			$answer = New Answer();
			$answer->Userid = $this->mUser->Id;
			$answer->Questionid = $this->mQuestion->Id;
			$answer->Text = "TestAnswer";
			$answer->Save();
			
			$answer1 = New Answer( $answer->Userid, $answer->Questionid );
			
			$this->Assert( $answer1->Exists(), 'Answer must exist after save' );
			$this->AssertEquals( $this->mUser->Id, $answer1->Userid, 'Answer userid did not retain its attribute values after saving' );
			$this->AssertEquals( $this->mQuestion->Id, $answer1->Questionid, 'Answer questionid did not retain its attribute values after saving' );
			$this->AssertEquals( 'TestAnswer', $answer1->Text, 'Answer text did not retain its attribute values after saving' );
			
			$answer->Delete();
		}

		public function TestEdit() {
			// Temp
			$answer = New Answer();
			$answer->Userid = $this->mUser->Id;
			$answer->Questionid = $this->mQuestion->Id;
			$answer->Text = "TestAnswer";
			$answer->Save();

			// Database object
			$answer1 = New Answer( $this->mUser->Id, $this->mQuestion->Id );
			$answer1->Userid = $this->mUser->Id;
			$answer1->Questionid = $this->mQuestion->Id;
			$answer1->Text = "TestAnswer1";
			$answer1->Save();

			// New database object
			$answer = New Answer( $this->mUser->Id, $this->mQuestion->Id  );

			$this->Assert( $answer->Exists(), 'Answer edited saved does not exist' );
			$this->AssertEquals( $this->mUser->Id, $answer->Userid, 'Wrong userid on edited answer' );
			$this->AssertEquals( $this->mQuestion->Id, $answer->Questionid, 'Wrong questionid on edited answer' );
			$this->AssertEquals( 'TestAnswer1', $answer->Text, 'Wrong text on edited answer' );

			$answer->Delete();
		}

		public function TestDelete() {
			// Temp 
			$answer = New Answer();
			$answer->Userid = $this->mUser->Id;
			$answer->Questionid = $this->mQuestion->Id;
			$answer->Text = "TestAnswerForTestDelete";
			$answer->Save();
			$answer->Delete();
			
			$this->AssertFalse( $answer->Exists(), 'Answer must be deleted on Delete method called' );	
		}
		
		
		public function TestFindAll() {
			
			// Find all answers in datatabase
			$finder = New AnswerFinder();
			$database_answers = $finder->FindAll();
			
			
			$a1 = New Answer();
			$a1->Userid = $this->mUser->Id;
			$a1->Questionid = $this->mQuestion->Id;
			$a1->Text = 'TestAnswerTestFindAll';
			$a1->Save();

			$a2 = New Answer();
			$a2->Userid = $this->mUser->Id;
			$a2->Questionid = $this->Question->Id;
			$a2->Text = 'TestAnswerTestFindAll2';
			$a2->Save();

			$finder = New AnswerFinder();
			$answers = $finder->FindAll();

			$num_texts = 0;
			foreach( $answers as $key => $a ) {
				if ( in_array( $a->Text, array( 'TestAnswerTestFindAll', 'TestAnswerTestFindAll2' ) ) ) {
					++$num_texts;
				}
			}
			
			$this->AssertEquals( count( $database_answers ) + 2, count( $answers ), 'Total Answers returned by AnswerFinder::FindAll not match' );
			$this->Assert( count( $answers ) >= $num_texts, 'Answers texts returned by AnswerFinder::FindAll not match' );

			$a1->Delete();
			$a2->Delete();
		}
		
		public function TestFindByUser() {
			$a1 = New Answer();
			$a1->Userid = $this->mUser->Id;
			$a1->Questionid = $this->mQuestion->Id;
			$a1->Text = 'TestAnswerTestFindAll';
			$a1->Save();

			$a2 = New Answer();
			$a2->Userid = $this->mUser->Id;
			$a2->Questionid = $this->Question->Id;
			$a2->Text = 'TestAnswerTestFindAll2';
			$a2->Save();
			
			$finder = New AnswerFinder();
			$answers = $finder->FindByUser( $this->mUser );
			
			$this->AssertEquals( 2, count( $answers ), 'Total Answers for this user returned by AnswerFinder::FindByUser not match' );
		
			$a1->Delete();
			$a2->Delete();
		}
		
		public function SetUp() {
			global $libs;
			$libs->Load( 'user/user' );
			$libs->Load( 'question/question' );

			$ufinder = New UserFinder();
			$user = $ufinder->FindByName( 'testanswers' );
			if ( is_object( $user ) ) {
				$user->Delete();
			}

			$this->mUser = New User();
			$this->mUser->Name = 'testanswers';
			$this->mUser->Subdomain = 'testanswers';
			$this->mUser->Save();
			
			$this->mQuestion = New Question();
			$this->mQuestion->Userid = $this->mUser->Id;
			$this->mQuestion->Delid = 0;
			$this->mQuestion->Save();
						
		}

		public function TearDown() {
			if ( is_object( $this->mUser ) ) {
				$this->mUser->Delete();
			}

			if ( is_object( $this->mUser2 ) ) {
				$this->mUser->Delete();
			}
			
			if ( is_object( $this->mQuestion ) ) {
				$this->mQuestion->Delete();
			}
		}
		
	}

	return New TestAnswer();

?>