<?php

	function Question_FormatMulti( &$questions ) {
        if ( !is_array( $questions ) ) {
            return false;
        }

		$answers = array();
		foreach ( $questions as $question ) {
			$answers[ $question->Id() ] = $question->Answer();
		}
			
		$formatted = mformatanswers( $answers );
		
		foreach ( $questions as $question ) {
			$question->SetAnswerFormatted( $formatted[ $question->Id() ] );
		}
		
		return true;
	}
	
	function AddQuestion( $question ) {
		global $questions;
		global $user;
		global $db;
		
		$question = myescape( $question );
		$nowdate = NowDate();
		$ip = UserIp();
		$userId = $user->Id();
		$sql = "INSERT INTO 
					`$questions`
				( `profileq_id` , `profileq_userid` , `profileq_created` , `profileq_question` , `profileq_userip` , `profileq_delid` ) VALUES
				( ''   , '$userId' , '$nowdate' , '$question' , '$ip' , '0'  ); ";
		$db->Query( $sql );
		
		return mysql_insert_id();
	}
	
	function UpdateQuestion( $eid , $question ) {
		global $questions;
		global $user;
		global $db;
		
		$question = myescape( $question );
		$eid = myescape( $eid );
		$nowdate = NowDate();
		$ip = UserIp();
		$sql = "UPDATE 
					`$questions`
					
				SET
					`profileq_userid`='" . $user->Id() . "',
					`profileq_question`='$question',
					`profileq_created`='$nowdate',
					`profileq_userip`='$ip'
					
				WHERE
					`profileq_id`='$eid' AND
					`profileq_delid`='0'
					
				LIMIT 1;";
		$db->Query( $sql );
	}
	
	function AllQuestions() {
		global $questions;
		global $db;
		
		$sql = "SELECT
					`profileq_id`,`profileq_question`
				FROM 
					`$questions`
				WHERE
					`profileq_delid`='0'
				;";
				
		$res = $db->Query( $sql );
		
		$ret = array();
		while( $row = $res->FetchArray() ) {
			$ret[] = New Question( $row );
		}
		return $ret;
	}
	
	function getAllQuestionInfo( $id ) {
		global $questions;
		global $db;
		
		$eid = myescape( $id );
		
		$sql = "SELECT 
						*
						
					FROM
						`$questions`
						
					WHERE
						`profileq_id`='$eid' AND
						`profileq_delid`='0'
					
					LIMIT
						1;";
					
		$res = $db->Query( $sql );
		$ret = $res->FetchArray();
		
		return $ret;
	}
	
	class Question {
		private $mId;
		private $mQuestion;
		private $mAdminId;
		private $mUpdateDate;
		private $mPermissions;
		private $mAnswer;
		private $mAnswerFormatted;
		
		public function Id() {
			return $this->mId;
		}
		public function Answer() {
			return $this->mAnswer;
		}
		public function AnswerFormatted() {
			if ( $this->mAnswerFormatted === false ) {
				$formatted = mformatanswers( array( $this->Answer() ) );
				$this->mAnswerFormatted = $formatted[ 0 ];
			}
			return $this->mAnswerFormatted;
		}
		public function SetAnswerFormatted( $text ) {
			$this->mAnswerFormatted = $text;
		}
		public function Kill() {
			global $db;
			global $questions;
			global $profileanswers;
			global $user;
			
			if ( !$user->CanModifyCategories() ) {
				return;
			}
			else {
				$sql = "UPDATE `$questions` SET `profileq_delid` = '1', `profileq_userid` = '" . $user->Id() . "' WHERE `profileq_id` = '" . $this->Id() . "' AND `profileq_delid` = '0' LIMIT 1;";
				$change = $db->Query( $sql );
				
				if ( $change->Impact() ) {
					$sql = "UPDATE `$profileanswers` SET `profile_delid` = '1' WHERE `profile_questionid` = '" . $this->Id() . "';";
					$db->Query( $sql );
				}
			}
		}
		private function Construct( $qid ) {
			global $db;
			global $questions;
			
			$sql = "SELECT * FROM `$questions` WHERE `profileq_id` = '$qid' LIMIT 1;";
			
			$res = $db->Query( $sql );
			return $res->FetchArray();
		}
        public function Exists() {
            return $this->mId > 0;
        }
		public function Question( $fetched_array = "" ) {
			if ( $fetched_array == "" ) {
				// Question() function, NOT constructor
				return $this->mQuestion;
			}
			else {
				if ( !is_array( $fetched_array ) ) {
					$fetched_array = $this->Construct( $fetched_array );
				}
				// Constructor function
				$this->mId			= isset( $fetched_array[ "profileq_id" ] ) ? $fetched_array[ "profileq_id" ] : 0;
				$this->mQuestion	= isset( $fetched_array[ "profileq_question" ] ) ? $fetched_array[ "profileq_question" ] : "";
				$this->mAdminId		= isset( $fetched_array[ "profileq_userid" ] ) ? $fetched_array[ "profileq_userid" ] : 0;
				$this->mAnswer		= isset( $fetched_array[ "profile_answer" ] ) ? $fetched_array[ "profile_answer" ] : "";
				$this->mDelId		= isset( $fetched_array[ "profileq_delid" ] ) ? $fetched_array[ "profileq_delid" ] : 0;
				
				$this->mAnswerFormatted = false;
			}
		}
	}
?>
