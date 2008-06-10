<?php

	class QuestionFinder extends Finder {
		protected $mModel = 'Question';
				
		public function Count() {
			$query = $this->mDb->Prepare(
			'SELECT
				COUNT(*) AS questionscount
			FROM
				:questions
			WHERE questions_delid = :delid;
			');
			$query->BindTable( 'questions' );
			$query->Bind( 'delid', 0 );
			$res = $query->Execute();
			$row = $res->FetchArray();
			return ( int )$row[ 'questionscount' ];
		}
		
		public function FindAll( $offset = 0, $limit = 10000 ) {
            $prototype = New Question();
            return $this->FindByPrototype( $prototype, $offset, $limit );
        }
		
		public function FindRandomByUser( $user ) {
			// This query is awesome, by dionyziz
			$query = $this->mDb->Prepare('
			SELECT 
				* 
			FROM 
				:questions
			LEFT JOIN :answers 
				ON ( question_id = answer_questionid AND answer_userid = :userid )
			WHERE
				answer_id = NULL
			ORDER BY RAND()
			LIMIT :limit;
			');
			$query->BindTable( 'questions' );
			$query->BindTable( 'answers' );
			$query->Bind( ':userid', $user->Id );
			$query->Bind( ':limit', 1 );
			return $this->FindBySqlResource( $query->Execute() );
		}
	}

	class Question extends Satori {
		protected $mDbTableAlias = 'questions';

		public function GetText() {
			return $this->Bulk->Text;
		}
		
		public function SetText( $text ) {
			$this->Bulk->Text = $text;
		}
		
		public function IsDeleted() {
            return $this->Delid > 0;
        }
		
		public function Relations() {
			$this->User = $this->HasOne( 'User', 'Userid' );
			$this->Bulk = $this->HasOne( 'Bulk', 'Bulkdid' );
		}
		
		public function OnBeforeDelete() {
			$this->Delid = 1;
            $this->Save();
			return false; // Avoid database row delete
		}
		
		public function OnBeforeCreate() {
            $this->Bulk->Save();
            $this->Bulkid = $this->Bulk->Id;
		}
						
		public function OnUpdate() {
			$this->Bulk->Save();
		}
						
		public function LoadDefaults() {
			global $user;

			$this->Userid = $user->Id;
			$this->Created = NowDate();
		}		
	}
	
?>
