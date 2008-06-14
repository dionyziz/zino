<?php

	class AnswerFinder extends Finder {
		protected $mModel = 'Answer';
				
		public function Count() {
			$query = $this->mDb->Prepare(
			'SELECT
				COUNT(*) AS answerscount
			FROM
				:answers
			');
			$query->BindTable( 'answers' );
			$res = $query->Execute();
			$row = $res->FetchArray();
			return ( int )$row[ 'answerscount' ];
		}
		
		public function FindAll( $offset = 0, $limit = 10000 ) {
            $prototype = New Answer();
            return $this->FindByPrototype( $prototype, $offset, $limit );
        }
		
		public function FindByUser( User $user, $offset = 0, $limit = 10000 ) {
			$answer = New Answer();
			$answer->Userid = $user->Id;
			return $this->FindByPrototype( $answer, $offset, $limit, array( 'Id', 'DESC' ) );
		}
	}

	class Answer extends Satori {
		protected $mDbTableAlias = 'answers';

		public function GetText() {
			return $this->Bulk->Text;
		}
		
		public function SetText( $text ) {
			$this->Bulk->Text = $text;
		}
				
		public function Relations() {
			$this->User = $this->HasOne( 'User', 'Userid' );
			$this->Bulk = $this->HasOne( 'Bulk', 'Bulkid' );
			$this->Question = $this->HasOne( 'Question', 'Questionid' );
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
